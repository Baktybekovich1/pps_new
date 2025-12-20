<?php

namespace App\Service;
use App\Entity\Teacher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SignupCodeMailer
{
    public function __construct(
        private MailerInterface $mailer,
        private EntityManagerInterface $em
    ) {}

    public function sendCode(string $email): Teacher
    {
        $repo = $this->em->getRepository(Teacher::class);
        $teacher = $repo->findOneBy(['email' => $email]);

        if (!$teacher) {                                    // ⬅️ СНАЧАЛА создаём
            $teacher = (new Teacher())->setEmail($email);
        }

        $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $teacher
            ->setSignupCode($code)
            ->setCodeExpiredAt(new \DateTimeImmutable('+15 minutes', new \DateTimeZone('UTC')));

        $this->em->persist($teacher);
        $this->em->flush();

        $mail = (new Email())
            ->from($_ENV['MAILER_FROM'] ?? 'noreply@example.com')
            ->to($email)
            ->subject('Код регистрации')
            ->text("Ваш код: $code (действителен 15 минут)");

        $this->mailer->send($mail);

        return $teacher;
    }
}