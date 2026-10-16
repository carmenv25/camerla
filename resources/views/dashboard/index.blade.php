@php
    /** @var int|float $overallProgress */
    /** @var int $completedModules */
    /** @var int $totalModules */
    /** @var \Illuminate\Database\Eloquent\Collection $modules */
@endphp

@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Mi Progreso de Aprendizaje</h1>
    </div>

    <!-- Alertas Flash -->
    @if(session('error'))
        <div class="alert alert-danger mb-4">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Indicadores Generales (RF-005) -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase fw-bold">Avance General</h6>
                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <h2 class="mb-0 text-primary">{{ $overallProgress }}%</h2>
                        <span class="badge bg-primary fs-6">{{ $completedModules }} / {{ $totalModules }} Módulos</span>
                    </div>
                    
                    <!-- Barra de progreso -->
                    <div class="progress mt-3" style="height: 12px; background-color: #e9ecef;">
                        <div class="progress-bar bg-primary" 
                             role="progressbar" 
                             aria-valuenow="{{ $overallProgress }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100"
                             @style(['width: ' . $overallProgress . '%'])>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Seguimiento por Módulo (RF-013) -->
    <h4 class="mb-3">Módulos de Aprendizaje</h4>
    <div class="row">
        @forelse($modules as $module)
            @php
                $status = $module->user_status ?? 'not_started';
                $score = $module->user_score ?? 0;
            @endphp
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">{{ $module->title }}</h5>
                            @if($status === 'completed')
                                <span class="badge bg-success">Completado</span>
                            @elseif($status === 'reinforcement')
                                <span class="badge bg-warning text-dark">En Refuerzo</span>
                            @else
                                <span class="badge bg-secondary">Sin Iniciar</span>
                            @endif
                        </div>

                        <p class="card-text text-muted small flex-grow-1">
                            {{ $module->description }}
                        </p>

                        <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                            <small class="text-muted">Puntuación: <strong>{{ $score }}%</strong></small>
                            <a href="{{ route('modules.show', $module->id) }}" class="btn btn-sm btn-outline-primary">
                                Continuar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-muted">No hay módulos disponibles en este momento.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection