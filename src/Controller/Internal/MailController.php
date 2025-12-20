<?php

namespace App\Controller\Internal;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class MailController
{
    #[Route('/api/internal/send-mail', name: 'internal_mail', methods: ['POST'])]
    public function sendMail(Request $req, MailerInterface $mailer): Response
    {
        $d = json_decode($req->getContent(), true);
        $email = (new Email())
            ->from($_ENV['MAILER_FROM'])
            ->to($d['to'])
            ->subject($d['subject'])
            ->text($d['text']);
        $mailer->send($email);
        return new Response(null, 204);
    }

}