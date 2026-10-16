<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Obtener ID de usuario de forma segura
        $userId = Auth::id() ?? session('user_id') ?? DB::table('users')->value('id') ?? 1;

        // 2. Obtener los módulos ordenados
        $modules = Module::orderBy('position')->orderBy('id')->get();
        $totalModules = $modules->count();

        // 3. Obtener el intento más reciente por módulo registrado para este usuario
        $latestAttemptIds = DB::table('attempts')
            ->where('user_id', $userId)
            ->selectRaw('MAX(id)')
            ->groupBy('module_id');

        $attempts = DB::table('attempts')
            ->whereIn('id', $latestAttemptIds)
            ->select('module_id', 'score as latest_score')
            ->get()
            ->keyBy('module_id');

        // 4. Obtener los registros de progreso
        $progressRecords = DB::table('learning_progress')
            ->where('user_id', $userId)
            ->get()
            ->keyBy('module_id');

        $completedModules = 0;

        foreach ($modules as $module) {
            $prog = $progressRecords->get($module->id);
            $att = $attempts->get($module->id);

            // Obtener el puntaje del intento más reciente
            $score = 0;
            if ($att && isset($att->latest_score)) {
                $score = (int) $att->latest_score;
            } elseif ($prog) {
                $score = (int) ($prog->score ?? $prog->last_score ?? 0);
            }

            // Estado del módulo
            $statusDb = $prog ? strtolower((string) ($prog->status ?? '')) : '';

            if ($statusDb === 'completed' || $score >= 70) {
                $module->user_status = 'completed';
                $completedModules++;
            } elseif ($statusDb === 'reinforcement' || ($score > 0 && $score < 70)) {
                $module->user_status = 'reinforcement';
            } else {
                $module->user_status = 'not_started';
            }

            $module->user_score = $score;
        }

        // 5. Avance general en porcentaje
        $overallProgress = $totalModules > 0 
            ? (int) round(($completedModules / $totalModules) * 100) 
            : 0;

        return view('dashboard.index', compact(
            'overallProgress',
            'completedModules',
            'totalModules',
            'modules'
        ));
    }
}