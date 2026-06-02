<?php

declare(strict_types=1);

namespace App\Controllers\Site;

use Core\Controller;
use Core\Logger;
use Core\Request;
use Core\Response;

class ContactController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        return $this->view('site/contact', [
            'title' => 'Contato - LRV Web',
            'meta_description' => 'Entre em contato com a LRV Web. Solicite um orçamento.',
        ], 'site');
    }

    public function send(Request $request, Response $response): void
    {
        $data = $this->validate([
            'name' => 'required|max:150',
            'email' => 'required|email',
            'phone' => 'phone',
            'subject' => 'required|max:200',
            'message' => 'required|max:2000',
        ]);

        // TODO: Enviar e-mail via PHPMailer
        Logger::info('Formulário de contato recebido', $data);

        $this->session->flash('success', \Core\I18n::get('message_sent'));
        $this->redirect('/' . \Core\I18n::getLocale() . '/contato');
    }
}
