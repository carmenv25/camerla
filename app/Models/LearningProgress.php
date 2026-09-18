<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningProgress extends Model
{
    protected $table = 'learning_progress';
    protected $fillable = ['user_id', 'module_id', 'status', 'last_score', 'completed_at'];
    protected $casts = ['completed_at' => 'datetime'];
    public function user() { return $this->belongsTo(User::class); }
    public function module() { return $this->belongsTo(Module::class); }
}
