<?php

namespace App\Service\Teacher;

use App\Dto\Award\SetAwardDto;
use App\Entity\TeacherAnswer;
use App\Repository\QuestionSubtitleRepository;
use App\Repository\TeacherAnswerRepository;
use App\Repository\TeacherRepository;

class TeacherAnswerService
{
    public function __construct(private readonly TeacherRepository $teacherRepository, private readonly TeacherAnswerRepository $teacherAnswerRepository, private readonly QuestionSubtitleRepository $questionSubtitleRepository)
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

}