<?php

namespace App\Service;

use App\Entity\User;
use Closure;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class Mailer
{
    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    public function sendWelcomeMail(User $user, string $password): void
    {
        $this->send(
            'email/welcome.html.twig',
            'Добро пожаловать на Товарный агрегатор Megano',
            $user,
            $password
        );
    }

    public function sendNewPassword(User $user, string $password): void
    {
        $this->send(
            'email/newpassword.html.twig',
            'Новые данные для входа в Товарный агрегатор Megano',
            $user,
            $password
        );
    }

    private function send(string $template, string $subject, User $user, string $password): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address('noreply@symfony.skillbox', 'Товарный агрегатор Megano'))
            ->to(new Address($user->getEmail(), $user->getPhone()))
            ->htmlTemplate($template)
            ->subject($subject)
            ->context([
                'user' => $user,
                'password' => $password
            ]);

        $this->mailer->send($email);
    }
}
