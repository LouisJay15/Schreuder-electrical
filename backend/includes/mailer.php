<?php
declare(strict_types=1);

/**
 * Minimal transactional mailer using PHP's mail() (works out of the box on
 * Xneelo shared hosting via the local MTA). Swap the body of aloe_send_mail()
 * for an SMTP library (e.g. PHPMailer) if you need better deliverability —
 * the call sites in api/ don't need to change.
 */
function aloe_send_mail(string $toEmail, string $toName, string $subject, string $bodyText): bool
{
    $config = require __DIR__ . '/../config.php';
    $from = $config['mail']['from'];
    $fromName = $config['mail']['from_name'];

    $headers = [];
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-Type: text/plain; charset=UTF-8';
    $headers[] = sprintf('From: %s <%s>', mb_encode_mimeheader($fromName), $from);
    $headers[] = 'X-Mailer: AloeCredit';

    $encodedSubject = mb_encode_mimeheader($subject, 'UTF-8');
    $to = sprintf('%s <%s>', mb_encode_mimeheader($toName), $toEmail);

    return @mail($to, $encodedSubject, $bodyText, implode("\r\n", $headers));
}

function aloe_send_otp_email(string $toEmail, string $toName, string $code): bool
{
    $subject = 'Your Aloe Credit verification code';
    $body = "Hi {$toName},\n\n"
        . "Your Aloe Credit sign-in code is: {$code}\n\n"
        . "This code expires in 10 minutes and can only be used once. "
        . "If you didn't try to sign in, you can ignore this email — your account is safe.\n\n"
        . "— Aloe Credit";
    return aloe_send_mail($toEmail, $toName, $subject, $body);
}

function aloe_send_reset_email(string $toEmail, string $toName, string $resetUrl): bool
{
    $subject = 'Reset your Aloe Credit password';
    $body = "Hi {$toName},\n\n"
        . "We received a request to reset your Aloe Credit password. Click the link below to choose a new one:\n\n"
        . "{$resetUrl}\n\n"
        . "This link expires in 30 minutes and can only be used once. "
        . "If you didn't request this, you can ignore this email — your password won't change.\n\n"
        . "— Aloe Credit";
    return aloe_send_mail($toEmail, $toName, $subject, $body);
}
