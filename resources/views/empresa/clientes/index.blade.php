@extends('layouts.empresa')

@section('title', 'Clientes')

@section('content')
    <div class="px-6 py-8">
        {{-- Encabezado --}}
        <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
            <div>
                <h2 class="text-2xl font-bold text-[#5a31d7]">👥 Clientes</h2>
                <p class="text-sm text-[#3B4269B3]">Gestión de tus clientes registrados.</p>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
                <button type="button" id="btnExportar" onclick="submitExport()"
                    style="display:inline-flex; align-items:center; gap:6px; background:#059669; color:#fff; padding:8px 16px; border-radius:6px; font-size:0.875rem; font-weight:600; border:none; cursor:pointer; opacity:0.4; pointer-events:none; transition:all 0.15s;">
                    <i class="fas fa-file-csv"></i> Exportar <span id="exportCount"></span>
                </button>
                <button onclick="document.getElementById('modalCrearCliente').classList.remove('hidden')"
                    class="bg-[#5a31d7] hover:bg-[#7b5ce0] text-white px-4 py-2 rounded text-sm transition-all"
                    style="font-weight:600;">
                    <i class="fas fa-user-plus mr-2"></i> Nuevo Cliente
                </button>
            </div>
        </div>

        {{-- Form oculto para exportación (fuera de la tabla) --}}
        <form id="formExportar" method="POST" action="{{ route('empresa.clientes.exportar', ['empresa' => $empresa->id]) }}" style="display:none;">
            @csrf
            <div id="exportIds"></div>
        </form>

        {{-- Tabla de clientes --}}
        <div class="bg-white rounded-lg shadow overflow-x-auto border border-[#5a31d71A]">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-[#f6f5f7] text-[#3B4269] font-semibold">
                    <tr>
                        <th style="width:40px; padding:8px 12px; text-align:center;">
                            <input type="checkbox" id="selectAll" style="cursor:pointer; width:16px; height:16px; accent-color:#5a31d7;">
                        </th>
                        <th class="px-4 py-2">Nombre</th>
                        <th class="px-4 py-2">Correo</th>
                        <th class="px-4 py-2">Teléfono</th>
                        <th class="px-4 py-2 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($clientes as $cliente)
                        <tr class="hover:bg-[#f6f5f7] transition">
                            <td style="padding:8px 12px; text-align:center;">
                                <input type="checkbox" data-id="{{ $cliente->id }}"
                                    class="cliente-check" style="cursor:pointer; width:16px; height:16px; accent-color:#5a31d7;">
                            </td>
                            <td class="px-4 py-2">{{ $cliente->nombre }}</td>
                            <td class="px-4 py-2">{{ $cliente->email ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $cliente->telefono ?? '—' }}</td>
                            <td class="px-4 py-2 text-center space-x-2">
                                <button type="button" onclick="document.getElementById('modalEditarCliente{{ $cliente->id }}').classList.remove('hidden')"
                                    class="text-[#5a31d7] hover:underline font-medium">Editar</button>

                                <form action="{{ route('empresa.clientes.destroy', ['empresa' => $empresa->id, 'cliente' => $cliente->id]) }}"
                                    method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline"
                                        onclick="return confirm('¿Estás seguro de eliminar este cliente?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>

                        {{-- 🔁 Modal de edición individual --}}
                        @include('empresa.clientes.partials.modal-editar-cliente', ['cliente' => $cliente])
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-gray-400">No hay clientes registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ➕ Modal Crear Cliente --}}
    @include('empresa.clientes.partials.modal-crear-cliente')
@endsection

@push('scripts')
<script>
    function submitExport() {
        var checked = document.querySelectorAll('.cliente-check:checked');
        if (checked.length === 0) return;

        var container = document.getElementById('exportIds');
        container.innerHTML = '';
        checked.forEach(function (cb) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = cb.getAttribute('data-id');
            container.appendChild(input);
        });

        document.getElementById('formExportar').submit();
    }

    document.addEventListener('DOMContentLoaded', function () {
        var selectAll = document.getElementById('selectAll');
        var btnExportar = document.getElementById('btnExportar');
        var exportCount = document.getElementById('exportCount');

        function getChecks() {
            return document.querySelectorAll('.cliente-check');
        }

        function getCheckedCount() {
            return document.querySelectorAll('.cliente-check:checked').length;
        }

        function updateBtn() {
            var count = getCheckedCount();
            if (count > 0) {
                btnExportar.style.opacity = '1';
                btnExportar.style.pointerEvents = 'auto';
                exportCount.textContent = '(' + count + ')';
            } else {
                btnExportar.style.opacity = '0.4';
                btnExportar.style.pointerEvents = 'none';
                exportCount.textContent = '';
            }
        }

        selectAll.addEventListener('change', function () {
            var checks = getChecks();
            for (var i = 0; i < checks.length; i++) {
                checks[i].checked = this.checked;
            }
            updateBtn();
        });

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('cliente-check')) {
                var total = getChecks().length;
                var checked = getCheckedCount();
                selectAll.checked = checked === total;
                selectAll.indeterminate = checked > 0 && checked < total;
                updateBtn();
            }
        });
    });
</script>
@endpush
