<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['module_id', 'question'];
    public function module() { return $this->belongsTo(Module::class); }
    public function options() { return $this->hasMany(Option::class); }
}
