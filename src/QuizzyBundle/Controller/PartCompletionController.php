<?php
namespace QuizzyBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use QuizzyBundle\Entity\Media;
use QuizzyBundle\Entity\Question;
use QuizzyBundle\Entity\Part;
use QuizzyBundle\Entity\PartCompletion;
use QuizzyBundle\Entity\QuizCompletion;
use QuizzyBundle\Entity\Answer;
use QuizzyBundle\Service\ImageService;
class PartCompletionController extends Controller
{
	
	/**
     * @Route("/{part}/{quizCompletion}/partCompletion/new", requirements={"part" = "\d+"})
     */
    public function newPartCompletionAction(Request $request, $part, $quizCompletion)
    {

        $part           = $this->em()->getRepository('QuizzyBundle:Part')->find($part);
        $quizCompletion = $this->em()->getRepository('QuizzyBundle:QuizCompletion')->find($quizCompletion);

        $partCompletion = new PartCompletion();
        $partCompletion->setPart($part);
        $partCompletion->setQuizCompletion($quizCompletion);

        $this->em()->persist($partCompletion);

        $this->em()->flush();

        $res = [
            "id" => $partCompletion->getId()
        ];
        return new JsonResponse($res, 200);
    }

    private function em()
    {
        return $this->getDoctrine()->getEntityManager();
    }
}