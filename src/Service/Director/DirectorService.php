<?php

namespace App\Service\Director;

use App\Dto\Director\DirectorInstituteAnswerDto;
use App\Dto\Director\DirectorInstituteDto;
use App\Entity\InstituteAnswer;
use App\Entity\Teacher;
use App\Factory\Director\DirectorNameDtoFactory;
use App\Repository\DirectorRepository;
use App\Repository\InstituteAnswerRepository;
use App\Repository\InstituteQuestionSubtitleRepository;
use App\Repository\TeacherOrganizationRepository;
use App\Repository\YearsRepository;
use Doctrine\ORM\EntityManagerInterface;

class DirectorService
{
    public function __construct(
        private readonly DirectorRepository $directorRepository,
        private readonly DirectorNameDtoFactory $directorNameDtoFactory,
        private readonly InstituteAnswerRepository $instituteAnswerRepository,
        private readonly InstituteQuestionSubtitleRepository $subtitleRepository,
        private readonly TeacherOrganizationRepository $teacherOrganizationRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly YearsRepository $yearsRepository,
    )
    {
    }

    public function findAllDirectors(): array
    {
        $directors = $this->directorRepository->findAll();
        $result = [];
        foreach ($directors as $director) {
            $result[] = $this->directorNameDtoFactory->factory($director);
        }
        return $result;
    }

    public function getInstituteData(Teacher $teacher): DirectorInstituteDto
    {
        $director = $this->directorRepository->findOneBy(['teacher' => $teacher]);
        if (!$director) {
            throw new \Exception("Вы не являетесь директором", 403);
        }

        $institute = $director->getInstitute();
        $teachers = [];
        foreach ($institute->getTeacherOrganizations() as $to) {
            $t = $to->getTeacher();
            $teachers[] = [
                'id' => $t->getId(),
                'firstName' => $t->getFirstName(),
                'lastName' => $t->getLastName(),
                'middleName' => $t->getMiddleName(),
                'email' => $t->getEmail(),
                'position' => $t->getPosition() ? $t->getPosition()->getName() : 'N/A',
            ];
        }

        return new DirectorInstituteDto(
            $institute->getId(),
            $institute->getName(),
            $institute->getOrganization()->getName(),
            $institute->getTeacherTotal(),
            $teachers
        );
    }

    public function updateInstitute(Teacher $teacher, int $teacherTotal): void
    {
        $director = $this->directorRepository->findOneBy(['teacher' => $teacher]);
        if (!$director) {
            throw new \Exception("Access Denied", 403);
        }

        $institute = $director->getInstitute();
        $institute->setTeacherTotal($teacherTotal);
        $this->entityManager->flush();
    }

    public function getInstituteAnswers(Teacher $teacher): array
    {
        $director = $this->directorRepository->findOneBy(['teacher' => $teacher]);
        if (!$director) {
            throw new \Exception("Access Denied", 403);
        }

        $institute = $director->getInstitute();
        $answers = $this->instituteAnswerRepository->findBy(['institute' => $institute]);

        // Initialize answers if not already created
        if (empty($answers)) {
            $subtitles = $this->subtitleRepository->findAll();
            foreach ($subtitles as $subtitle) {
                $answer = new InstituteAnswer();
                $answer->setInstitute($institute);
                $answer->setSubtitle($subtitle);
                $answer->setActive(false);
                $this->entityManager->persist($answer);
            }
            $this->entityManager->flush();
            $answers = $this->instituteAnswerRepository->findBy(['institute' => $institute]);
        }

        $result = [];
        foreach ($answers as $answer) {
            $result[] = new DirectorInstituteAnswerDto(
                $answer->getId(),
                $answer->getSubtitle()->getId(),
                $answer->getSubtitle()->getTitle()->getName(),
                $answer->getSubtitle()->getName(),
                $answer->getLink(),
                $answer->getSubtitle()->getPoint(),
                $answer->isActive()
            );
        }

        return $result;
    }

    public function updateInstituteAnswer(Teacher $teacher, int $answerId, string $link): void
    {
        $director = $this->directorRepository->findOneBy(['teacher' => $teacher]);
        $answer = $this->instituteAnswerRepository->find($answerId);

        if (!$director || !$answer || $answer->getInstitute() !== $director->getInstitute()) {
            throw new \Exception("Access Denied", 403);
        }

        $answer->setLink($link);
        if ($link !== '') {
            $answer->setActive(true);
        }
        $this->entityManager->flush();
    }

    /**
     * Adds a new award entry (new InstituteAnswer row) for a given subtitle.
     * This allows multiple awards of the same type.
     */
    public function addInstituteAward(Teacher $teacher, int $subtitleId, string $link): array
    {
        // Проверяем блокировку года
        $currentYear = $this->yearsRepository->findCurrentYear();
        if ($currentYear && $currentYear->isLocked()) {
            throw new \Exception('Год заблокирован. Добавление наград недоступно.', 423);
        }

        $director = $this->directorRepository->findOneBy(['teacher' => $teacher]);
        if (!$director) {
            throw new \Exception("Access Denied", 403);
        }

        $subtitle = $this->subtitleRepository->find($subtitleId);
        if (!$subtitle) {
            throw new \Exception("Subtitle not found", 404);
        }

        $institute = $director->getInstitute();

        $answer = new InstituteAnswer();
        $answer->setInstitute($institute);
        $answer->setSubtitle($subtitle);
        $answer->setLink($link);
        $answer->setActive(true);
        // Привязываем к текущему году
        $answer->setAcademicYear($currentYear);

        $this->entityManager->persist($answer);
        $this->entityManager->flush();

        return [
            'id'           => $answer->getId(),
            'subtitleName' => $subtitle->getName(),
            'titleName'    => $subtitle->getTitle()->getName(),
            'answerLink'   => $link,
            'point'        => $subtitle->getPoint(),
        ];
    }

    /**
     * Returns only answered institute awards (where link is not null/empty).
     */
    public function getInstituteAwards(Teacher $teacher): array
    {
        $director = $this->directorRepository->findOneBy(['teacher' => $teacher]);
        if (!$director) {
            throw new \Exception("Access Denied", 403);
        }

        $institute = $director->getInstitute();
        $answers = $this->instituteAnswerRepository->findBy(['institute' => $institute]);

        $result = [];
        foreach ($answers as $answer) {
            if ($answer->getLink()) {
                $result[] = new DirectorInstituteAnswerDto(
                    $answer->getId(),
                    $answer->getSubtitle()->getId(),
                    $answer->getSubtitle()->getTitle()->getName(),
                    $answer->getSubtitle()->getName(),
                    $answer->getLink(),
                    $answer->getSubtitle()->getPoint(),
                    $answer->isActive()
                );
            }
        }
        return $result;
    }

    public function removeTeacherFromInstitute(Teacher $teacher, int $targetTeacherId): void
    {
        $director = $this->directorRepository->findOneBy(['teacher' => $teacher]);
        if (!$director) {
            throw new \Exception("Access Denied", 403);
        }

        $institute = $director->getInstitute();
        $to = $this->teacherOrganizationRepository->findOneBy([
            'teacher' => $targetTeacherId,
            'institute' => $institute
        ]);

        if ($to) {
            $this->entityManager->remove($to);
            $this->entityManager->flush();
        }
    }

    private function findOwnedAward(Teacher $teacher, int $answerId): \App\Entity\InstituteAnswer
    {
        $director = $this->directorRepository->findOneBy(['teacher' => $teacher]);
        if (!$director) throw new \Exception('Access Denied', 403);

        $answer = $this->instituteAnswerRepository->find($answerId);
        if (!$answer || $answer->getInstitute() !== $director->getInstitute()) {
            throw new \Exception('Not found or forbidden', 403);
        }
        return $answer;
    }

    public function editInstituteAward(Teacher $teacher, int $answerId, string $link): void
    {
        $answer = $this->findOwnedAward($teacher, $answerId);
        if (!str_starts_with($link, 'http')) $link = 'https://' . $link;
        $answer->setLink($link);
        $this->entityManager->flush();
    }

    public function deleteInstituteAward(Teacher $teacher, int $answerId): void
    {
        $answer = $this->findOwnedAward($teacher, $answerId);
        $this->entityManager->remove($answer);
        $this->entityManager->flush();
    }

    public function setInstituteAwardActive(Teacher $teacher, int $answerId, bool $active): void
    {
        $answer = $this->findOwnedAward($teacher, $answerId);
        $answer->setActive($active);
        $this->entityManager->flush();
    }
}