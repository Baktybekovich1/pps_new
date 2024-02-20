<?php

namespace App\Controller;

use App\Entity\Awards;
use App\Entity\ComtehnoPps;
use App\Repository\AwardsRepository;
use App\Repository\ComtehnoPpsRepository;
use App\Util\ValidationErrors;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class IndexController extends AbstractController
{
    public function __construct(private ComtehnoPpsRepository $ppsRepository, private AwardsRepository $awardsRepository)
    {
    }

    #[Route('/pps', name: 'app_index', methods: ['POST'])]
    public function index(Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $pps = $serializer->deserialize($request->getContent(), ComtehnoPps::class, "json");

        $violations = $validator->validate($pps);

        if ($violations->count() > 0) {
            return $this->json(ValidationErrors::format($violations));
        }

        $this->ppsRepository->save($pps);
        return new JsonResponse(["SAVED"], Response::HTTP_CREATED);
    }

    #[Route('/pps/awards', name: 'app_awards', methods: ['POST'])]
    public function awards_add(Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $awards = $serializer->deserialize($request->getContent(), Awards::class, 'json');
        $viola = $validator->validate($awards);
        if ($viola->count() > 0) {
            return $this->json(ValidationErrors::format($viola));
        }
        $this->awardsRepository->save($awards);

        return new JsonResponse(['AWARDS SAVED SUCCESS'], Response::HTTP_CREATED);

    }
}
