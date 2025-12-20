<?php

namespace App\Controller\Auth;
use App\Model\ProfileRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Auth')]
class FillProfileAction extends AbstractController
{
    public function __construct(private EntityManagerInterface $em) {}

    #[Route('/api/me/profile', name: 'me_profile', methods: ['PUT'])]
    public function __invoke(
        #[CurrentUser] \App\Entity\Teacher $teacher,
        #[MapRequestPayload] ProfileRequest $dto
    ): Response
    {
        $teacher->setFirstName($dto->firstName)
            ->setLastName($dto->lastName);
        $this->em->flush();
        return $this->json($teacher, 200, [], ['groups' => 'teacher:read']);
    }
}