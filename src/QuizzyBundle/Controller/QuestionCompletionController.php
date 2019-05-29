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
     * @Route("/partCompletion/{partCompletion}/question/{question}/score/{score}/questionCompletion/new", requirements={"partCompletion" = "\d+"})
     */
    public function newQuestionCompletionAction(Request $request, $partCompletion, $question, $score)
    {
        $partCompletion = $this->em()->getRepository('QuizzyBundle:PartCompletion')->find($partCompletion);
        $question = $this->em()->getRepository('QuizzyBundle:Question')->find($question);

        $questionCompletion = new QuestionCompletion();
        $questionCompletion->setScore($score);
        $questionCompletion->setPartCompletion($partCompletion);
        $questionCompletion->setQuestion($question);

        $this->em()->persist($questionCompletion);

        $this->em()->flush();

        $tabQc = [];
        $tabQc['id']       = $questionCompletion->getId();
        $tabQc['score']    = $questionCompletion->getScore();
        $tabQc['pc']       = $questionCompletion->getPartCompletion()->getId();
        $tabQc['question'] = $questionCompletion->getQuestion()->getId();

        $res = [
            "qc" => $tabQc
        ];
        return new JsonResponse($res, 200);
    }

    private function em()
    {
        return $this->getDoctrine()->getEntityManager();
    }
}