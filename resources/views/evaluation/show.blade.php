@extends('layouts.app')
@section('content')
<div class="mb-4"><a href="{{ route('modules.show', $module) }}" class="text-decoration-none">← Volver al módulo</a></div>
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4 p-lg-5">
        <h1 class="h2 fw-bold">Evaluación: {{ $module->title }}</h1>
        <p class="text-secondary">Responde todas las preguntas. Se aprueba con 70% o más.</p>
        <form method="POST" action="{{ route('evaluation.submit', $module) }}" class="mt-4">
            @csrf
            @foreach($module->questions as $index => $question)
                <div class="question-box mb-4">
                    <h2 class="h5 fw-bold">{{ $index + 1 }}. {{ $question->question }}</h2>
                    @foreach($question->options as $option)
                        <label class="option-row">
                            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" required>
                            <span>{{ $option->label }}</span>
                        </label>
                    @endforeach
                </div>
            @endforeach
            <button class="btn btn-primary btn-lg px-4">Enviar evaluación</button>
        </form>
    </div>
</div>
@endsection
