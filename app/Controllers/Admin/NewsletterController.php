<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;

class NewsletterController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        $this->requirePermission('settings.view');
        $subscribers = Database::getInstance()->fetchAll("SELECT * FROM newsletter_subscribers ORDER BY created_at DESC");
        return $this->adminView('newsletter/index', ['title' => 'Newsletter', 'subscribers' => $subscribers]);
    }

    public function destroy(Request $request, Response $response, array $params): void
    {
        $this->requirePermission('settings.manage');
        Database::getInstance()->delete('newsletter_subscribers', 'id = :id', ['id' => (int) $params['id']]);
        $this->session->flash('success', 'E-mail removido!');
        $this->redirect('/admin/newsletter');
    }
}
