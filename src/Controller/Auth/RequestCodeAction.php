<?php

namespace App\Controller\Auth;
use App\Model\EmailRequest;
use App\Service\SignupCodeMailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Auth')]
class RequestCodeAction extends AbstractController
{
    public function __construct(private SignupCodeMailer $mailer) {}

    #[Route('/api/auth/code', name: 'auth_request_code', methods: ['POST'])]
    public function __invoke(
        #[MapRequestPayload] EmailRequest $dto
    ): Response
    {
        $this->mailer->sendCode($dto->email);
        return $this->json(['message' => 'Code sent']);
    }
}