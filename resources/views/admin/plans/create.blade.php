@extends('layouts.admin')
@section('title', 'Nuevo Plan')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-plans.css') }}">
@endpush
@section('admin-content')
<div class="plan-form-container">
    <div class="plan-form-card">

        <div class="plan-form-header">
            <h2 class="plan-form-title">
                <i class="fas fa-plus-circle"></i>
                Crear Nuevo Plan
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

        <form action="{{ route('admin.plans.store') }}" method="POST" id="plan-form">
            @csrf

            {{-- Información básica --}}
            <div class="plan-form-section">
                <h3 class="plan-form-section-title">Información básica</h3>

                <div class="plan-form-row">
                    <div class="plan-form-group">
                        <label class="plan-form-label">Nombre del plan <span class="required">*</span></label>
                        <input type="text" name="name" class="plan-form-control"
                               value="{{ old('name') }}" required
                               placeholder="Ej: Plan Independiente">
                    </div>
                    <div class="plan-form-group">
                        <label class="plan-form-label">Orden de visualización</label>
                        <input type="number" name="sort_order" class="plan-form-control"
                               value="{{ old('sort_order', 0) }}" min="0"
                               placeholder="0">
                    </div>
                </div>

                <div class="plan-form-group">
                    <label class="plan-form-label">Descripción</label>
                    <textarea name="description" class="plan-form-control plan-form-textarea"
                              placeholder="Descripción breve del plan...">{{ old('description') }}</textarea>
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
                                   value="{{ old('price') }}" min="1" step="0.01" required
                                   placeholder="9.00">
                        </div>
                        <span class="plan-form-hint">Todos los planes incluyen 15 dias de prueba gratis</span>
                    </div>
                    <div class="plan-form-group">
                        <label class="plan-form-label">Moneda <span class="required">*</span></label>
                        <select name="currency" class="plan-form-control" required>
                            <option value="UYU" {{ old('currency', 'UYU') === 'UYU' ? 'selected' : '' }}>UYU</option>
                            <option value="USD" {{ old('currency') === 'USD' ? 'selected' : '' }}>USD</option>
                            <option value="CLP" {{ old('currency') === 'CLP' ? 'selected' : '' }}>CLP</option>
                            <option value="ARS" {{ old('currency') === 'ARS' ? 'selected' : '' }}>ARS</option>
                            <option value="COP" {{ old('currency') === 'COP' ? 'selected' : '' }}>COP</option>
                            <option value="MXN" {{ old('currency') === 'MXN' ? 'selected' : '' }}>MXN</option>
                        </select>
                    </div>
                    <div class="plan-form-group">
                        <label class="plan-form-label">Intervalo <span class="required">*</span></label>
                        <select name="interval" class="plan-form-control" required>
                            <option value="monthly" {{ old('interval', 'monthly') === 'monthly' ? 'selected' : '' }}>Mensual</option>
                            <option value="yearly" {{ old('interval') === 'yearly' ? 'selected' : '' }}>Anual</option>
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
                               value="{{ old('max_professionals') }}" min="1"
                               placeholder="Dejar vacío = ilimitado">
                        <span class="plan-form-hint">Dejar vacío para ilimitados</span>
                    </div>
                    <div class="plan-form-group">
                        <label class="plan-form-label">Precio por profesional adicional</label>
                        <div class="plan-input-prefix">
                            <span class="prefix-symbol">$</span>
                            <input type="number" name="price_per_additional_professional"
                                   class="plan-form-control plan-form-control--prefixed"
                                   value="{{ old('price_per_additional_professional') }}" min="0" step="0.01"
                                   placeholder="5.00">
                        </div>
                        <span class="plan-form-hint">Dejar vacío si no aplica</span>
                    </div>
                </div>
            </div>

            {{-- Funcionalidades --}}
            <div class="plan-form-section">
                <h3 class="plan-form-section-title">Funcionalidades incluidas</h3>

                <div class="plan-toggles-grid">
                    <label class="plan-toggle-item">
                        <div class="plan-toggle-info">
                            <i class="fas fa-brain plan-toggle-icon"></i>
                            <div>
                                <span class="plan-toggle-label">Agenda inteligente + CRM + IA</span>
                                <span class="plan-toggle-desc">Predicción de retorno, aprendizaje de horarios</span>
                            </div>
                        </div>
                        <input type="hidden" name="crm_ia_enabled" value="0">
                        <input type="checkbox" name="crm_ia_enabled" value="1" class="plan-toggle-checkbox"
                               {{ old('crm_ia_enabled', '1') ? 'checked' : '' }}>
                        <span class="plan-toggle-switch"></span>
                    </label>


                    <label class="plan-toggle-item">
                        <div class="plan-toggle-info">
                            <i class="fas fa-code-branch plan-toggle-icon"></i>
                            <div>
                                <span class="plan-toggle-label">Multi-sucursal</span>
                                <span class="plan-toggle-desc">Gestionar múltiples locales</span>
                            </div>
                        </div>
                        <input type="hidden" name="multi_branch_enabled" value="0">
                        <input type="checkbox" name="multi_branch_enabled" value="1" class="plan-toggle-checkbox"
                               {{ old('multi_branch_enabled') ? 'checked' : '' }}>
                        <span class="plan-toggle-switch"></span>
                    </label>

                    <label class="plan-toggle-item">
                        <div class="plan-toggle-info">
                            <i class="fab fa-whatsapp plan-toggle-icon plan-toggle-icon--wa"></i>
                            <div>
                                <span class="plan-toggle-label">Recordatorios WhatsApp</span>
                                <span class="plan-toggle-desc">Notificaciones automáticas de citas</span>
                            </div>
                        </div>
                        <input type="hidden" name="whatsapp_reminders" value="0">
                        <input type="checkbox" name="whatsapp_reminders" value="1" class="plan-toggle-checkbox"
                               {{ old('whatsapp_reminders', '1') ? 'checked' : '' }}>
                        <span class="plan-toggle-switch"></span>
                    </label>

                    <label class="plan-toggle-item">
                        <div class="plan-toggle-info">
                            <i class="fas fa-envelope plan-toggle-icon"></i>
                            <div>
                                <span class="plan-toggle-label">Recordatorios Email</span>
                                <span class="plan-toggle-desc">Confirmaciones y recordatorios por correo</span>
                            </div>
                        </div>
                        <input type="hidden" name="email_reminders" value="0">
                        <input type="checkbox" name="email_reminders" value="1" class="plan-toggle-checkbox"
                               {{ old('email_reminders', '1') ? 'checked' : '' }}>
                        <span class="plan-toggle-switch"></span>
                    </label>

                    <label class="plan-toggle-item">
                        <div class="plan-toggle-info">
                            <i class="fas fa-toggle-on plan-toggle-icon plan-toggle-icon--active"></i>
                            <div>
                                <span class="plan-toggle-label">Plan activo</span>
                                <span class="plan-toggle-desc">Visible y disponible para los negocios</span>
                            </div>
                        </div>
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="plan-toggle-checkbox"
                               {{ old('is_active', '1') ? 'checked' : '' }}>
                        <span class="plan-toggle-switch"></span>
                    </label>
                </div>
            </div>

            <div class="plan-form-actions">
                <button type="submit" class="btn-plan btn-plan--save">
                    <i class="fas fa-save"></i> Crear Plan
                </button>
                <a href="{{ route('admin.plans.index') }}" class="btn-plan btn-plan--secondary">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
