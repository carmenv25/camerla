<?php

namespace App\Http\Controllers;

use App\Models\LearningProgress;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function show(Request $request, Module $module)
    {
        $userId = $request->session()->get('user_id');
        $progress = LearningProgress::where('user_id', $userId)->where('module_id', $module->id)->first();

        if (! $progress || $progress->status === 'locked') {
            return redirect()->route('dashboard')->with('error', 'Este módulo aún no está disponible.');
        }

        return view('modules.show', compact('module', 'progress'));
    }
}
