<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;

class LogController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        $this->requirePermission('logs.view');

        $logDir = ROOT_PATH . '/logs/';
        $files = [];
        if (is_dir($logDir)) {
            $allFiles = scandir($logDir, SCANDIR_SORT_DESCENDING);
            $files = array_filter($allFiles, fn($f) => str_ends_with($f, '.log'));
        }

        $selectedFile = $request->query('file') ?? ($files[0] ?? '');
        $content = '';
        if ($selectedFile && file_exists($logDir . $selectedFile)) {
            $lines = file($logDir . $selectedFile);
            $content = implode('', array_slice($lines, -200)); // últimas 200 linhas
        }

        return $this->adminView('system/logs', [
            'title' => 'Logs do Sistema',
            'files' => array_values($files),
            'selectedFile' => $selectedFile,
            'content' => $content,
        ]);
    }

    public function audit(Request $request, Response $response): string
    {
        $this->requirePermission('logs.view');
        $db = Database::getInstance();

        $page = max(1, (int) ($request->query('page') ?? 1));
        $perPage = 50;
        $offset = ($page - 1) * $perPage;

        $logs = $db->fetchAll("
            SELECT al.*, u.name as user_name
            FROM audit_logs al
            LEFT JOIN users u ON al.user_id = u.id
            ORDER BY al.created_at DESC
            LIMIT {$perPage} OFFSET {$offset}
        ");

        $total = $db->fetchOne("SELECT COUNT(*) as total FROM audit_logs");

        return $this->adminView('system/audit', [
            'title' => 'Log de Auditoria',
            'logs' => $logs,
            'currentPage' => $page,
            'totalPages' => (int) ceil(($total['total'] ?? 0) / $perPage),
        ]);
    }
}
