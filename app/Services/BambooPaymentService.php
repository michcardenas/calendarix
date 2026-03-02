<?php

namespace App\Services;

use App\Models\BambooPaymentLog;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BambooPaymentService
{
    private string $baseUrl;
    private string $privateKey;
    private string $publicKey;
    private string $targetCountry;
    private string $currency;

    public function __construct()
    {
        $this->baseUrl       = config('bamboo.base_url');
        $this->privateKey    = config('bamboo.private_key');
        $this->publicKey     = config('bamboo.public_key');
        $this->targetCountry = config('bamboo.target_country');
        $this->currency      = config('bamboo.currency');
    }

    /**
     * Build auth headers for Bamboo API.
     * Authorization: Basic base64(privateKey + ":")
     */
    private function authHeaders(): array
    {
        return [
            'Authorization' => 'Basic ' . $this->privateKey,
            'Content-Type'  => 'application/json',
        ];
    }

    /**
     * Create a customer in Bamboo Payment.
     * POST /v1/api/customer
     */
    public function createCustomer(User $user): array
    {
        $endpoint = $this->baseUrl . '/v1/api/customer';

        $nameParts = explode(' ', $user->name, 2);
        $firstName = $nameParts[0];
        $lastName  = $nameParts[1] ?? $firstName;

        $payload = [
            'Email'          => $user->email,
            'FirstName'      => $firstName,
            'LastName'       => $lastName,
            'DocNumber'      => $user->dni ?? '',
            'DocumentTypeId' => 2, // CI Uruguay
            'PhoneNumber'    => $user->celular ?? '',
            'BillingAddress' => [
                'AddressType'   => 1,
                'Country'       => 'UY',
                'State'         => $user->ciudad ?? 'Montevideo',
                'City'          => $user->ciudad ?? 'Montevideo',
                'AddressDetail' => '10000',
            ],
        ];

        try {
            $response = Http::withHeaders($this->authHeaders())
                ->timeout(30)
                ->post($endpoint, $payload);

            $data       = $response->json();
            $httpStatus = $response->status();
            $responseData = $data['Response'] ?? [];
            $success    = $response->successful() && isset($responseData['CustomerId']);

            $this->logApiCall(
                userId: $user->id,
                action: 'create_customer',
                requestPayload: $payload,
                responsePayload: $data ?? [],
                httpStatus: $httpStatus,
                success: $success,
            );

            if ($success) {
                return [
                    'success'     => true,
                    'customer_id' => $responseData['CustomerId'],
                    'unique_id'   => $responseData['UniqueID'] ?? null,
                    'data'        => $data,
                    'error'       => null,
                ];
            }

            $errors = $data['Errors'] ?? [];
            $errorMsg = !empty($errors) ? ($errors[0]['Detail'] ?? $errors[0]['Message'] ?? 'Error al crear customer en Bamboo') : ($data['message'] ?? 'Error al crear customer en Bamboo');

            return [
                'success' => false,
                'data'    => $data,
                'error'   => $errorMsg,
            ];
        } catch (\Exception $e) {
            Log::error('BambooPayment createCustomer exception', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);

            $this->logApiCall(
                userId: $user->id,
                action: 'create_customer',
                requestPayload: $payload,
                responsePayload: [],
                httpStatus: 0,
                success: false,
                errorMessage: $e->getMessage(),
            );

            return [
                'success' => false,
                'data'    => [],
                'error'   => 'No se pudo conectar con el procesador de pagos. Intenta más tarde.',
            ];
        }
    }

    /**
     * Create a purchase (charge) using a Commerce Token.
     * POST /v3/api/purchase
     * Amount must be in cents (multiply by 100).
     */
    public function createPurchase(
        string $trxToken,
        float  $amount,
        string $currency,
        string $uniqueId,
        User   $user,
        string $order,
        string $description,
        ?int   $subscriptionId = null,
    ): array {
        $endpoint = $this->baseUrl . '/v3/api/purchase';

        $nameParts = explode(' ', $user->name, 2);

        $payload = [
            'TrxToken'         => $trxToken,
            'Capture'          => true,
            'Order'            => $order,
            'Amount'           => (int) round($amount * 100), // en centavos
            'Currency'         => $currency,
            'Installments'     => 1,
            'TargetCountryISO' => $this->targetCountry,
            'Description'      => $description,
            'Customer'         => [
                'FirstName'      => $nameParts[0],
                'LastName'       => $nameParts[1] ?? $nameParts[0],
                'Email'          => $user->email,
                'PhoneNumber'    => $user->celular ?? '',
                'DocumentNumber' => $user->dni ?? '',
                'DocumentType'   => 'CI.UY',
            ],
        ];

        try {
            $response = Http::withHeaders($this->authHeaders())
                ->timeout(30)
                ->post($endpoint, $payload);

            $data       = $response->json();
            $httpStatus = $response->status();

            // Bamboo v3 may return at top level or wrapped in Response
            $purchaseData = isset($data['Response']) ? $data['Response'] : $data;

            // Bamboo response: Status = "Approved", "Rejected", "Pending"
            $status  = $purchaseData['Status'] ?? '';
            $success = $response->successful() && strtolower($status) === 'approved';

            $this->logApiCall(
                userId: $user->id,
                subscriptionId: $subscriptionId,
                action: 'purchase',
                requestPayload: $payload,
                responsePayload: $data ?? [],
                httpStatus: $httpStatus,
                success: $success,
                errorMessage: $success ? null : ($purchaseData['ErrorDescription'] ?? $status),
            );

            if ($success) {
                return [
                    'success'     => true,
                    'purchase_id' => $purchaseData['PurchaseId'] ?? null,
                    'status'      => $status,
                    'data'        => $data,
                    'error'       => null,
                ];
            }

            $errors = $data['Errors'] ?? [];
            $errorMsg = !empty($errors) ? ($errors[0]['Detail'] ?? $errors[0]['Message'] ?? 'Pago rechazado por el procesador') : ($purchaseData['ErrorDescription'] ?? 'Pago rechazado por el procesador');

            return [
                'success' => false,
                'status'  => $status,
                'data'    => $data,
                'error'   => $errorMsg,
            ];
        } catch (\Exception $e) {
            Log::error('BambooPayment createPurchase exception', [
                'user_id' => $user->id,
                'order'   => $order,
                'error'   => $e->getMessage(),
            ]);

            $this->logApiCall(
                userId: $user->id,
                subscriptionId: $subscriptionId,
                action: 'purchase',
                requestPayload: $payload,
                responsePayload: [],
                httpStatus: 0,
                success: false,
                errorMessage: $e->getMessage(),
            );

            return [
                'success' => false,
                'data'    => [],
                'error'   => 'No se pudo conectar con el procesador de pagos. Intenta más tarde.',
            ];
        }
    }

    /**
     * Get the public key for the tokenization form.
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    /**
     * Get the capture script URL for the tokenization form.
     */
    public function getCaptureScriptUrl(): string
    {
        return config('bamboo.capture_script');
    }

    /**
     * Get the capture script integrity hash for SRI.
     */
    public function getCaptureIntegrity(): string
    {
        return config('bamboo.capture_integrity');
    }

    /**
     * Get the target country ISO code.
     */
    public function getTargetCountry(): string
    {
        return $this->targetCountry;
    }

    /**
     * Log an API call to bamboo_payment_logs.
     */
    private function logApiCall(
        int    $userId,
        string $action,
        array  $requestPayload,
        array  $responsePayload,
        int    $httpStatus,
        bool   $success,
        ?int   $subscriptionId = null,
        ?string $errorMessage = null,
    ): void {
        try {
            BambooPaymentLog::create([
                'user_id'          => $userId,
                'subscription_id'  => $subscriptionId,
                'action'           => $action,
                'request_payload'  => $requestPayload,
                'response_payload' => $responsePayload,
                'http_status'      => $httpStatus,
                'success'          => $success,
                'error_message'    => $errorMessage,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log Bamboo API call', ['error' => $e->getMessage()]);
        }
    }
}
