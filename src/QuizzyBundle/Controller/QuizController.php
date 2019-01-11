<?php

namespace QuizzyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use QuizzyBundle\Entity\Media;
use QuizzyBundle\Entity\Quiz;
use QuizzyBundle\Service\ImageService;

class QuizController extends Controller
{

	/**
     * @Route("/{user}/quiz/status/{finished}")
     */
    public function getQuizAction(Request $request, $user, $finished)
    {
        $user = $this->em()->getRepository('QuizzyBundle:User')->find($user);
        $allQuiz = $this->em()->getRepository('QuizzyBundle:Quiz')->findBy(["user" => $user]);
        $res = [];
        foreach ($allQuiz as $quiz) {
            $add = null;
            if ($finished) {
                $quiz->getIsValidated() != null ? $add = $quiz : "";
            } else {
                $quiz->getIsValidated() == null ? $add = $quiz : "";
            }

            if ($add != null) {
                $tab = [
                    "id" => $quiz->getId(),
                    "name" => $quiz->getName(),
                    "popularity" => $quiz->getPopularity() != null ? $quiz->getPopularity() : null,
                    "media" => $quiz->getMedia() ? $quiz->getMedia()->getPath() : null
                ];
                if ($quiz->getIsValidated() != null) {
                    $tab["isValidated"] = [
                        "year" => (int)$user->getBirthDate()->format("Y"),
                        "month" => (int)$user->getBirthDate()->format("m"),
                        "day" => (int)$user->getBirthDate()->format("d")
                    ];
                }
                array_push($res, $tab);
            }
        }
        return new JsonResponse($res, 200);
    }

    /**
     * @Route("/{user}/quiz/new", requirements={"user" = "\d+"})
     */
    public function newQuizAction(Request $request, $user)
    {
        $imageService  = $this->get(ImageService::REFERENCE);
        $user          = $this->em()->getRepository('QuizzyBundle:User')->find($user);

        $quiz = new Quiz();
        $quiz->setName($request->request->get('name'));
        $quiz->setUser($user);

        if (!empty($request->request->get('media'))) {
            $media = new Media();
            $media->setPath($imageService->saveImage($request->request->get('media'), "_quiz_img.jpeg"));
            $quiz->setMedia($media);

            $this->em()->persist($media);
        }

        $this->em()->persist($quiz);
        $this->em()->flush();

        $res = [
            "id" => $quiz->getId(),
            "media" => $quiz->getMedia() != null ? $quiz->getMedia()->getPath() : null
        ];
        return new JsonResponse($res, 200);
    }

    /**
     * @Route("/quiz/edit/{quiz}", requirements={"quiz" = "\d+"})
     */
    public function setQuizAction(Request $request, $quiz)
    {
        $imageService  = $this->get(ImageService::REFERENCE);
        $quiz          = $this->em()->getRepository('QuizzyBundle:Quiz')->find($quiz);

        if ($quiz->getMedia()) {// on delete l'image si il y a en
            $imageService->deleteImage($quiz->getMedia());
        }

        $quiz->setMedia(null);
        $quiz->setName($request->request->get('name'));

        if (!empty($request->request->get('media'))) {
            $media = new Media();
            $media->setPath($imageService->saveImage($request->request->get('media'), "_quiz_img.jpeg"));
            $quiz->setMedia($media);

            $this->em()->persist($media);
        }

        $this->em()->persist($quiz);
        $this->em()->flush();

        $res = [
            "media" => $quiz->getMedia() != null ? $quiz->getMedia()->getPath() : null
        ];
        return new JsonResponse($res, 200);
    }

    /**
     * @Route("/quiz/{quiz}/check", requirements={"quiz" = "\d+"})
     */
    public function checkQuizAction(Request $request, $quiz)
    {
        $quiz   = $this->em()->getRepository('QuizzyBundle:Quiz')->find($quiz);
        $status = true;

        foreach ($quiz->getParts() as $part) {
            if (!$part->getName() || $part->getQuestions()->count() == 0) {
                $status = false;
                break;
            }
            foreach ($part->getQuestions() as $question) {
                $oneGoodAnswer = false;
                foreach ($question->getAnswers() as $answer) {
                    if (!$answer->getName()) {
                        $status = false;
                        break;
                    }
                    if($answer->getIsCorrect()) {
                        $oneGoodAnswer = true;
                    }
                }
                if (!$question->getName() || $question->getAnswers()->count() == 0 || !$oneGoodAnswer) {
                    $status = false;
                    break;
                }
            }
        }

        if ($status) {
            $quiz->setIsValidated(new \DateTime());
            $this->em()->persist($quiz);
            $this->em()->flush();
        }

        $res = [
            "status" => $status
        ];
        return new JsonResponse($res, 200);
    }

    private function em()
    {
        return $this->getDoctrine()->getEntityManager();
    }
}
