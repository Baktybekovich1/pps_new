<?php

namespace App\Controller;

use App\Dto\SignUpDto;
use App\Entity\User;
use App\Exception\UserAlreadyExistsException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Model\IdResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SignUpController extends AbstractController
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    #[Route('/pps/sign-up', name: 'app_sign_up', methods: ['POST'])]
    public function signUp(#[MapRequestPayload] SignUpDto $dto, UserPasswordHasherInterface $userPasswordHasher): JsonResponse
    {

        if ($this->userRepository->existsByUsername($dto->username)) {
            throw new UserAlreadyExistsException();
        }
        $user = new User();
        $user->setUsername($dto->username);
        $user->setPassword($userPasswordHasher->hashPassword($user, $dto->password));
        if ($dto->username == 'admin') {
            $user->setRoles(['ROLE_ADMIN']);
        } else {
            $user->setRoles(["ROLE_USER"]);
        }
        $this->userRepository->save($user);

        return $this->json(['token' => 'Ilya Salam']);
    }

}
