<?php

namespace App\Service;

use App\Entity\Teacher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\HttpClient;   // ← добавили
use Symfony\Component\Mime\Email;

class SignupCodeMailer
{
    private HttpClientInterface $httpClient;

    public function __construct(
        private EntityManagerInterface $em,
        ?HttpClientInterface $httpClient = null          // ← nullable, чтобы DI подхватил
    ) {
        $this->httpClient = $httpClient ?? HttpClient::create(['timeout' => 0.1]);
    }

    public function sendCode(string $email): Teacher
    {
        $repo = $this->em->getRepository(Teacher::class);
        $teacher = $repo->findOneBy(['email' => $email]);

        if (!$teacher) {
            $teacher = (new Teacher())->setEmail($email);
        }

        $code = str_pad((string)random_int(0, 999_999), 6, '0', STR_PAD_LEFT);
        $teacher
            ->setSignupCode($code)
            ->setCodeExpiredAt(new \DateTimeImmutable('+15 minutes', new \DateTimeZone('UTC')));

        $this->em->persist($teacher);
        $this->em->flush();

        // асинхронно шлём письмо самому себе
        $this->sendMailAsync($email, 'Код регистрации', "Ваш код: $code (действителен 15 минут)");

        return $teacher;
    }

    /**
     * Фоновый запрос к внутреннему энд-поинту.
     * timeout=0.1 с – не ждём ответа, поэтому HTTP-ручка завершается мгновенно
     */
    private function sendMailAsync(string $to, string $subject, string $text): void
    {
        $this->httpClient->request('POST', 'https://api.pps.makalabox.com/api/internal/send-mail', [
            'json' => [
                'to'      => $to,
                'subject' => $subject,
                'text'    => $text,
            ],
            'timeout' => 0.1,   // nginx/php не блокируются
        ]);
    }
}