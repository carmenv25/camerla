@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
    <div class="card border-0 shadow-lg rounded-4 text-center">
        <div class="card-body p-5">
            <div class="result-circle mx-auto mb-4 {{ $attempt->approved ? 'approved' : 'needs-work' }}">{{ $attempt->score }}%</div>
            <h1 class="h2 fw-bold">{{ $attempt->approved ? '¡Evaluación aprobada!' : 'Necesitas refuerzo' }}</h1>
            <p class="text-secondary">{{ $attempt->correct_answers }} de {{ $attempt->total_questions }} respuestas correctas en <strong>{{ $attempt->module->title }}</strong>.</p>
            @if($attempt->approved)
                <div class="alert alert-success mt-4">Tu resultado habilitó el siguiente paso de la ruta de aprendizaje.</div>
                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    @if($next)
                        <a href="{{ route('learning.next', $attempt->module) }}" class="btn btn-primary">Siguiente módulo</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">Ver dashboard</a>
                    @endif
                </div>
            @else
                <div class="alert alert-warning mt-4">La plataforma recomienda contenido de refuerzo antes de volver a evaluar.</div>
                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <a href="{{ route('learning.reinforcement', $attempt->module) }}" class="btn btn-warning">Contenido de refuerzo</a>
                    <a href="{{ route('modules.show', $attempt->module) }}" class="btn btn-outline-primary">Volver al módulo</a>
                </div>
            @endif
        </div>
    </div>
</div>
</div>
@endsection
