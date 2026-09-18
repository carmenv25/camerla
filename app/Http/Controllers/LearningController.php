<?php

namespace App\Http\Controllers;

use App\Models\LearningProgress;
use App\Models\Module;
use Illuminate\Http\Request;

class LearningController extends Controller
{
    public function reinforcement(Request $request, Module $module)
    {
        $progress = LearningProgress::where('user_id', $request->session()->get('user_id'))
            ->where('module_id', $module->id)->firstOrFail();
        abort_unless($progress->status === 'reinforcement', 403);
        return view('learning.reinforcement', compact('module'));
    }

    public function next(Request $request, Module $module)
    {
        $userId = $request->session()->get('user_id');
        $progress = LearningProgress::where('user_id', $userId)->where('module_id', $module->id)->firstOrFail();
        abort_unless($progress->status === 'completed', 403);

        $next = Module::where('position', '>', $module->position)->orderBy('position')->first();
        return $next
            ? redirect()->route('modules.show', $next)
            : redirect()->route('dashboard')->with('success', '¡Has completado todos los módulos!');
    }
}
