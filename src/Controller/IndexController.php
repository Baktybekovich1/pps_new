<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;


class IndexController extends AbstractController
{

    #[Route('/pps/awards', name: 'app_awds')]
    public function awards_add(): JsonResponse
    {
        return $this->json(['HELLO']);
    }

}
