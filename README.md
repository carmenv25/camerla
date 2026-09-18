# AulaAdaptativa — Prototipo de plataforma web de aprendizaje adaptativo

Prototipo académico construido con **Laravel 12 + PHP 8.2+ + MySQL + HTML5 + CSS3 + JavaScript + Bootstrap + VS Code + Git/GitHub**.

El flujo implementado sigue el diagrama proporcionado:

**Inicio → Iniciar sesión → Validar usuario → Dashboard → Seleccionar módulo → Realizar evaluación → Analizar resultado → ¿Aprobó? → Siguiente módulo / Contenido de refuerzo → Actualizar ruta → Retroalimentación → Cerrar sesión → Fin.**

## Funcionalidades

- Inicio de sesión con sesión PHP/Laravel.
- Dashboard del estudiante.
- Ruta de aprendizaje por módulos.
- Primer módulo disponible y siguientes módulos desbloqueados al aprobar.
- Evaluación de opción múltiple.
- Cálculo automático de puntuación.
- Aprobación con **70% o más**.
- Si aprueba: se habilita el siguiente módulo.
- Si no aprueba: se activa contenido de refuerzo.
- Registro de intentos y respuestas en MySQL.
- Persistencia del progreso del estudiante.
- Interfaz responsive con Bootstrap.
- JavaScript para mejorar la interacción de las respuestas.

## Cuenta demo

- Correo: `estudiante@demo.com`
- Contraseña: `password`

## Requisitos

- PHP 8.2.0 o superior (compatible con PHP 8.2.12).
- Composer.
- MySQL 5.7+ / 8.x o MariaDB compatible.
- Node.js y npm (recomendado si vas a trabajar con Vite).
- Visual Studio Code.
- Git.

Laravel 12 requiere PHP 8.2 como mínimo, por lo que es compatible con PHP 8.2.12.

## Instalación en Windows con XAMPP/WAMP/MySQL

### 1. Crear la base de datos

En MySQL Workbench, phpMyAdmin o consola MySQL ejecuta:

```sql
CREATE DATABASE aula_adaptativa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. Abrir el proyecto en VS Code

Descomprime el ZIP y abre la carpeta `plataforma_adaptativa`.

En VS Code: **File → Open Folder**.

### 3. Instalar dependencias PHP

Abre la terminal integrada y ejecuta:

```bash
composer install
```

### 4. Crear `.env`

Copia `.env.example` y renómbralo como `.env`.

PowerShell:

```powershell
Copy-Item .env.example .env
```

CMD:

```cmd
copy .env.example .env
```

Configura estas variables según tu MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aula_adaptativa
DB_USERNAME=root
DB_PASSWORD=
```

Si tu usuario root tiene contraseña, colócala en `DB_PASSWORD`.

### 5. Generar la clave de Laravel

```bash
php artisan key:generate
```

### 6. Crear tablas y datos de demostración

```bash
php artisan migrate --seed
```

Esto crea las tablas y carga:

- 1 estudiante demo.
- 3 módulos.
- 9 preguntas.
- Opciones de respuesta.

### 7. Ejecutar Laravel

```bash
php artisan serve
```

Abre:

`http://127.0.0.1:8000`

### 8. Probar

Usa:

```text
Correo: estudiante@demo.com
Contraseña: password
```

## Flujo para probar la adaptación

### Caso A — Aprobar

1. Inicia sesión.
2. Abre el Módulo 1.
3. Realiza la evaluación.
4. Selecciona las respuestas correctas.
5. Obtén 70% o más.
6. Verás que el sistema habilita el siguiente módulo.

### Caso B — Reprobar

1. Abre un módulo disponible.
2. Selecciona varias respuestas incorrectas.
3. Obtén menos de 70%.
4. El sistema mostrará **Contenido de refuerzo**.
5. Estudia el refuerzo.
6. Pulsa **Volver a evaluar**.

## Estructura principal

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── EvaluationController.php
│   │   ├── LearningController.php
│   │   └── ModuleController.php
│   └── Middleware/
│       └── EnsureAuthenticated.php
├── Models/
│   ├── Attempt.php
│   ├── AttemptAnswer.php
│   ├── LearningProgress.php
│   ├── Module.php
│   ├── Option.php
│   ├── Question.php
│   └── User.php
├── bootstrap/
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   ├── css/app.css
│   └── js/app.js
├── resources/views/
│   ├── auth/
│   ├── dashboard/
│   ├── evaluation/
│   ├── learning/
│   ├── modules/
│   └── layouts/
└── routes/
    └── web.php
```

## Relación con el diagrama

| Diagrama | Implementación |
|---|---|
| Inicio | `/` redirige al login |
| Iniciar Sesión | `AuthController` |
| ¿Usuario válido? | Validación de correo + contraseña |
| Mostrar Dashboard | `DashboardController` |
| Seleccionar Módulo | `ModuleController` |
| Realizar Evaluación | `EvaluationController@show` |
| Analizar Resultado | `EvaluationController@submit` |
| ¿Aprobó? | Regla de 70% |
| Siguiente Módulo | `LearningController@next` |
| Contenido de Refuerzo | `LearningController@reinforcement` |
| Actualizar Ruta | `LearningProgress` |
| Mostrar Retroalimentación | Vista `evaluation/result.blade.php` |
| Cerrar Sesión | `AuthController@logout` |

## Base de datos

```text
users
  1 ──────── N attempts
  1 ──────── N learning_progress

modules
  1 ──────── N questions
  1 ──────── N attempts
  1 ──────── N learning_progress

questions
  1 ──────── N options
  1 ──────── N attempt_answers

attempts
  1 ──────── N attempt_answers
```

## Git y GitHub

Inicializa Git dentro de la carpeta:

```bash
git init
git add .
git commit -m "Prototipo inicial de plataforma de aprendizaje adaptativo"
```

Después crea un repositorio vacío en GitHub y conecta el remoto:

```bash
git branch -M main
git remote add origin https://github.com/TU-USUARIO/aula-adaptativa.git
git push -u origin main
```

**No subas `.env`**. Ya está incluido en `.gitignore`.

## Comandos útiles

```bash
php artisan migrate
php artisan migrate:fresh --seed
php artisan route:list
php artisan test
php artisan serve
```

Para volver a cargar completamente los datos demo:

```bash
php artisan migrate:fresh --seed
```

## Nota académica

Este proyecto es un **prototipo funcional**, no un LMS listo para producción. Para una versión de producción se recomienda incorporar autenticación completa con recuperación de contraseña, roles/permisos, CSRF ya integrado por Laravel, políticas de autorización, historial detallado de aprendizaje, banco de preguntas, administración de contenidos, analítica y pruebas más extensas.
