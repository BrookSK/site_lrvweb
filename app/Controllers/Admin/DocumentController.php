<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Database;
use Core\Logger;
use Core\Request;
use Core\Response;

class DocumentController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        $this->requirePermission('documents.view');
        $db = Database::getInstance();
        $category = $request->query('category');

        $where = 'd.deleted_at IS NULL';
        $params = [];
        if ($category) { $where .= ' AND d.category = :cat'; $params['cat'] = $category; }

        $documents = $db->fetchAll("
            SELECT d.*, c.name as client_name, u.name as uploaded_by_name
            FROM documents d
            LEFT JOIN clients c ON d.client_id = c.id
            LEFT JOIN users u ON d.uploaded_by = u.id
            WHERE {$where} ORDER BY d.created_at DESC
        ", $params);

        return $this->adminView('documents/index', ['title' => 'Documentos', 'documents' => $documents, 'currentCategory' => $category]);
    }

    public function upload(Request $request, Response $response): void
    {
        $this->requirePermission('documents.manage');
        $file = $request->file('file');
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            $this->session->flash('error', 'Erro no upload.');
            $this->redirect('/admin/documentos');
            return;
        }

        $uploadDir = ROOT_PATH . '/storage/uploads/documents/' . date('Y/m') . '/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $ext;
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            Database::getInstance()->insert('documents', [
                'client_id' => $request->input('client_id') ?: null,
                'project_id' => $request->input('project_id') ?: null,
                'uploaded_by' => $this->getUser()['id'],
                'name' => $request->input('name') ?: $file['name'],
                'original_name' => $file['name'],
                'path' => 'storage/uploads/documents/' . date('Y/m') . '/' . $filename,
                'mime_type' => $file['type'],
                'size' => $file['size'],
                'category' => $request->input('category') ?? 'other',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            Logger::audit('Documento enviado', ['file' => $file['name']]);
            $this->session->flash('success', 'Documento enviado!');
        } else {
            $this->session->flash('error', 'Erro ao salvar arquivo.');
        }

        $this->redirect('/admin/documentos');
    }

    public function download(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('documents.view');
        $doc = Database::getInstance()->fetchOne("SELECT * FROM documents WHERE id = :id AND deleted_at IS NULL", ['id' => (int) $params['id']]);
        if (!$doc) { $this->response->error('Não encontrado', 404); return; }

        $filePath = ROOT_PATH . '/' . $doc['path'];
        $this->response->download($filePath, $doc['original_name']);
    }

    public function update(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('documents.manage');
        Database::getInstance()->update('documents', [
            'name' => $request->input('name'),
            'category' => $request->input('category'),
            'updated_at' => date('Y-m-d H:i:s'),
        ], 'id = :id', ['id' => (int) $params['id']]);
        $this->session->flash('success', 'Documento atualizado!');
        $this->redirect('/admin/documentos');
    }

    public function destroy(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('documents.manage');
        Database::getInstance()->update('documents', ['deleted_at' => date('Y-m-d H:i:s')], 'id = :id', ['id' => (int) $params['id']]);
        $this->session->flash('success', 'Documento excluído!');
        $this->redirect('/admin/documentos');
    }
}
