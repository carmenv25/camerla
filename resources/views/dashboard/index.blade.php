@extends('layouts.app')
@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <p class="text-primary fw-semibold mb-1">Dashboard</p>
        <h1 class="display-6 fw-bold mb-1">Hola, {{ $user->name }} 👋</h1>
        <p class="text-secondary mb-0">Tu ruta de aprendizaje se adapta según tus resultados.</p>
    </div>
    <div class="progress-summary"><span class="small text-secondary">Ruta</span><strong>{{ $progress->where('status','completed')->count() }} / {{ $modules->count() }} módulos</strong></div>
</div>
<div class="row g-4">
@foreach($modules as $module)
    @php $p = $progress->get($module->id); @endphp
    <div class="col-md-6 col-lg-4">
        <div class="card module-card h-100 border-0 shadow-sm {{ $p?->status === 'locked' ? 'locked' : '' }}">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="badge text-bg-light">Módulo {{ $module->position }}</span>
                    @if($p?->status === 'completed') <span class="badge text-bg-success">Completado</span>
                    @elseif($p?->status === 'reinforcement') <span class="badge text-bg-warning">Refuerzo</span>
                    @elseif($p?->status === 'available') <span class="badge text-bg-primary">Disponible</span>
                    @else <span class="badge text-bg-secondary">Bloqueado</span> @endif
                </div>
                <h2 class="h5 fw-bold">{{ $module->title }}</h2>
                <p class="text-secondary">{{ $module->description }}</p>
                <div class="small text-secondary mb-3">Nivel: {{ $module->level }}</div>
                @if($p && $p->status !== 'locked')
                    <a href="{{ route('modules.show', $module) }}" class="btn btn-outline-primary w-100">Abrir módulo</a>
                @else
                    <button class="btn btn-outline-secondary w-100" disabled>Bloqueado</button>
                @endif
            </div>
        </div>
    </div>
@endforeach
</div>
<div class="flow-note mt-5 p-4 rounded-4">
    <h3 class="h5 fw-bold">Ruta adaptativa</h3>
    <p class="mb-0">Evaluación → análisis del resultado → si apruebas, se habilita el siguiente módulo; si no, se muestra contenido de refuerzo y puedes volver a evaluar.</p>
</div>
@endsection
