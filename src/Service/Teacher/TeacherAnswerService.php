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
use App\Repository\YearsRepository;

class TeacherAnswerService
{
    public function __construct(
        private readonly TeacherRepository          $teacherRepository,
        private readonly TeacherAnswerRepository    $teacherAnswerRepository,
        private readonly QuestionSubtitleRepository $questionSubtitleRepository,
        private readonly StageDtoFactory            $stageDtoFactory,
        private readonly StageRepository            $stageRepository,
        private readonly YearsRepository            $yearsRepository,
    )
    {
    }

    /**
     * Сохранить новую награду/ответ преподавателя.
     * Проверяет: год не заблокирован.
     * Привязывает ответ к текущему академическому году.
     *
     * @throws \RuntimeException если год заблокирован
     */
    public function save($email, SetAwardDto $dto): bool
    {
        $currentYear = $this->yearsRepository->findCurrentYear();
        if (!$currentYear) {
            throw new \RuntimeException('Текущий год не установлен. Обратитесь к администратору.', 409);
        }

        if ($currentYear && $currentYear->isLocked()) {
            throw new \RuntimeException('Год заблокирован. Добавление наград недоступно.', 423);
        }

        $teacher = $this->teacherRepository->findOneBy(['email' => $email]);
        $answer = new TeacherAnswer();
        $answer->setTeacher($teacher);
        $answer->setActive(true);
        $answer->setSubtitle($this->questionSubtitleRepository->find($dto->subtitleId));
        $answer->setLink($dto->link);
        // Привязываем к текущему году
        $answer->setAcademicYear($currentYear);

        return $this->teacherAnswerRepository->save($answer);
    }

    public function getAll(string $email, ?int $yearId = null): array
    {
        $teacher = $this->teacherRepository->findOneBy(['email' => $email]);
        $currentYear = $yearId ? $this->yearsRepository->find($yearId) : $this->yearsRepository->findCurrentYear();
        $stages = $this->stageRepository->findBy(['active' => true]);
        $awards = [];
        foreach ($stages as $stage) {
            $awards[] = $this->stageDtoFactory->factory($teacher, $stage, $currentYear);
        }
        return $awards;
    }

    public function delete(string $email, $answerId): bool
    {
        $teacher = $this->teacherRepository->findOneBy(['email' => $email]);
        $currentYear = $this->yearsRepository->findCurrentYear();
        $answer = $this->teacherAnswerRepository->findOneByTeacherAndYear((int)$answerId, $teacher, $currentYear);

        if (!$answer) {
            throw new \RuntimeException('Награда не найдена в текущем году.', 404);
        }

        return $this->teacherAnswerRepository->remove($answer);
    }

    public function edit(string $email, $answerId, string $answerLink): bool
    {
        // Проверяем блокировку текущего года
        $currentYear = $this->yearsRepository->findCurrentYear();
        if ($currentYear && $currentYear->isLocked()) {
            throw new \RuntimeException('Год заблокирован. Редактирование недоступно.', 423);
        }

        $teacher = $this->teacherRepository->findOneBy(['email' => $email]);
        $answer = $this->teacherAnswerRepository->findOneByTeacherAndYear((int)$answerId, $teacher, $currentYear);
        if (!$answer) {
            throw new \RuntimeException('Награда не найдена в текущем году.', 404);
        }
        if ($answer->getAcademicYear() === null && $currentYear) {
            $answer->setAcademicYear($currentYear);
        }
        $answer->setActive(true);
        $answer->setLink($answerLink);
        return $this->teacherAnswerRepository->save($answer);
    }

}
