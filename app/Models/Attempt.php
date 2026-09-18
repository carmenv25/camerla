<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    protected $fillable = [
        'user_id',
        'module_id',
        'score',
        'correct_answers',
        'total_questions',
        'approved',
    ];

    protected $casts = [
        'approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function answers()
    {
        return $this->hasMany(AttemptAnswer::class);
    }
}