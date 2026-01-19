<?php

namespace App\Controller;

use App\Entity\Teacher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class MeController extends AbstractController
{
    #[Route('/api/me', name: 'me', methods: ['GET'])]
    public function __invoke(#[CurrentUser] ?Teacher $teacher): Response
    {
        dump('JWT user:', $this->getUser()?->getRoles(), 'Teacher:', $teacher);
        if (!$teacher) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        return $this->json($teacher, 200, [], ['groups' => 'teacher:read']);
    }
}

