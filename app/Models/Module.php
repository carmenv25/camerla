<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['position', 'title', 'description', 'content', 'reinforcement_content', 'level'];
    public function questions() { return $this->hasMany(Question::class); }
    public function attempts() { return $this->hasMany(Attempt::class); }
    public function progress() { return $this->hasMany(LearningProgress::class); }
}
