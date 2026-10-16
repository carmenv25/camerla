<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ModuleController extends Controller
{
    public function show(Module $module)
    {
        $userId = Auth::id() ?? session('user_id') ?? DB::table('users')->value('id') ?? 1;

        // 1. Identificar el primer módulo
        $firstModule = Module::orderBy('position')->orderBy('id')->first();

        // 2. Si no es el primero, verificar si el módulo anterior fue completado o aprobado
        if ($firstModule && $module->id !== $firstModule->id) {
            $previousModule = Module::where('position', '<', $module->position)
                ->orderBy('position', 'desc')
                ->first();

            if (!$previousModule) {
                $previousModule = Module::where('id', '<', $module->id)
                    ->orderBy('id', 'desc')
                    ->first();
            }

            if ($previousModule) {
                $passedAttempt = DB::table('attempts')
                    ->where('user_id', $userId)
                    ->where('module_id', $previousModule->id)
                    ->where('score', '>=', 70)
                    ->exists();

                $completedProgress = DB::table('learning_progress')
                    ->where('user_id', $userId)
                    ->where('module_id', $previousModule->id)
                    ->where('status', 'completed')
                    ->exists();

                if (!$passedAttempt && !$completedProgress) {
                    return redirect()->route('dashboard')
                        ->with('error', 'Este módulo aún no está disponible. Debes completar y aprobar el módulo anterior.');
                }
            }
        }

        // 3. Obtener el progreso del usuario para este módulo específico
        $progress = DB::table('learning_progress')
            ->where('user_id', $userId)
            ->where('module_id', $module->id)
            ->first();

        // Si no hay registro previo, enviar un objeto por defecto para evitar errores en la vista
        if (!$progress) {
            $progress = (object) [
                'status' => 'not_started',
                'last_score' => 0
            ];
        }

        return view('modules.show', compact('module', 'progress'));
    }
}