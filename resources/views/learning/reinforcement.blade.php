@extends('layouts.app')
@section('content')
<div class="mb-4"><a href="{{ route('modules.show', $module) }}" class="text-decoration-none">← Volver al módulo</a></div>
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4 p-lg-5">
        <span class="badge text-bg-warning mb-3">Contenido de refuerzo</span>
        <h1 class="h2 fw-bold">Repaso: {{ $module->title }}</h1>
        <p class="text-secondary">Este contenido aparece porque tu resultado indica que necesitas fortalecer algunos conceptos.</p>
        <div class="content-readable mt-4">{!! $module->reinforcement_content !!}</div>
        <div class="mt-5">
            <a href="{{ route('evaluation.show', $module) }}" class="btn btn-primary btn-lg">Volver a evaluar</a>
        </div>
    </div>
</div>
@endsection
