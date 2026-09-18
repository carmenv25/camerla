<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Question;
use App\Models\Option;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'estudiante@demo.com'],
            ['name' => 'Estudiante Demo', 'password' => Hash::make('password'), 'role' => 'student']
        );

        $modules = [
            [
                'position' => 1,
                'title' => 'Fundamentos del aprendizaje',
                'description' => 'Conceptos básicos sobre objetivos, estudio activo y evaluación.',
                'level' => 'Básico',
                'content' => '<p>En este módulo conocerás cómo organizar un objetivo de aprendizaje, dividirlo en pequeñas metas y comprobar el dominio mediante una evaluación.</p><ul><li>Define un objetivo concreto.</li><li>Estudia conceptos clave.</li><li>Comprueba lo aprendido con preguntas.</li></ul>',
                'reinforcement_content' => '<p>Repasa los conceptos de objetivo, práctica y evaluación. Intenta explicar con tus propias palabras qué aprendiste antes de repetir la evaluación.</p>',
                'questions' => [
                    ['question' => '¿Qué debe tener un buen objetivo de aprendizaje?', 'options' => ['Ser claro y medible', 'Ser lo más ambiguo posible', 'No tener un plazo', 'No poder comprobarse']],
                    ['question' => '¿Para qué sirve una evaluación?', 'options' => ['Comprobar el dominio', 'Evitar el estudio', 'Eliminar la retroalimentación', 'Bloquear todos los módulos']],
                    ['question' => '¿Qué acción favorece el aprendizaje activo?', 'options' => ['Practicar y explicar', 'Solo leer títulos', 'No responder preguntas', 'Memorizar sin comprender']],
                ],
            ],
            [
                'position' => 2,
                'title' => 'Pensamiento lógico',
                'description' => 'Desarrolla razonamiento mediante condiciones, secuencias y problemas sencillos.',
                'level' => 'Intermedio',
                'content' => '<p>El pensamiento lógico permite analizar información, reconocer relaciones y tomar decisiones siguiendo reglas.</p><ul><li>Identifica datos.</li><li>Define condiciones.</li><li>Ordena pasos.</li></ul>',
                'reinforcement_content' => '<p>Practica identificando la condición, la acción y el resultado en problemas cotidianos. Luego vuelve a la evaluación.</p>',
                'questions' => [
                    ['question' => '¿Qué representa una condición?', 'options' => ['Una regla que determina una decisión', 'Un resultado aleatorio', 'Un dato sin uso', 'Una imagen']],
                    ['question' => '¿Qué ayuda a resolver un problema?', 'options' => ['Dividirlo en pasos', 'Ignorar los datos', 'Cambiar las reglas', 'Evitar comprobar resultados']],
                    ['question' => 'Si una condición es verdadera, normalmente se ejecuta…', 'options' => ['La acción asociada', 'Ninguna instrucción', 'Siempre un error', 'El módulo anterior']],
                ],
            ],
            [
                'position' => 3,
                'title' => 'Aplicación práctica',
                'description' => 'Integra los conocimientos mediante ejercicios y toma de decisiones.',
                'level' => 'Avanzado',
                'content' => '<p>En este módulo integrarás conceptos anteriores para analizar situaciones, seleccionar soluciones y justificar decisiones.</p>',
                'reinforcement_content' => '<p>Vuelve a revisar los fundamentos y construye un pequeño ejemplo paso a paso antes de intentar nuevamente la evaluación.</p>',
                'questions' => [
                    ['question' => '¿Cuál es el mejor primer paso ante un problema?', 'options' => ['Comprender el problema', 'Elegir una respuesta al azar', 'Ignorar restricciones', 'Saltar directamente al resultado']],
                    ['question' => '¿Qué aporta la retroalimentación?', 'options' => ['Indica qué mejorar', 'Elimina el aprendizaje', 'Oculta los errores', 'Bloquea el progreso']],
                    ['question' => '¿Qué caracteriza una solución bien justificada?', 'options' => ['Relaciona datos, pasos y resultado', 'No explica nada', 'Depende del azar', 'Evita comprobarse']],
                ],
            ],
        ];

        foreach ($modules as $data) {
            $questions = $data['questions'];
            unset($data['questions']);
            $module = Module::updateOrCreate(['position' => $data['position']], $data);
            $module->questions()->delete();
            foreach ($questions as $questionData) {
                $question = Question::create(['module_id' => $module->id, 'question' => $questionData['question']]);
                foreach ($questionData['options'] as $index => $label) {
                    Option::create([
                        'question_id' => $question->id,
                        'label' => $label,
                        'is_correct' => $index === 0,
                    ]);
                }
            }
        }
    }
}
