<?php

namespace App\Service\Teacher;

use App\Dto\Award\SetAwardDto;
use App\Entity\TeacherAnswer;
use App\Factory\Answer\AnswerDtoFactory;
use App\Factory\Answer\StageDtoFactory;
use App\Repository\QuestionSubtitleRepository;
use App\Repository\StageRepository;
use App\Repository\TeacherAnswerRepository;
use App\Repository\TeacherRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class TeacherAnswerService
{
    public function __construct(
        private readonly TeacherRepository          $teacherRepository,
        private readonly TeacherAnswerRepository    $teacherAnswerRepository,
        private readonly QuestionSubtitleRepository $questionSubtitleRepository,
        private readonly StageDtoFactory            $stageDtoFactory, private readonly StageRepository $stageRepository
    )
    {
    }

    public function save($email, SetAwardDto $dto): bool
    {
        $teacher = $this->teacherRepository->findOneBy(['email' => $email]);
        $answer = new TeacherAnswer();
        $answer->setTeacher($teacher);
        $answer->setActive(true);
        $answer->setSubtitle($this->questionSubtitleRepository->find($dto->subtitleId));
        $answer->setLink($dto->link);
        return $this->teacherAnswerRepository->save($answer);
    }

    public function getAll(string $email): array
    {
        $teacher = $this->teacherRepository->findOneBy(['email' => $email]);
        $stages = $this->stageRepository->findBy(['active' => true]);
        $awards = [];
        foreach ($stages as $stage) {
            $awards[] = $this->stageDtoFactory->factory($teacher, $stage);
        }
        return $awards;
    }

    public function delete(string $email, int $answerId): bool
    {
        $teacher = $this->teacherRepository->findOneBy(['email' => $email]);
        $answer = $this->teacherAnswerRepository->findOneBy(['id' => $answerId, 'teacher' => $teacher]);
        return $this->teacherAnswerRepository->remove($answer);
    }

    public function edit(string $email, int $answerId, string $answerLink): bool
    {
        $teacher = $this->teacherRepository->findOneBy(['email' => $email]);
        $answer = $this->teacherAnswerRepository->findOneBy(['id' => $answerId, 'teacher' => $teacher]);
        $answer->setActive(true);
        $answer->setLink($answerLink);
        return $this->teacherAnswerRepository->save($answer);
    }

}