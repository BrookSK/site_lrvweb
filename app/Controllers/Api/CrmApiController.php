<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;

class CrmApiController extends Controller
{
    public function index(Request $request, Response $response): void
    {
        $this->requireAuth();
        $leads = Database::getInstance()->fetchAll("SELECT l.*, u.name as responsible_name FROM crm_leads l LEFT JOIN users u ON l.responsible_id = u.id WHERE l.deleted_at IS NULL ORDER BY l.updated_at DESC");
        $this->response->success($leads);
    }

    public function updateStage(Request $request, Response $response, array $params): void
    {
        $this->requireAuth();
        $id = (int) $params['id'];
        $stage = $request->input('stage');

        Database::getInstance()->update('crm_leads', ['stage' => $stage, 'updated_at' => date('Y-m-d H:i:s')], 'id = :id', ['id' => $id]);
        $this->response->success(null, 'Stage atualizado');
    }
}
