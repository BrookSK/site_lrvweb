<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Database;
use Core\Logger;
use Core\Request;
use Core\Response;

class VersionController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        $this->requirePermission('versions.view');
        $versions = Database::getInstance()->fetchAll("
            SELECT v.*, u.name as responsible_name
            FROM system_versions v
            LEFT JOIN users u ON v.responsible_id = u.id
            ORDER BY v.date DESC, v.id DESC
        ");
        return $this->adminView('system/versions', ['title' => 'Versionamento', 'versions' => $versions]);
    }

    public function store(Request $request, Response $response): void
    {
        $this->requirePermission('versions.view');
        $data = $this->validate(['version' => 'required|max:20', 'description' => 'required']);

        Database::getInstance()->insert('system_versions', [
            'version' => $data['version'],
            'date' => $request->input('date') ?? date('Y-m-d'),
            'responsible_id' => $this->getUser()['id'],
            'type' => $request->input('type') ?? 'minor',
            'description' => $data['description'],
            'modules_affected' => $request->input('modules_affected'),
            'status' => $request->input('status') ?? 'completed',
            'changelog' => $request->input('changelog'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        Logger::audit('Versão registrada', ['version' => $data['version']]);
        $this->session->flash('success', 'Versão registrada!');
        $this->redirect('/admin/versoes');
    }

    public function changelog(Request $request, Response $response): string
    {
        $this->requirePermission('versions.view');
        $versions = Database::getInstance()->fetchAll("SELECT * FROM system_versions WHERE status = 'completed' ORDER BY date DESC");
        return $this->adminView('system/changelog', ['title' => 'Changelog', 'versions' => $versions]);
    }

    public function exportChangelog(Request $request, Response $response): void
    {
        $this->requirePermission('versions.view');
        $versions = Database::getInstance()->fetchAll("SELECT * FROM system_versions WHERE status = 'completed' ORDER BY date DESC");

        $md = "# Changelog\n\n";
        foreach ($versions as $v) {
            $md .= "## [{$v['version']}] - {$v['date']}\n\n";
            $md .= $v['description'] . "\n";
            if ($v['changelog']) $md .= "\n{$v['changelog']}\n";
            if ($v['modules_affected']) $md .= "\n**Módulos:** {$v['modules_affected']}\n";
            $md .= "\n---\n\n";
        }

        $response->setHeader('Content-Type', 'text/markdown');
        $response->setHeader('Content-Disposition', 'attachment; filename="CHANGELOG.md"');
        $response->setBody($md);
        $response->send();
    }
}
