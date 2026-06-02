<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Config;
use Core\Controller;
use Core\Database;
use Core\Logger;
use Core\Request;
use Core\Response;

class BackupController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        $this->requirePermission('backups.manage');
        $backups = Database::getInstance()->fetchAll("
            SELECT b.*, u.name as created_by_name
            FROM backups b
            LEFT JOIN users u ON b.created_by = u.id
            ORDER BY b.created_at DESC
        ");
        return $this->adminView('system/backups', ['title' => 'Backups', 'backups' => $backups]);
    }

    public function create(Request $request, Response $response): void
    {
        $this->requirePermission('backups.manage');

        $dbConfig = Config::get('database');
        $backupDir = ROOT_PATH . '/' . Config::get('backup.path', 'storage/backups') . '/';
        if (!is_dir($backupDir)) mkdir($backupDir, 0755, true);

        $filename = 'backup_' . date('Ymd_His') . '.sql';
        $filepath = $backupDir . $filename;

        $command = sprintf(
            'mysqldump --host=%s --port=%d --user=%s --password=%s %s > %s 2>&1',
            escapeshellarg($dbConfig['host']),
            $dbConfig['port'],
            escapeshellarg($dbConfig['username']),
            escapeshellarg($dbConfig['password']),
            escapeshellarg($dbConfig['database']),
            escapeshellarg($filepath)
        );

        exec($command, $output, $returnCode);

        if ($returnCode === 0 && file_exists($filepath)) {
            Database::getInstance()->insert('backups', [
                'filename' => $filename,
                'path' => Config::get('backup.path') . '/' . $filename,
                'size' => filesize($filepath),
                'type' => 'manual',
                'status' => 'completed',
                'created_by' => $this->getUser()['id'],
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            Logger::audit('Backup criado', ['filename' => $filename]);
            $this->session->flash('success', "Backup criado: {$filename}");
        } else {
            Logger::error('Falha ao criar backup', ['output' => implode("\n", $output)]);
            $this->session->flash('error', 'Erro ao criar backup. Verifique os logs.');
        }

        $this->redirect('/admin/backups');
    }

    public function download(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('backups.manage');
        $backup = Database::getInstance()->fetchOne("SELECT * FROM backups WHERE id = :id", ['id' => (int) $params['id']]);
        if (!$backup) { $this->response->error('Backup não encontrado', 404); return; }

        $filepath = ROOT_PATH . '/' . $backup['path'];
        $this->response->download($filepath, $backup['filename']);
    }

    public function restore(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('backups.manage');
        $backup = Database::getInstance()->fetchOne("SELECT * FROM backups WHERE id = :id", ['id' => (int) $params['id']]);
        if (!$backup) { $this->session->flash('error', 'Backup não encontrado'); $this->redirect('/admin/backups'); return; }

        $dbConfig = Config::get('database');
        $filepath = ROOT_PATH . '/' . $backup['path'];

        if (!file_exists($filepath)) {
            $this->session->flash('error', 'Arquivo de backup não encontrado.');
            $this->redirect('/admin/backups');
            return;
        }

        $command = sprintf(
            'mysql --host=%s --port=%d --user=%s --password=%s %s < %s 2>&1',
            escapeshellarg($dbConfig['host']),
            $dbConfig['port'],
            escapeshellarg($dbConfig['username']),
            escapeshellarg($dbConfig['password']),
            escapeshellarg($dbConfig['database']),
            escapeshellarg($filepath)
        );

        exec($command, $output, $returnCode);

        if ($returnCode === 0) {
            Logger::audit('Backup restaurado', ['filename' => $backup['filename']]);
            $this->session->flash('success', 'Backup restaurado com sucesso!');
        } else {
            $this->session->flash('error', 'Erro ao restaurar backup.');
        }

        $this->redirect('/admin/backups');
    }

    public function destroy(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('backups.manage');
        $id = (int) $params['id'];
        $backup = Database::getInstance()->fetchOne("SELECT * FROM backups WHERE id = :id", ['id' => $id]);

        if ($backup) {
            $filepath = ROOT_PATH . '/' . $backup['path'];
            if (file_exists($filepath)) unlink($filepath);
            Database::getInstance()->delete('backups', 'id = :id', ['id' => $id]);
        }

        $this->session->flash('success', 'Backup excluído!');
        $this->redirect('/admin/backups');
    }
}
