<?php

namespace QuizzyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use QuizzyBundle\Entity\Media;
use QuizzyBundle\Entity\Question;
use QuizzyBundle\Entity\Answer;
use QuizzyBundle\Service\ImageService;

class QuestionController extends Controller
{

	/**
     * @Route("/part/{part}", requirements={"part" = "\d+"})
     */
    public function getQuestionsAction(Request $request, $part)
    {
    	$part      = $this->em()->getRepository('QuizzyBundle:Part')->find($part);
        $questions = $this->em()->getRepository('QuizzyBundle:Question')->findBy(["part" => $part]);
        $res       = [];

        foreach ($questions as $question) {
            $data = [
                "id"     => $question->getId(),
                "name"   => $question->getName(),
                "grade"  => $question->getMaxScore(),
                "type"   => $question->getTypeQuestion()->getType(),
                "media"  => $question->getMedia() != null ? $question->getMedia()->getPath() : null
            ];

            $allAnswer = [];
            foreach ($question->getAnswers() as $answer) {
             	$answerTab = [
             		"id"        => $answer->getId(),
             		"name"      => $answer->getName(),
             		"isCorrect" => $answer->getIsCorrect()
             	];
            	array_push($allAnswer, $answerTab);
            }

            $data["answers"] = $allAnswer;
            array_push($res, $data);
        }

        return new JsonResponse($res, 200);
    }

	/**
     * @Route("/part/{part}/question/new", requirements={"part" = "\d+"})
     */
    public function newQuestionAction(Request $request, $part)
    {
        $imageService  = $this->get(ImageService::REFERENCE);
        $part          = $this->em()->getRepository('QuizzyBundle:Part')->find($part);
        $typeQuestion  = $this->em()->getRepository('QuizzyBundle:TypeQuestion')->findOneBy(["type" => $request->request->get('type')]);

        $question = new Question();
        $question->setName($request->request->get('name'));
        $question->setMaxScore($request->request->get('grade'));
        $question->setPart($part);
        $question->setTypeQuestion($typeQuestion);

        if (!empty($request->request->get('media'))) {
            $media = new Media();
            $media->setPath($imageService->saveImage($request->request->get('media'), "_question_img.jpeg"));
            $question->setMedia($media);

            $this->em()->persist($media);
        }

        $this->em()->persist($question);

        if ((int)$request->request->get('nbAnswers') > 0) {
        	for ($i = 0; $i < (int)$request->request->get('nbAnswers'); $i++) { 
        		$answer = new Answer() ;
        		$answer->setName($request->request->get('name_answer_'.$i));
        		$answer->setIsCorrect(
        			filter_var(
        				$request->request->get('is_correct_answer_'.$i),
        				FILTER_VALIDATE_BOOLEAN
        			)
        		);
        		$answer->setQuestion($question);
        		$this->em()->persist($answer);
        	}
        }

        $this->em()->flush();

        $res = [
            "id" => $question->getId(),
            "media" => $question->getMedia() != null ? $question->getMedia()->getPath() : null
        ];
        return new JsonResponse($res, 200);
    }

    /**
     * @Route("/question/edit/{question}", requirements={"question" = "\d+"})
     */
    public function setQuestionAction(Request $request, $question)
    {
        $imageService  = $this->get(ImageService::REFERENCE);
        $question      = $this->em()->getRepository('QuizzyBundle:Question')->find($question);
        $typeQuestion  = $this->em()->getRepository('QuizzyBundle:TypeQuestion')->findOneBy(["type" => $request->request->get('type')]);

        if ($question->getMedia()) {// on delete l'image si il y a en
            $imageService->deleteImage($question->getMedia());
        }

        $question->setMedia(null);
        $question->setName($request->request->get('name'));
        $question->setMaxScore($request->request->get('grade'));

        if ($question->getTypeQuestion()->getType() != $typeQuestion->getType()) {
        	$question->setTypeQuestion($typeQuestion);
        }

        if (!empty($request->request->get('media'))) {
            $media = new Media();
            $media->setPath($imageService->saveImage($request->request->get('media'), "_question_img.jpeg"));
            $question->setMedia($media);

            $this->em()->persist($media);
        }

        $this->em()->persist($question);

        $this->deleteAllAnswers($question);
        if ((int)$request->request->get('nbAnswers') > 0) {
        	for ($i = 0; $i < (int)$request->request->get('nbAnswers'); $i++) { 
        		$answer = new Answer() ;
        		$answer->setName($request->request->get('name_answer_'.$i));
        		$answer->setIsCorrect(
        			filter_var(
        				$request->request->get('is_correct_answer_'.$i),
        				FILTER_VALIDATE_BOOLEAN
        			)
        		);
        		$answer->setQuestion($question);
        		$this->em()->persist($answer);
        	}
        }

        $this->em()->flush();

        $res = [
            "media" => $question->getMedia() != null ? $question->getMedia()->getPath() : null
        ];
        return new JsonResponse($res, 200);
    }

    /**
     * @Route("/question/delete/{question}", requirements={"question" = "\d+"})
     */
    public function deleteQuestionAction(Request $request, $question)
    {
        $imageService  = $this->get(ImageService::REFERENCE);
        $question      = $this->em()->getRepository('QuizzyBundle:Question')->find($question);

        if ($question->getMedia()) {// on delete l'image si il y a en
            $imageService->deleteImage($question->getMedia());
        }

        $this->em()->remove($question);
        $this->em()->flush();
        return new JsonResponse([], 200);
    }

    private function deleteAllAnswers(Question $question)
    {
    	foreach ($question->getAnswers() as $answer) {
    		$this->em()->remove($answer);
    	}
    }

    private function em()
    {
        return $this->getDoctrine()->getEntityManager();
    }
}
