<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('position')->unique();
            $table->string('title');
            $table->text('description');
            $table->text('content');
            $table->text('reinforcement_content');
            $table->string('level')->default('Básico');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('modules'); }
};
