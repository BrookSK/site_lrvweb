<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;

class NotificationController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        $this->requireAuth();
        $userId = $this->getUser()['id'];

        $notifications = Database::getInstance()->fetchAll(
            "SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 50",
            ['user_id' => $userId]
        );

        return $this->adminView('notifications/index', ['title' => 'Notificações', 'notifications' => $notifications]);
    }

    public function markAsRead(Request $request, Response $response, array $params): void
    {
        $this->requireAuth();
        Database::getInstance()->update('notifications', ['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')], 'id = :id AND user_id = :uid', ['id' => (int) $params['id'], 'uid' => $this->getUser()['id']]);
        $this->response->success(null, 'Marcada como lida');
    }

    public function markAllAsRead(Request $request, Response $response): void
    {
        $this->requireAuth();
        Database::getInstance()->update('notifications', ['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')], 'user_id = :uid AND is_read = 0', ['uid' => $this->getUser()['id']]);
        $this->session->flash('success', 'Todas marcadas como lidas!');
        $this->redirect('/admin/notificacoes');
    }
}
