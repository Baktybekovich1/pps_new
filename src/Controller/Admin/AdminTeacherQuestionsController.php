<?php

namespace App\Controller\Admin;

use App\Entity\QuestionSubtitle;
use App\Entity\QuestionTitle;
use App\Repository\QuestionSubtitleRepository;
use App\Repository\QuestionTitleRepository;
use App\Repository\StageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

/**
 * CRUD для вопросов (Stages/Titles/Subtitles) преподавателей
 * Prefix: /api/admin/teacher/questions (через admin_controllers + маршрут)
 */
#[Route('/teacher/questions')]
#[OA\Tag(name: 'Admin Teacher Questions')]
class AdminTeacherQuestionsController extends AbstractController
{
    public function __construct(
        private readonly QuestionTitleRepository    $titleRepository,
        private readonly QuestionSubtitleRepository $subtitleRepository,
        private readonly StageRepository            $stageRepository,
    ) {}

    /* ─────── GET /titles  ─────── */
    #[Route('/titles', name: 'admin_teacher_q_titles_list', methods: ['GET'])]
    public function listTitles(): JsonResponse
    {
        $titles = $this->titleRepository->findAll();
        $result = [];
        foreach ($titles as $title) {
            $result[] = [
                'titleId'   => $title->getId(),
                'titleName' => $title->getName(),
                'stageId'   => $title->getStage()?->getId(),
                'stageName' => $title->getStage()?->getName(),
            ];
        }
        return $this->json($result);
    }

    /* ─────── POST /titles  ─────── */
    #[Route('/titles', name: 'admin_teacher_q_titles_create', methods: ['POST'])]
    public function createTitle(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $name    = trim($data['titleName'] ?? '');
        $stageId = (int)($data['stageId'] ?? 0);

        if (!$name)    return $this->json(['error' => 'titleName is required'], 400);
        if (!$stageId) return $this->json(['error' => 'stageId is required'], 400);

        $stage = $this->stageRepository->find($stageId);
        if (!$stage)   return $this->json(['error' => 'Stage not found'], 404);

        $title = new QuestionTitle();
        $title->setName($name);
        $title->setStage($stage);

        $em = $this->titleRepository->getEntityManager();
        $em->persist($title);
        $em->flush();

        return $this->json(['id' => $title->getId(), 'message' => 'Created'], 201);
    }

    /* ─────── PUT /titles/{id}  ─────── */
    #[Route('/titles/{id}', name: 'admin_teacher_q_titles_update', methods: ['PUT'])]
    public function updateTitle(int $id, Request $request): JsonResponse
    {
        $title = $this->titleRepository->find($id);
        if (!$title) return $this->json(['error' => 'Not found'], 404);

        $data = json_decode($request->getContent(), true);
        $name    = trim($data['titleName'] ?? '');
        $stageId = (int)($data['stageId'] ?? 0);

        if ($name)    $title->setName($name);
        if ($stageId) {
            $stage = $this->stageRepository->find($stageId);
            if ($stage) $title->setStage($stage);
        }

        $em = $this->titleRepository->getEntityManager();
        $em->flush();

        return $this->json(['message' => 'Updated']);
    }

    /* ─────── DELETE /titles/{id}  ─────── */
    #[Route('/titles/{id}', name: 'admin_teacher_q_titles_delete', methods: ['DELETE'])]
    public function deleteTitle(int $id): JsonResponse
    {
        $title = $this->titleRepository->find($id);
        if (!$title) return $this->json(['error' => 'Not found'], 404);

        $em = $this->titleRepository->getEntityManager();
        // Cascade: удаляем связанные subtitle
        foreach ($title->getQuestionSubtitles() as $sub) {
            $em->remove($sub);
        }
        $em->remove($title);
        $em->flush();

        return $this->json(['message' => 'Deleted']);
    }

    /* ─────── GET /subtitles?titleId=X  ─────── */
    #[Route('/subtitles', name: 'admin_teacher_q_subtitles_list', methods: ['GET'])]
    public function listSubtitles(Request $request): JsonResponse
    {
        $titleId = $request->query->get('titleId');
        $criteria = $titleId ? ['title' => (int)$titleId] : [];
        $subs = $this->subtitleRepository->findBy($criteria);
        $result = [];
        foreach ($subs as $sub) {
            $result[] = [
                'subtitleId'   => $sub->getId(),
                'subtitleName' => $sub->getName(),
                'point'        => $sub->getPoint(),
                'titleId'      => $sub->getTitle()?->getId(),
                'titleName'    => $sub->getTitle()?->getName(),
                'stageId'      => $sub->getTitle()?->getStage()?->getId(),
                'isPermanent'  => $sub->getTitle()?->getStage()?->isPermanent(),
            ];
        }
        return $this->json($result);
    }

    /* ─────── POST /subtitles  ─────── */
    #[Route('/subtitles', name: 'admin_teacher_q_subtitles_create', methods: ['POST'])]
    public function createSubtitle(Request $request): JsonResponse
    {
        $data    = json_decode($request->getContent(), true);
        $name    = trim($data['subtitleName'] ?? '');
        $titleId = (int)($data['titleId'] ?? 0);
        $point   = isset($data['point']) ? (int)$data['point'] : null;

        if (!$name)         return $this->json(['error' => 'subtitleName is required'], 400);
        if (!$titleId)      return $this->json(['error' => 'titleId is required'], 400);
        if ($point === null) return $this->json(['error' => 'point is required'], 400);

        $title = $this->titleRepository->find($titleId);
        if (!$title) return $this->json(['error' => 'Title not found'], 404);

        $sub = new QuestionSubtitle();
        $sub->setName($name);
        $sub->setTitle($title);
        $sub->setPoint($point);

        $em = $this->subtitleRepository->getEntityManager();
        $em->persist($sub);
        $em->flush();

        return $this->json(['id' => $sub->getId(), 'message' => 'Created'], 201);
    }

    /* ─────── PUT /subtitles/{id}  ─────── */
    #[Route('/subtitles/{id}', name: 'admin_teacher_q_subtitles_update', methods: ['PUT'])]
    public function updateSubtitle(int $id, Request $request): JsonResponse
    {
        $sub = $this->subtitleRepository->find($id);
        if (!$sub) return $this->json(['error' => 'Not found'], 404);

        $data = json_decode($request->getContent(), true);
        if (isset($data['subtitleName'])) $sub->setName(trim($data['subtitleName']));
        if (isset($data['point']))        $sub->setPoint((int)$data['point']);
        if (isset($data['titleId'])) {
            $title = $this->titleRepository->find((int)$data['titleId']);
            if ($title) $sub->setTitle($title);
        }

        $em = $this->subtitleRepository->getEntityManager();
        $em->flush();

        return $this->json(['message' => 'Updated']);
    }

    /* ─────── DELETE /subtitles/{id}  ─────── */
    #[Route('/subtitles/{id}', name: 'admin_teacher_q_subtitles_delete', methods: ['DELETE'])]
    public function deleteSubtitle(int $id): JsonResponse
    {
        $sub = $this->subtitleRepository->find($id);
        if (!$sub) return $this->json(['error' => 'Not found'], 404);

        $em = $this->subtitleRepository->getEntityManager();
        $em->remove($sub);
        $em->flush();

        return $this->json(['message' => 'Deleted']);
    }

    /* ─────── GET /stages  ─────── */
    #[Route('/stages', name: 'admin_teacher_q_stages_list', methods: ['GET'])]
    public function listStages(): JsonResponse
    {
        $stages = $this->stageRepository->findAll();
        $result = [];
        foreach ($stages as $stage) {
            $result[] = [
                'id'          => $stage->getId(),
                'name'        => $stage->getName(),
                'isPermanent' => $stage->isPermanent(),
            ];
        }
        return $this->json($result);
    }
}
