<?php

namespace App\Controller;


use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use App\Util\ValidationErrors;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class IndexController extends AbstractController
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    #[Route('/pps', name: 'app_index')]
    public function index(): JsonResponse
    {
        $email = (new Email())
            ->from('kydyrmysheva2005@gmai.com')
            ->to('tresunionfun@gmail.com')
            ->subject('Новый вход на сайт')
            ->text('Пользователь зашел на сайт.');
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface) {
            $this->json(["ERROR"]);
        }
        return $this->json(['HELLO']);
    }

    #[Route('/pps/awards', name: 'app_awards', methods: ['POST'])]
    public function awards_add(): JsonResponse
    {
        return $this->json(['HELLO']);
    }

}
