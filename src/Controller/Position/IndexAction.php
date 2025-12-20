<?php

namespace App\Controller\Position;

use App\Repository\PositionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexAction extends AbstractController
{
    #[Route('/api/positions', name: 'position_index', methods: ['GET'])]
    public function __invoke(PositionRepository $repo): Response
    {
        $positions = $repo->findBy([], ['name' => 'ASC']);
        return $this->json($positions, 200, [], ['groups' => 'position:read']);
    }
}