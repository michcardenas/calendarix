@extends('layouts.admin')
@section('title', 'Editar Plan')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-plans.css') }}">
@endpush
@section('admin-content')
<div class="plan-form-container">
    <div class="plan-form-card">

        <div class="plan-form-header">
            <h2 class="plan-form-title">
                <i class="fas fa-edit"></i>
                Editar Plan: {{ $plan->name }}
            </h2>
            <a href="{{ route('admin.plans.index') }}" class="btn-plan btn-plan--secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>

        @if($errors->any())
            <div class="plans-alert plans-alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <ul style="margin:0;padding-left:1.2rem">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.plans.update', $plan) }}" method="POST" id="plan-form">
            @csrf
            @method('PUT')

            {{-- Información básica --}}
            <div class="plan-form-section">
                <h3 class="plan-form-section-title">Información básica</h3>

                <div class="plan-form-row">
                    <div class="plan-form-group">
                        <label class="plan-form-label">Nombre del plan <span class="required">*</span></label>
                        <input type="text" name="name" class="plan-form-control"
                               value="{{ old('name', $plan->name) }}" required
                               placeholder="Ej: Plan Independiente">
                    </div>
                    <div class="plan-form-group">
                        <label class="plan-form-label">Orden de visualización</label>
                        <input type="number" name="sort_order" class="plan-form-control"
                               value="{{ old('sort_order', $plan->sort_order) }}" min="0"
                               placeholder="0">
                    </div>
                </div>

                <div class="plan-form-group">
                    <label class="plan-form-label">Descripción</label>
                    <textarea name="description" class="plan-form-control plan-form-textarea"
                              placeholder="Descripción breve del plan...">{{ old('description', $plan->description) }}</textarea>
                </div>
            </div>

            {{-- Precio --}}
            <div class="plan-form-section">
                <h3 class="plan-form-section-title">Precio y facturación</h3>

                <div class="plan-form-row plan-form-row--3">
                    <div class="plan-form-group">
                        <label class="plan-form-label">Precio <span class="required">*</span></label>
                        <div class="plan-input-prefix">
                            <span class="prefix-symbol">$</span>
                            <input type="number" name="price" class="plan-form-control plan-form-control--prefixed"
                                   value="{{ old('price', $plan->price) }}" min="0" step="0.01" required
                                   placeholder="9.00">
                        </div>
                    </div>
                    <div class="plan-form-group">
                        <label class="plan-form-label">Moneda <span class="required">*</span></label>
                        <select name="currency" class="plan-form-control" required>
                            @foreach(['USD','CLP','ARS','COP','MXN'] as $cur)
                                <option value="{{ $cur }}" {{ old('currency', $plan->currency) === $cur ? 'selected' : '' }}>{{ $cur }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="plan-form-group">
                        <label class="plan-form-label">Intervalo <span class="required">*</span></label>
                        <select name="interval" class="plan-form-control" required>
                            <option value="monthly" {{ old('interval', $plan->interval) === 'monthly' ? 'selected' : '' }}>Mensual</option>
                            <option value="yearly"  {{ old('interval', $plan->interval) === 'yearly'  ? 'selected' : '' }}>Anual</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Profesionales --}}
            <div class="plan-form-section">
                <h3 class="plan-form-section-title">Profesionales</h3>

                <div class="plan-form-row">
                    <div class="plan-form-group">
                        <label class="plan-form-label">Máximo de profesionales incluidos</label>
                        <input type="number" name="max_professionals" class="plan-form-control"
                               value="{{ old('max_professionals', $plan->max_professionals) }}" min="1"
                               placeholder="Dejar vacío = ilimitado">
                        <span class="plan-form-hint">Dejar vacío para ilimitados</span>
                    </div>
                    <div class="plan-form-group">
                        <label class="plan-form-label">Precio por profesional adicional</label>
                        <div class="plan-input-prefix">
                            <span class="prefix-symbol">$</span>
                            <input type="number" name="price_per_additional_professional"
                                   class="plan-form-control plan-form-control--prefixed"
                                   value="{{ old('price_per_additional_professional', $plan->price_per_additional_professional) }}"
                                   min="0" step="0.01" placeholder="5.00">
                        </div>
                        <span class="plan-form-hint">Dejar vacío si no aplica</span>
                    </div>
                </div>
            </div>

            {{-- Funcionalidades --}}
            <div class="plan-form-section">
                <h3 class="plan-form-section-title">Funcionalidades incluidas</h3>

                @php
                    $toggles = [
                        ['name' => 'crm_ia_enabled',       'icon' => 'fas fa-brain',         'label' => 'Agenda inteligente + CRM + IA',  'desc' => 'Predicción de retorno, aprendizaje de horarios', 'extra_class' => ''],
                        ['name' => 'multi_branch_enabled', 'icon' => 'fas fa-code-branch',    'label' => 'Multi-sucursal',                 'desc' => 'Gestionar múltiples locales', 'extra_class' => ''],
                        ['name' => 'whatsapp_reminders',   'icon' => 'fab fa-whatsapp',       'label' => 'Recordatorios WhatsApp',         'desc' => 'Notificaciones automáticas de citas', 'extra_class' => 'plan-toggle-icon--wa'],
                        ['name' => 'email_reminders',      'icon' => 'fas fa-envelope',       'label' => 'Recordatorios Email',            'desc' => 'Confirmaciones y recordatorios por correo', 'extra_class' => ''],
                        ['name' => 'is_active',            'icon' => 'fas fa-toggle-on',      'label' => 'Plan activo',                   'desc' => 'Visible y disponible para los negocios', 'extra_class' => 'plan-toggle-icon--active'],
                    ];
                @endphp

                <div class="plan-toggles-grid">
                    @foreach($toggles as $toggle)
                        <label class="plan-toggle-item">
                            <div class="plan-toggle-info">
                                <i class="{{ $toggle['icon'] }} plan-toggle-icon {{ $toggle['extra_class'] }}"></i>
                                <div>
                                    <span class="plan-toggle-label">{{ $toggle['label'] }}</span>
                                    <span class="plan-toggle-desc">{{ $toggle['desc'] }}</span>
                                </div>
                            </div>
                            <input type="hidden" name="{{ $toggle['name'] }}" value="0">
                            <input type="checkbox" name="{{ $toggle['name'] }}" value="1" class="plan-toggle-checkbox"
                                   {{ old($toggle['name'], $plan->{$toggle['name']}) ? 'checked' : '' }}>
                            <span class="plan-toggle-switch"></span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="plan-form-actions">
                <button type="submit" class="btn-plan btn-plan--save">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="{{ route('admin.plans.index') }}" class="btn-plan btn-plan--secondary">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
