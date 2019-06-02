<?php

namespace QuizzyBundle\Controller;

use QuizzyBundle\Entity\User;
use QuizzyBundle\Service\FriendService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use QuizzyBundle\Entity\Media;
use QuizzyBundle\Entity\Quiz;
use QuizzyBundle\Entity\QuizCompletion;
use QuizzyBundle\Service\ImageService;
use QuizzyBundle\Service\QuizService;

class QuizController extends Controller
{

	/**
     * @Route("/{user}/quiz")
     */
    public function getQuizAction(Request $request, $user)
    {
        $user = $this->em()->getRepository(User::REFERENCE)->find($user);
        $friendService = $this->get(FriendService::REFERENCE);
        $quizService = $this->get(QuizService::REFERENCE);
        $quizNotFinished = [];
        $quizShared = [];
        $quizCompleted = [];

        foreach ($quizService->getQuizNotFinished($user) as $quiz) {
            $quizNotFinished[] = $this->parseToArrayQuiz($quiz, $user);
        }
        foreach ($user->getQuizShared() as $quiz) {
            $quizCompletion = $this->em()->getRepository('QuizzyBundle:QuizCompletion')->findOneBy(['user' => $user, 'quiz' => $quiz]);
            if ($quizCompletion) {
                $quizCompleted[] = $this->parseToArrayQuiz($quiz, $user);
            }else {
                $quizShared[] = $this->parseToArrayQuiz($quiz, $user);
            }
        }

        if ($quizCompleted === null) {
            return new JsonResponse([
           'quiz_not_finished' => $quizNotFinished,
           'quiz_shared' => $quizShared,
           'quiz_completed' => null,
           'friends_request_counter' => count($friendService->getFriendsRequestByUser($user))
        ], 200);
        }

        return new JsonResponse([
           'quiz_not_finished' => $quizNotFinished,
           'quiz_shared' => $quizShared,
           'quiz_completed' => $quizCompleted,
           'friends_request_counter' => count($friendService->getFriendsRequestByUser($user))
        ], 200);
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
     * @Route("/quiz/{quiz}/delete", requirements={"quiz" = "\d+"})
     */
    public function deleteQuizAction(Request $request, $quiz)
    {
        $imageService  = $this->get(ImageService::REFERENCE);
        
        $quiz = $this->em()->getRepository('QuizzyBundle:Quiz')->find($quiz);
        $user = $this->em()->getRepository('QuizzyBundle:User')->find($quiz->getUser());
        $quizCompletions = $this->em()->getRepository('QuizzyBundle:QuizCompletion')->findBy(
            [
                'quiz' => $quiz
            ]
        );

        foreach ($quizCompletions as $quizC) {
            $partsC = $quizC->getPartsCompletion();
            foreach ($partsC as $part) {
                $questionsC = $part->getQuestionsCompletion();
                foreach ($questionsC as $question) {
                    $answersC = $question->getAnswersCompletion();
                    foreach ($answersC as $answer) {
                        $this->em()->remove($answer);
                    }
                    $this->em()->remove($question);
                }
                $this->em()->remove($part);
            }
            $this->em()->remove($quizC);   
        }

        $usersShared = $quiz->getUserShared();

        foreach ($usersShared as $userShared) {
            $quiz->removeUserShared($userShared);
        }

        foreach ($quiz->getParts() as $part) {
            if ($part->getMedia()) {// on delete l'image si il y en a
                $imageService->deleteImage($part->getMedia());
            }

            foreach ($part->getQuestions() as $question) {
                if ($question->getMedia()) {
                    $imageService->deleteImage($question->getMedia());
                }
                $this->em()->remove($question);
            }

            $this->em()->remove($part);
            $this->em()->flush();
        }

        $this->em()->remove($quiz);
        $this->em()->flush();

        return new JsonResponse('quiz deleted', 200);
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

        if($quiz->getParts()->count() == 0) {
            $status = false;
        }

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


    /**
     * @Route("/quiz/{user}/created", requirements={"user" = "\d+"})
     */
    public function getAllMyQuizCreatedAction(Request $request, $user)
    {
        $quizService = $this->get(QuizService::REFERENCE);
        $user        = $this->em()->getRepository('QuizzyBundle:User')->find($user);
        $allQuiz     = $quizService->getAllQuizFinished($user);
        $res         = [];
        foreach ($allQuiz as $quiz) {
            $tab = [
                "id" => $quiz->getId(),
                "name" => $quiz->getName(),
                "popularity" => $quiz->getPopularity() != null ? $quiz->getPopularity() : null,
                "media" => $quiz->getMedia() ? $quiz->getMedia()->getPath() : null,
                "isValidated" => [
                    "year" => (int)$quiz->getIsValidated()->format("Y"),
                    "month" => (int)$quiz->getIsValidated()->format("m"),
                    "day" => (int)$quiz->getIsValidated()->format("d")
                ]
            ];
            array_push($res, $tab);
        }
        return new JsonResponse($res, 200);
    }

    /**
     * @param Quiz $quiz
     * @param User $user
     * @return array
     */
    private function parseToArrayQuiz(Quiz $quiz, User $user)
    {
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

        return $tab;
    }

    /**
     * @Route("/{user}/{quiz}/score")
     */
    public function getQuizScoreAction(Request $request, $user, $quiz)
    {
        $quiz = $this->em()->getRepository('QuizzyBundle:Quiz')->find($quiz);
        $user = $this->em()->getRepository(User::REFERENCE)->find($user);

        $quizCompletion = $this->em()->getRepository('QuizzyBundle:QuizCompletion')->findOneBy([
            'user' => $user,
            'quiz' => $quiz,
        ]);

        $partsC = $quizCompletion->getPartsCompletion();

        $score = 0;

        foreach ($partsC as $part) {
            $questionsC = $part->getQuestionsCompletion();
            foreach ($questionsC as $question) {
                $answersC = $question->getAnswersCompletion();
                foreach ($answersC as $answer) {
                    $score = $score + $answer->getScore();
                }
            }
        }
        
        $parts = $quiz->getParts();

        $maxScore = 0;

        foreach ($parts as $part) {
            $questions = $part->getQuestions();
            foreach ($questions as $question) {
                $maxScore = $maxScore + $question->getMaxScore();
            }
        }

        $tabScore = [];
        $tabScore['score']    = $score;
        $tabScore['maxScore']  = $maxScore;

        $res = [
            "score" => $tabScore
        ];

        return new JsonResponse($res, 200);
    }

    /**
     * @Route("/{user}/{quiz}/share", requirements={"user" = "\d+"})
     */
    public function shareQuizAction(Request $request, $user, $quiz)
    {
        $quiz = $this->em()->getRepository('QuizzyBundle:Quiz')->find($quiz);
        $user = $this->em()->getRepository(User::REFERENCE)->find($user);

        $user->addQuizShared($quiz);

        $this->em()->persist($user);
        $this->em()->flush();

        $res = [
            "id" => $user->getId()
        ];
        return new JsonResponse($res, 200);
    }

    /**
     * @return mixed
     */
    private function em()
    {
        return $this->getDoctrine()->getEntityManager();
    }
}
