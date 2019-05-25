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
use QuizzyBundle\Entity\QuizCompletion;
use QuizzyBundle\Entity\Answer;
use QuizzyBundle\Service\ImageService;
class PartCompletionController extends Controller
{
	
	/**
     * @Route("/quizCompletion/{quizCompletion}/partCompletion/new", requirements={"quizCompletion" = "\d+"})
     */
    public function newPartCompletionAction(Request $request, $quizCompletion, $part)
    {
        $quizCompletion     = $this->em()->getRepository('QuizzyBundle:QuizCompletion')->find($quizCompletion);
        $part = $this->em()->getRepository('QuizzyBundle:Part')->find($part);

        $partCompletion = new PartCompletion();
        $partCompletion->setQuizCompletion($quizCompletion);
        $partCompletion->setPart($part);

        $this->em()->persist($partCompletion);

        $this->em()->flush();

        $res = [
            "id" => $partCompletion->getId()
        ];
        return new JsonResponse($res, 200);
    }
}