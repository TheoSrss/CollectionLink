<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\User;
use Twig\Environment;

class MailerService
{
    public function __construct(
        private MailerInterface $mailer,
        private Environment $twig
    ) {}

    private static array $fromEmails = [
        'default' => 'hi@demomailtrap.com',
        'noreply' => 'noreply@collectionlink.com',
    ];

    private function sendEmail(string $to, string $subject, $templatePath, $templateParams, string $from = 'default'): void
    {
        if (!isset(self::$fromEmails[$from])) {
            throw new \InvalidArgumentException("Erreur envoie email");
        }

        $mjmlContent = $this->twig->render('email/' . $templatePath, $templateParams);
        $htmlContent = $this->twig->createTemplate('{{ mjmlContent|mjml }}')->render(['mjmlContent' => $mjmlContent]);


        $email = (new Email())
            ->from(self::$fromEmails[$from])
            ->to($to)
            ->subject($subject)
            ->html($htmlContent);

        $this->mailer->send($email);
    }

    public function sendValidationCode(User $user, string $verificationCode): void
    {
        $this->sendEmail(
            $user->getEmail(),
            'Bienvenue sur CollectionLink! Votre code de validation',
            'welcome.mjml.twig',
            ['code' => $verificationCode, "username" => $user->getUsername()]
        );
    }

    public function sendPasswordResetEmail(User $user, int $code): void
    {
        $this->sendEmail(
            $user->getEmail(),
            'Réinitialisation de votre mot de passe',
            'resetPassword.mjml.twig',
            ['code' => $code]
        );
    }
}
