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
        	for ($i=0; $i < (int)$request->request->get('nbAnswers') ; $i++) { 
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

    private function em()
    {
        return $this->getDoctrine()->getEntityManager();
    }
}
