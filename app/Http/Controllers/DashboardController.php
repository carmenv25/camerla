<?php

namespace App\Http\Controllers;

use App\Models\LearningProgress;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController
{
    public function index(Request $request)
    {
        $user = User::findOrFail($request->session()->get('user_id'));
        $modules = Module::orderBy('position')->get();
        $progress = LearningProgress::where('user_id', $user->id)->get()->keyBy('module_id');

        if ($progress->isEmpty() && $modules->isNotEmpty()) {
            LearningProgress::create([
                'user_id' => $user->id,
                'module_id' => $modules->first()->id,
                'status' => 'available',
            ]);
            $progress = LearningProgress::where('user_id', $user->id)->get()->keyBy('module_id');
        }

        return view('dashboard.index', compact('user', 'modules', 'progress'));
    }
}
