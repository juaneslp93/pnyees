<?php

/**
 * CLI de migraciones de base de datos
 *
 * Uso:
 *   php migrate.php              → ejecuta migraciones pendientes
 *   php migrate.php run          → igual que el anterior
 *   php migrate.php rollback     → revierte la última migración
 *   php migrate.php rollback 3   → revierte las últimas 3 migraciones
 *   php migrate.php status       → muestra el estado de todas las migraciones
 *   php migrate.php make nombre  → crea un archivo de migración en blanco
 */

if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit('Acceso solo por consola.');
}

// Variables de servidor requeridas por setup.php
$_SERVER['REQUEST_SCHEME'] = 'http';
$_SERVER['HTTP_HOST']      = 'localhost';

require_once __DIR__ . '/model/MigrationRunner.php';

$command = $argv[1] ?? 'run';
$arg2    = $argv[2] ?? null;

$runner = new MigrationRunner();

switch ($command) {
    case 'run':
        $runner->run();
        break;

    case 'rollback':
        $steps = max(1, (int)($arg2 ?? 1));
        $runner->rollback($steps);
        break;

    case 'status':
        $runner->status();
        break;

    case 'make':
        if (!$arg2) {
            echo "Uso: php migrate.php make <nombre_descripcion>\n";
            exit(1);
        }
        makeMigration($arg2);
        break;

    default:
        echo "Comando desconocido: $command\n\n";
        echo "Comandos disponibles:\n";
        echo "  run              Ejecuta migraciones pendientes\n";
        echo "  rollback [n]     Revierte las últimas n migraciones (defecto: 1)\n";
        echo "  status           Muestra el estado de todas las migraciones\n";
        echo "  make <nombre>    Crea un nuevo archivo de migración en blanco\n";
        exit(1);
}

// ─── helpers ────────────────────────────────────────────────────────────────

function makeMigration(string $name): void
{
    $dir      = __DIR__ . '/model/migrations';
    $existing = glob($dir . '/*.php') ?: [];
    $next     = str_pad(count($existing) + 1, 3, '0', STR_PAD_LEFT);
    $slug     = preg_replace('/[^a-z0-9]+/', '_', strtolower($name));
    $filename = "{$next}_{$slug}.php";
    $class    = "Migration_{$next}_{$slug}";
    $path     = "$dir/$filename";

    $stub = <<<PHP
    <?php

    class $class extends Migration
    {
        public function up(mysqli \$db): void
        {
            // ALTER TABLE o CREATE TABLE aquí
        }

        public function down(mysqli \$db): void
        {
            // Revertir los cambios de up() aquí
        }
    }
    PHP;

    file_put_contents($path, $stub);
    echo "Migración creada: model/migrations/$filename\n";
}
