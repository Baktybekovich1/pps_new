<?php

namespace App\Controller;

use App\Entity\Teacher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class MeController extends AbstractController
{

    #[Route('/api/me')]
    public function apiMe(Security $security): Response
    {
        // Всегда через Security сервис
        $user = $security->getUser();
        dump($security);
        dump($user);
        return $this->json($user);
    }

//    #[Route('/api/me', name: 'me', methods: ['GET'])]
//    public function __invoke(#[CurrentUser] ?Teacher $teacher,Request $request): Response
//    {
//        dump(
//            'Authorization header:', $request->headers->get('Authorization'),
//
//            'Teacher:', $teacher,
//            'JWT roles:', $this->getUser()?->getRoles()
//        );
//
//        if (!$teacher) {
//            return $this->json(['error' => 'Unauthorized'], 401);
//        }
//
//        return $this->json($teacher, 200, [], ['groups' => 'teacher:read']);
//    }
}

