<?php

namespace App\Controller\Auth;

use App\Model\VerifyRequest;
use App\Repository\TeacherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Auth')]
class VerifyCodeAction extends AbstractController
{
    public function __construct(
        private TeacherRepository           $repo,
        private EntityManagerInterface      $em,
        private UserPasswordHasherInterface $hasher
    )
    {
    }

    #[Route('/api/auth/verify', name: 'auth_verify', methods: ['POST'])]
    public function __invoke(
        #[MapRequestPayload] VerifyRequest $dto
    ): Response
    {
        $teacher = $this->repo->findOneBy(['email' => $dto->email]);
        $now = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        if ($teacher->getCodeExpiredAt() < $now)
        {
            if (!$teacher ||
                $teacher->getSignupCode() !== $dto->code ||
                $teacher->isCodeExpired()) {
                return $this->json(['error' => 'Invalid or expired code'], 422);
            }

            $teacher->setPassword($this->hasher->hashPassword($teacher, $dto->password))
                ->setEnabled(true)
                ->setSignupCode(null)
                ->setCodeExpiredAt(null);

            $this->em->flush();

            return $this->json(['message' => 'Password created, you may login']);
        }
        return $this->json(['error' => 'Invalid code'], 422);
    }
}