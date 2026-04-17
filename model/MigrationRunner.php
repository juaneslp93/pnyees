<?php

require_once __DIR__ . '/Migration.php';
require_once __DIR__ . '/conexion.php';

class MigrationRunner
{
    private mysqli $db;
    private string $path;

    public function __construct()
    {
        $this->db   = Conexion::iniciar();
        $this->path = __DIR__ . '/migrations';
        $this->ensureTable();
    }

    public function run(): void
    {
        $pending = $this->pending();

        if (empty($pending)) {
            echo "Base de datos al día. No hay migraciones pendientes.\n";
            return;
        }

        foreach ($pending as $file) {
            $this->execute($file);
        }
    }

    public function rollback(int $steps = 1): void
    {
        $executed = $this->executed();
        $targets  = array_slice(array_reverse($executed), 0, $steps);

        if (empty($targets)) {
            echo "No hay migraciones ejecutadas para revertir.\n";
            return;
        }

        foreach ($targets as $name) {
            $this->revert($name);
        }
    }

    public function status(): void
    {
        $executed = $this->executed();
        $files    = $this->allFiles();

        if (empty($files)) {
            echo "No se encontraron archivos de migración en model/migrations/\n";
            return;
        }

        echo str_pad('Migración', 55) . "Estado\n";
        echo str_repeat('─', 70) . "\n";

        foreach ($files as $file) {
            $name   = basename($file, '.php');
            $status = in_array($name, $executed) ? '[  OK  ]' : '[PENDIENTE]';
            echo str_pad($name, 55) . $status . "\n";
        }
    }

    // ─── private ─────────────────────────────────────────────────

    private function ensureTable(): void
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `migrations` (
                `id`          INT AUTO_INCREMENT PRIMARY KEY,
                `migration`   VARCHAR(255) NOT NULL UNIQUE,
                `executed_at` DATETIME     NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }

    private function pending(): array
    {
        $executed = $this->executed();

        return array_filter($this->allFiles(), function (string $file) use ($executed): bool {
            return !in_array(basename($file, '.php'), $executed);
        });
    }

    private function executed(): array
    {
        $result = $this->db->query("SELECT migration FROM migrations ORDER BY id ASC");
        $list   = [];
        while ($row = $result->fetch_assoc()) {
            $list[] = $row['migration'];
        }
        return $list;
    }

    private function allFiles(): array
    {
        $files = glob($this->path . '/*.php') ?: [];
        sort($files);
        return $files;
    }

    private function execute(string $file): void
    {
        $name      = basename($file, '.php');
        $className = 'Migration_' . $name;

        require_once $file;

        /** @var Migration $migration */
        $migration = new $className();

        echo "Ejecutando: $name ... ";

        $this->db->begin_transaction();
        try {
            $migration->up($this->db);
            $stmt = $this->db->prepare("INSERT INTO migrations (migration, executed_at) VALUES (?, NOW())");
            $stmt->bind_param('s', $name);
            $stmt->execute();
            $this->db->commit();
            echo "OK\n";
        } catch (Throwable $e) {
            $this->db->rollback();
            echo "FALLO\n";
            echo "  → " . $e->getMessage() . "\n";
            exit(1);
        }
    }

    private function revert(string $name): void
    {
        $file = $this->path . "/$name.php";

        if (!file_exists($file)) {
            echo "Archivo no encontrado para rollback: $name\n";
            return;
        }

        $className = 'Migration_' . $name;
        require_once $file;

        /** @var Migration $migration */
        $migration = new $className();

        echo "Revirtiendo: $name ... ";

        $this->db->begin_transaction();
        try {
            $migration->down($this->db);
            $stmt = $this->db->prepare("DELETE FROM migrations WHERE migration = ?");
            $stmt->bind_param('s', $name);
            $stmt->execute();
            $this->db->commit();
            echo "OK\n";
        } catch (Throwable $e) {
            $this->db->rollback();
            echo "FALLO\n";
            echo "  → " . $e->getMessage() . "\n";
            exit(1);
        }
    }
}
