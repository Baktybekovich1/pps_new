<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PostLoginController extends AbstractController
{
//    #[Route('/choose-role', name: 'choose_role')]
//    #[IsGranted('ROLE_USER')]   // любой авторизованный
//    public function chooseRole(): Response
//    {
//        return $this->render('security/choose_role.html.twig', [
//            'roles' => $this->getUser()->getRoles(),
//        ]);
//    }
    #[Route('/choose-role', name: 'choose_role')]
    #[IsGranted('ROLE_USER')]   // любой авторизованный
    public function chooseRole(): Response
    {
        $roles = $this->getUser()->getRoles();

        if (in_array('ROLE_ADMIN', $roles, true)) {
            return $this->redirectToRoute('admin');
        }
        if (in_array('ROLE_DIRECTOR', $roles, true)) {
            return $this->redirectToRoute('director');
        }

        // если роль ни admin ни director — на главную
        return $this->redirectToRoute('home');
    }
}