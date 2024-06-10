<?php

namespace App\Controller\admin\StageEdit;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class SubtitleDeleteController extends AbstractController
{
    #[Route('/subtitle/delete/award', name: 'app_subtitle_delete')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/SubtitleDeleteController.php',
        ]);
    }
}
