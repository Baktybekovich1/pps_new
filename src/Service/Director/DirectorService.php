<?php

namespace App\Service\Director;

use App\Factory\Director\DirectorNameDtoFactory;
use App\Repository\DirectorRepository;

class DirectorService
{
    public function __construct(
        private readonly DirectorRepository $directorRepository,
        private readonly DirectorNameDtoFactory $directorNameDtoFactory
    )
    {
    }

    public function findAllDirectors():array
    {
        $directors = $this->directorRepository->findAll();
        $result = [];
        foreach ($directors as $director) {
            $result[] = $this->directorNameDtoFactory->factory($director);
        }
        return $result;
    }
}