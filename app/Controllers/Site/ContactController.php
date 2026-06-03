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

        // Envia e-mail via PHPMailer
        try {
            $mailConfig = \Core\Config::get('mail');
            // Tenta pegar do banco se o arquivo não tem
            if (empty($mailConfig['host'])) {
                $mailConfig = [
                    'host' => \Core\Config::setting('mail.host') ?: 'smtp.gmail.com',
                    'port' => (int) (\Core\Config::setting('mail.port') ?: 587),
                    'username' => \Core\Config::setting('mail.username') ?: '',
                    'password' => \Core\Config::setting('mail.password') ?: '',
                    'encryption' => \Core\Config::setting('mail.encryption') ?: 'tls',
                    'from_address' => \Core\Config::setting('mail.from_address') ?: '',
                    'from_name' => \Core\Config::setting('mail.from_name') ?: 'LRV Web',
                ];
            }

            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $mailConfig['host'];
            $mail->Port = (int) $mailConfig['port'];
            $mail->SMTPAuth = true;
            $mail->Username = $mailConfig['username'];
            $mail->Password = $mailConfig['password'];
            $mail->CharSet = 'UTF-8';

            // Criptografia
            $encryption = $mailConfig['encryption'] ?? '';
            if ($encryption === 'tls') {
                $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            } elseif ($encryption === 'ssl') {
                $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $mail->SMTPSecure = '';
                $mail->SMTPAutoTLS = false;
            }

            // Se for localhost, desabilita verificação de certificado
            if (in_array($mailConfig['host'], ['localhost', '127.0.0.1'])) {
                $mail->SMTPOptions = [
                    'ssl' => ['verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true]
                ];
                $mail->SMTPAuth = false;
            }

            $mail->setFrom($mailConfig['from_address'] ?: $mailConfig['username'], $mailConfig['from_name'] ?: 'LRV Web');
            $mail->addAddress($mailConfig['from_address'] ?: $mailConfig['username']); // Envia para si mesmo
            $mail->addReplyTo($data['email'], $data['name']);

            $mail->isHTML(true);
            $mail->Subject = '[Contato Site] ' . $data['subject'];
            $mail->Body = "
                <h2>Nova mensagem do site</h2>
                <p><strong>Nome:</strong> {$data['name']}</p>
                <p><strong>E-mail:</strong> {$data['email']}</p>
                <p><strong>Telefone:</strong> " . ($data['phone'] ?? '—') . "</p>
                <p><strong>Assunto:</strong> {$data['subject']}</p>
                <hr>
                <p><strong>Mensagem:</strong></p>
                <p>" . nl2br(htmlspecialchars($data['message'])) . "</p>
                <hr>
                <p style='color:#888;font-size:12px;'>Enviado pelo formulário de contato em " . date('d/m/Y H:i') . "</p>
            ";
            $mail->AltBody = "Nome: {$data['name']}\nE-mail: {$data['email']}\nTelefone: " . ($data['phone'] ?? '—') . "\nAssunto: {$data['subject']}\n\nMensagem:\n{$data['message']}";

            $mail->send();
            Logger::info('E-mail de contato enviado', ['from' => $data['email'], 'subject' => $data['subject']]);
        } catch (\Throwable $e) {
            Logger::error('Falha ao enviar e-mail de contato', ['error' => $e->getMessage()]);
            // Não bloqueia o usuário — só loga o erro
        }

        $this->session->flash('success', \Core\I18n::get('message_sent'));
        $this->redirect('/' . \Core\I18n::getLocale() . '/contato');
    }
}
