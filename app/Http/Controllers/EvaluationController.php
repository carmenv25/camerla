<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Attempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    /**
     * Muestra el cuestionario de evaluación del módulo.
     */
    public function show(Module $module)
    {
        // Cargar preguntas con sus opciones asociadas
        $module->load('questions.options');

        return view('evaluation.show', compact('module'));
    }

    /**
     * Califica las respuestas y guarda el intento y progreso.
     */
    public function submit(Request $request, Module $module)
    {
        $userId = Auth::id() ?? session('user_id') ?? DB::table('users')->value('id') ?? 1;
        $userAnswers = $request->input('answers', []);

        // Cargar preguntas y opciones
        $module->load('questions.options');
        $totalQuestions = $module->questions->count();
        $correctCount = 0;

        foreach ($module->questions as $question) {
            $selectedOptionId = $userAnswers[$question->id] ?? null;

            if ($selectedOptionId) {
                // Verificar si la opción seleccionada es la correcta
                $selectedOption = $question->options->firstWhere('id', $selectedOptionId);

                if ($selectedOption && ($selectedOption->is_correct ?? false)) {
                    $correctCount++;
                }
            }
        }

        // Calcular puntaje
        $score = $totalQuestions > 0 ? (int) round(($correctCount / $totalQuestions) * 100) : 0;
        $status = $score >= 70 ? 'completed' : 'reinforcement';

        // 1. Guardar intento en attempts
        $attempt = Attempt::create([
            'user_id' => $userId,
            'module_id' => $module->id,
            'score' => $score,
            'correct_answers' => $correctCount,
            'total_questions' => $totalQuestions,
            'approved' => $status === 'completed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Actualizar o crear registro en learning_progress
        $existing = DB::table('learning_progress')
            ->where('user_id', $userId)
            ->where('module_id', $module->id)
            ->first();

        if ($existing) {
            DB::table('learning_progress')
                ->where('user_id', $userId)
                ->where('module_id', $module->id)
                ->update([
                    'status' => $status,
                    'last_score' => $score,
                    'completed_at' => $status === 'completed'
                        ? ($existing->completed_at ?? now())
                        : null,
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('learning_progress')->insert([
                'user_id' => $userId,
                'module_id' => $module->id,
                'status' => $status,
                'last_score' => $score,
                'completed_at' => $status === 'completed' ? now() : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Si tienes la vista result.blade.php, redirige o renderiza con los datos
        if (view()->exists('evaluation.result')) {
            $next = Module::where('position', '>', $module->position)
                ->orderBy('position')
                ->first();

            return view('evaluation.result', compact('module', 'attempt', 'next', 'score', 'status', 'correctCount', 'totalQuestions'));
        }

        return redirect()->route('dashboard')->with('success', "Evaluación enviada con éxito. Calificación: {$score}%.");
    }
}