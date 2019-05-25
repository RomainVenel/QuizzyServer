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
use QuizzyBundle\Entity\Answer;
use QuizzyBundle\Service\ImageService;
class QuestionCompletionController extends Controller
{
	
	/**
     * @Route("/partCompletion/{partCompletion}/questionCompletion/new", requirements={"partCompletion" = "\d+"})
     */
    public function newQuestionCompletionAction(Request $request, $partCompletion, $question)
    {
        $partCompletion = $this->em()->getRepository('QuizzyBundle:PartCompletion')->find($partCompletion);

        $questionCompletion = new QuestionCompletion();
        $questionCompletion->setScore($request->request->get('score'));
        $questionCompletion->setPartCompletion($partCompletion);
        $questionCompletion->setQuestion($question);

        $this->em()->persist($questionCompletion);

        $this->em()->flush();

        $res = [
            "id" => $questionCompletion->getId()
            "score" => $questionCompletion->getScore();
        ];
        return new JsonResponse($res, 200);
    }
}