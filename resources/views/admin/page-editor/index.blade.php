@extends('layouts.admin')

@section('title', 'Editor de Paginas')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-page-editor.css') }}">
@endpush

@section('admin-content')

<header class="admin-header">
    <div>
        <h1>
            <i class="fas fa-palette icon"></i>
            Editor de Paginas
        </h1>
        <div class="admin-date">
            Personaliza el contenido de tu sitio web
        </div>
    </div>
</header>

<div class="pe-pages-grid">
    <a href="{{ route('admin.page-editor.home') }}" class="pe-page-card">
        <div class="pe-page-icon">
            <i class="fas fa-home"></i>
        </div>
        <h3 class="pe-page-name">Pagina de Inicio</h3>
        <p class="pe-page-desc">
            Edita el hero, secciones de negocios, features, precios y llamada a la accion del home.
        </p>
        <span class="pe-page-link">
            Editar pagina <i class="fas fa-arrow-right"></i>
        </span>
    </a>
</div>

@endsection
