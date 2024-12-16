<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class YearsController extends AbstractController
{
    #[Route('/years', name: 'years' ,methods: ['GET'])]
    public function indexAction():JsonResponse
    {

        return new JsonResponse(['success' => true]);
    }


}