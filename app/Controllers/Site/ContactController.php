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

            // Destinatários (múltiplos separados por vírgula)
            $recipients = \Core\Config::setting('mail.recipients') ?: ($mailConfig['from_address'] ?: $mailConfig['username']);
            foreach (explode(',', $recipients) as $recipient) {
                $recipient = trim($recipient);
                if (!empty($recipient)) $mail->addAddress($recipient);
            }

            $mail->addReplyTo($data['email'], $data['name']);

            $mail->isHTML(true);
            $mail->Subject = '[LRV Web] ' . $data['subject'];
            $mail->Body = '
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;font-family:Arial,sans-serif;background-color:#f4f4f7;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f7;padding:40px 0;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.05);">

<!-- Header -->
<tr><td style="background:linear-gradient(135deg,#2d1b69,#6d28d9);padding:30px 40px;text-align:center;">
<h1 style="color:#ffffff;margin:0;font-size:22px;">Nova Mensagem do Site</h1>
<p style="color:#c4b5fd;margin:8px 0 0;font-size:13px;">Formulário de Contato — lrvweb.com.br</p>
</td></tr>

<!-- Body -->
<tr><td style="padding:30px 40px;">

<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:20px;">
<tr>
<td style="padding:12px 16px;background:#f8f7ff;border-radius:8px;border-left:4px solid #7c3aed;margin-bottom:8px;">
<p style="margin:0;font-size:11px;color:#6b7280;text-transform:uppercase;letter-spacing:1px;">Nome</p>
<p style="margin:4px 0 0;font-size:15px;color:#1f2937;font-weight:600;">' . htmlspecialchars($data['name']) . '</p>
</td>
</tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:12px;">
<tr>
<td width="50%" style="padding:10px 0;"><span style="font-size:12px;color:#6b7280;">E-mail:</span><br><a href="mailto:' . htmlspecialchars($data['email']) . '" style="color:#7c3aed;font-size:14px;text-decoration:none;">' . htmlspecialchars($data['email']) . '</a></td>
<td width="50%" style="padding:10px 0;"><span style="font-size:12px;color:#6b7280;">Telefone:</span><br><span style="color:#1f2937;font-size:14px;">' . htmlspecialchars($data['phone'] ?? '—') . '</span></td>
</tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:20px;">
<tr><td style="padding:10px 0;border-top:1px solid #e5e7eb;">
<span style="font-size:12px;color:#6b7280;">Assunto:</span><br>
<span style="color:#1f2937;font-size:14px;font-weight:600;">' . htmlspecialchars($data['subject']) . '</span>
</td></tr>
</table>

<div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:20px;margin-bottom:20px;">
<p style="margin:0 0 8px;font-size:12px;color:#6b7280;text-transform:uppercase;letter-spacing:1px;">Mensagem</p>
<p style="margin:0;font-size:14px;color:#374151;line-height:1.7;">' . nl2br(htmlspecialchars($data['message'])) . '</p>
</div>

<p style="font-size:12px;color:#9ca3af;text-align:center;margin:20px 0 0;">
Para responder, basta clicar em "Responder" — irá direto para ' . htmlspecialchars($data['email']) . '
</p>

</td></tr>

<!-- Footer -->
<tr><td style="background:#f8f7ff;padding:20px 40px;text-align:center;border-top:1px solid #e5e7eb;">
<p style="margin:0;font-size:11px;color:#9ca3af;">Enviado pelo formulário de contato em ' . date('d/m/Y \à\s H:i') . '</p>
<p style="margin:4px 0 0;font-size:11px;color:#9ca3af;">LRV Web — lrvweb.com.br</p>
</td></tr>

</table>
</td></tr>
</table>
</body>
</html>';

            $mail->AltBody = "Nova mensagem do site\n\nNome: {$data['name']}\nE-mail: {$data['email']}\nTelefone: " . ($data['phone'] ?? '—') . "\nAssunto: {$data['subject']}\n\nMensagem:\n{$data['message']}\n\nEnviado em " . date('d/m/Y H:i');

            $mail->send();
            Logger::info('E-mail de contato enviado', ['from' => $data['email'], 'subject' => $data['subject']]);
        } catch (\Throwable $e) {
            Logger::error('Falha ao enviar e-mail de contato', ['error' => $e->getMessage()]);
            // Não bloqueia o usuário — só loga o erro
        }

        $this->session->flash('success', \Core\I18n::get('message_sent'));
        $this->redirect('/' . \Core\I18n::getLocale() . '/contato');
    }

    /**
     * Cadastro na newsletter
     */
    public function newsletter(Request $request, Response $response): void
    {
        $email = trim($request->input('email') ?? '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ($request->isAjax()) {
                $this->response->error('E-mail inválido', 422);
            } else {
                $this->session->flash('error', 'E-mail inválido.');
                $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
            }
            return;
        }

        $db = \Core\Database::getInstance();

        // Verifica se já existe
        $existing = $db->fetchOne("SELECT id FROM newsletter_subscribers WHERE email = :email", ['email' => $email]);

        if (!$existing) {
            $db->insert('newsletter_subscribers', [
                'email' => $email,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($request->isAjax()) {
            $this->response->success(null, 'Cadastrado com sucesso!');
        } else {
            $this->session->flash('success', 'E-mail cadastrado!');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }
    }
}
