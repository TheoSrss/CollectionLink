<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    public function __construct(private MailerInterface $mailer) {}

    private static array $fromEmails = [
        'default' => 'hi@demomailtrap.com',
        'noreply' => 'noreply@collectionlink.com',
    ];

    private function sendEmail(string $to, string $subject, string $text, string $from = 'default'): void
    {
        if (!isset(self::$fromEmails[$from])) {
            throw new \InvalidArgumentException("Erreur envoie email");
        }

        $email = (new Email())
            ->from(self::$fromEmails[$from])
            ->to($to)
            ->subject($subject)
            ->text($text);

        $this->mailer->send($email);
    }

    public function sendValidationCode(string $email, string $verificationCode): void
    {
        $this->sendEmail(
            $email,
            'Bienvenue sur CollectionLink! Votre code de validation',
            "Bonjour,\n\nVotre code de validation est: $verificationCode\n\nBienvenue sur CollectionLink!"
        );
    }
}
