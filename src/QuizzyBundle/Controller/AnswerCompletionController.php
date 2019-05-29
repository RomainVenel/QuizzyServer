<?php
namespace QuizzyBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use QuizzyBundle\Entity\Media;
use QuizzyBundle\Entity\Question;
use QuizzyBundle\Entity\PartCompletion;
use QuizzyBundle\Entity\QuestionCompletion;
use QuizzyBundle\Entity\AnswerCompletion;
use QuizzyBundle\Entity\Answer;
use QuizzyBundle\Service\ImageService;
class AnswerCompletionController extends Controller
{
	
	/**
     * @Route("/questionCompletion/{questionCompletion}/answer/{answer}/answerCompletion/new", requirements={"questionCompletion" = "\d+"})
     */
    public function newAnswerCompletionAction(Request $request, $questionCompletion, $answer)
    {
        $questionCompletion = $this->em()->getRepository('QuizzyBundle:QuestionCompletion')->find($questionCompletion);
        $answer = $this->em()->getRepository('QuizzyBundle:Answer')->find($answer);

        $answerCompletion = new AnswerCompletion();
        $answerCompletion->setQuestionCompletion($questionCompletion);
        $answerCompletion->setAnswer($answer);

        $this->em()->persist($answerCompletion);

        $this->em()->flush();

        $tabAc = [];
        $tabAc['id']       = $answerCompletion->getId();
        $tabAc['qc']       = $answerCompletion->getQuestionCompletion()->getId();
        $tabAc['answer'] = $answerCompletion->getAnswer()->getId();

        $res = [
            "qc" => $tabAc
        ];
        return new JsonResponse($res, 200);
    }

    private function em()
    {
        return $this->getDoctrine()->getEntityManager();
    }
}