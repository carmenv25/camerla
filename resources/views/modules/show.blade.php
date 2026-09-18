@extends('layouts.app')
@section('content')
<div class="mb-4"><a href="{{ route('dashboard') }}" class="text-decoration-none">← Volver al dashboard</a></div>
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4 p-lg-5">
        <div class="d-flex justify-content-between gap-3 flex-wrap mb-3">
            <span class="badge text-bg-primary">Módulo {{ $module->position }}</span>
            <span class="badge text-bg-light">{{ $module->level }}</span>
        </div>
        <h1 class="display-6 fw-bold">{{ $module->title }}</h1>
        <p class="lead text-secondary">{{ $module->description }}</p>
        <hr class="my-4">
        <div class="content-readable">{!! $module->content !!}</div>
        <div class="d-flex flex-column flex-sm-row gap-2 mt-5">
            <a href="{{ route('evaluation.show', $module) }}" class="btn btn-primary btn-lg">Realizar evaluación</a>
            @if($progress->status === 'reinforcement')
                <a href="{{ route('learning.reinforcement', $module) }}" class="btn btn-warning btn-lg">Ver contenido de refuerzo</a>
            @endif
        </div>
    </div>
</div>
@endsection
