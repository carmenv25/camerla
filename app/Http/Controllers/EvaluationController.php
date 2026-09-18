<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\AttemptAnswer;
use App\Models\LearningProgress;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    public function show(Request $request, Module $module)
    {
        $this->ensureAvailable($request, $module);
        $module->load(['questions.options']);
        return view('evaluation.show', compact('module'));
    }

    public function submit(Request $request, Module $module)
    {
        $this->ensureAvailable($request, $module);
        $module->load(['questions.options']);
        $answers = $request->input('answers', []);
        $userId = $request->session()->get('user_id');

        $result = DB::transaction(function () use ($module, $answers, $userId) {
            $total = $module->questions->count();
            $correct = 0;
            $evaluated = [];

            foreach ($module->questions as $question) {
                $selectedId = $answers[$question->id] ?? null;
                $selected = $question->options->firstWhere('id', (int) $selectedId);
                $isCorrect = $selected?->is_correct === true;
                if ($isCorrect) $correct++;
                $evaluated[] = [$question->id, $selected?->id, $isCorrect];
            }

            $score = $total > 0 ? (int) round(($correct / $total) * 100) : 0;
            $approved = $score >= 70;
            $attempt = Attempt::create([
                'user_id' => $userId,
                'module_id' => $module->id,
                'score' => $score,
                'correct_answers' => $correct,
                'total_questions' => $total,
                'approved' => $approved,
            ]);

            foreach ($evaluated as [$questionId, $optionId, $isCorrect]) {
                AttemptAnswer::create([
                    'attempt_id' => $attempt->id,
                    'question_id' => $questionId,
                    'option_id' => $optionId,
                    'is_correct' => $isCorrect,
                ]);
            }

            $progress = LearningProgress::firstOrCreate(
                ['user_id' => $userId, 'module_id' => $module->id],
                ['status' => 'available']
            );
            $progress->update([
                'status' => $approved ? 'completed' : 'reinforcement',
                'last_score' => $score,
                'completed_at' => $approved ? now() : null,
            ]);

            if ($approved) {
                $next = Module::where('position', '>', $module->position)->orderBy('position')->first();
                if ($next) {
                    LearningProgress::firstOrCreate(
                        ['user_id' => $userId, 'module_id' => $next->id],
                        ['status' => 'available']
                    );
                }
            }

            return $attempt;
        });

        return redirect()->route('evaluation.result', $result);
    }

    public function result(Request $request, Attempt $attempt)
    {
        abort_unless($attempt->user_id === $request->session()->get('user_id'), 403);
        $attempt->load('module');
        $next = Module::where('position', '>', $attempt->module->position)->orderBy('position')->first();
        return view('evaluation.result', compact('attempt', 'next'));
    }

    private function ensureAvailable(Request $request, Module $module): void
    {
        $progress = LearningProgress::where('user_id', $request->session()->get('user_id'))
            ->where('module_id', $module->id)->first();
        abort_unless($progress && $progress->status !== 'locked', 403);
    }
}
