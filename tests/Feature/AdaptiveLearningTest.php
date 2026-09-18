<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdaptiveLearningTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_available(): void
    {
        $this->get('/login')->assertOk()->assertSee('Iniciar sesión');
    }

    public function test_student_can_login_and_see_dashboard(): void
    {
        User::create(['name' => 'Test', 'email' => 'test@example.com', 'password' => Hash::make('password'), 'role' => 'student']);
        Module::create(['position' => 1, 'title' => 'Módulo 1', 'description' => 'Desc', 'content' => '<p>Contenido</p>', 'reinforcement_content' => '<p>Refuerzo</p>', 'level' => 'Básico']);

        $this->post('/login', ['email' => 'test@example.com', 'password' => 'password'])
            ->assertRedirect('/dashboard');
        $this->get('/dashboard')->assertOk()->assertSee('Módulo 1');
    }
}
