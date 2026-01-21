<?php

namespace App\Controller\Auth;

use App\Entity\Teacher;
use App\Repository\TeacherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use League\OAuth2\Client\Provider\Google;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class GoogleController extends AbstractController
{
    private Google $google;

    public function __construct(
        private TeacherRepository        $repo,
        private EntityManagerInterface   $em,
        private JWTTokenManagerInterface $jwt
    )
    {
        $this->google = new Google([
            'clientId' => $_ENV['GOOGLE_CLIENT_ID'],
            'clientSecret' => $_ENV['GOOGLE_CLIENT_SECRET'],
            'redirectUri' => $_ENV['GOOGLE_REDIRECT_URI'],
//            'hostedDomain' => '*',   // любой домен
        ]);
    }

    /** Редирект на Google */
    #[Route('/api/auth/google', name: 'google_auth')]
    public function googleRedirect(): Response
    {
        $url = $this->google->getAuthorizationUrl(['scope' => ['email', 'profile']]);
        return $this->redirect($url);
    }

    /** Google возвращает сюда */
    #[Route('/api/auth/google/check', name: 'google_check')]
    public function googleCheck(Request $request): Response
    {
        try {
            $code = $request->query->get('code');

            $token = $this->google->getAccessToken('authorization_code', [
                'code' => $code
            ]);

            $googleUser = $this->google->getResourceOwner($token);
        } catch (\Throwable $e) {
            return $this->json([
                'error' => 'Google auth failed',
                'details' => $e->getMessage()
            ], 401);
        }

        $email = $googleUser->getEmail();
        if (!$email) {
            return $this->json(['error' => 'Email not provided'], 401);
        }

        $teacher = $this->repo->findOneBy(['email' => $email]);

        if (!$teacher) {
            $teacher = (new Teacher())
                ->setEmail($email)
                ->setFirstName($googleUser->getFirstName() ?? '')
                ->setLastName($googleUser->getLastName() ?? '')
                ->setRoles(['ROLE_TEACHER']);

            $this->em->persist($teacher);
            $this->em->flush();
        }

        $jwt = $this->jwt->create($teacher);

        return $this->redirect(
            $_ENV['FRONT_OAUTH_SUCCESS'] . '?token=' . $jwt
        );
    }
//    #[Route('/api/auth/google/check', name: 'google_check')]
//    public function googleCheck(): Response
//    {
//        try {
//            $token = $this->google->getAccessToken('authorization_code', ['code' => $_GET['code'] ?? '']);
//            $googleUser = $this->google->getResourceOwner($token);
//        } catch (\Exception $e) {
//            return $this->json([
//                'error' => 'Google auth failed',
//                'details' => $e->getMessage()   // ← добавим
//            ], 401);
//        }
//
//        $email = $googleUser->getEmail();
//        if (!$email) {
//            return $this->json(['error' => 'Email not provided'], 401);
//        }
//
//        // ищем или создаём
//        $teacher = $this->repo->findOneBy(['email' => $email]);
//        if (!$teacher) {
//            $teacher = (new Teacher())
//                ->setEmail($email)
//                ->setFirstName($googleUser->getFirstName() ?? '')
//                ->setLastName($googleUser->getLastName() ?? '');
//            // пароль НЕ устанавливаем (останется null)
//            $this->em->persist($teacher);
//            $this->em->flush();
//        }
//
//        // выдаём JWT
//        $jwt = $this->jwt->create($teacher);
//
//        // редирект на фронт с токеном
//        return $this->redirect($_ENV['FRONT_OAUTH_SUCCESS'] . '?token=' . $jwt);
//    }
}