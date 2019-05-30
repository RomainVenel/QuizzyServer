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
     * @Route("/questionCompletion/{questionCompletionGiven}/answer/{answerGiven}/answerCompletion/new", requirements={"questionCompletionGiven" = "\d+"})
     */
    public function newAnswerCompletionAction(Request $request, $questionCompletionGiven, $answerGiven)
    {

        $questionCompletionGiven = $this->em()->getRepository('QuizzyBundle:QuestionCompletion')->find($questionCompletionGiven);

        $answerGiven = $this->em()->getRepository('QuizzyBundle:Answer')->find($answerGiven);
        
        $qc = $this->em()->getRepository('QuizzyBundle:QuestionCompletion')->findOneBy([
            'partCompletion' => $questionCompletionGiven->getPartCompletion(),
            'question' => $answerGiven->getQuestion(),
        ]);

        $ac = $this->em()->getRepository('QuizzyBundle:AnswerCompletion')->findOneBy([
            'questionCompletion' => $qc
        ]);

        if (isset($ac)) {
            if (isset($qc)) {
                $this->em()->remove($ac);
                $this->em()->remove($qc);
            }else {
                $this->em()->remove($ac);
            }
        }

        $this->em()->flush();

        $answerGiven = $this->em()->getRepository('QuizzyBundle:Answer')->find($answerGiven);

        $questionCompletion = new QuestionCompletion();
        $questionCompletion->setPartCompletion($questionCompletionGiven->getPartCompletion());
        $questionCompletion->setQuestion($answerGiven->getQuestion());

        $answerCompletion = new AnswerCompletion();
        $answerCompletion->setQuestionCompletion($questionCompletionGiven);
        $answerCompletion->setAnswer($answerGiven);

        $this->em()->persist($answerCompletion);

        $this->em()->flush();

        $tabAc = [];
        $tabAc['id']       = $answerCompletion->getId();
        $tabAc['qc']       = $answerCompletion->getQuestionCompletion()->getId();
        $tabAc['answer']   = $answerCompletion->getAnswer()->getId();

        $res = [
            "ac" => $tabAc
        ];
        return new JsonResponse($res, 200);
    }

    /**
     * @Route("/questionCompletion/{questionCompletion}/answer/{answer}/score/{score}/answerCompletion/setScore", requirements={"questionCompletion" = "\d+"})
     */
    public function setScoreForAnswerCompletionAction(Request $request, $questionCompletion, $answer, $score)
    {

        $questionCompletion     = $this->em()->getRepository('QuizzyBundle:QuestionCompletion')->find($questionCompletion);
        $answer = $this->em()->getRepository('QuizzyBundle:Answer')->find($answer);
        $ac = $this->em()->getRepository('QuizzyBundle:AnswerCompletion')->findOneBy([
            'questionCompletion' => $questionCompletion,
            'answer' => $answer,
        ]);

        $ac->setScore($score);

        $this->em()->persist($ac);

        $this->em()->flush();
        
        $tabAc = [];
        $tabAc['id']    = $ac->getId();
        $tabAc['qc']  = $ac->getQuestionCompletion()->getId();
        $tabAc['answer']  = $ac->getAnswer()->getId();
        $tabAc['score'] = $ac->getScore();

        $res = [
            "ac" => $tabAc
        ];

        return new JsonResponse($res, 200);

    }

    private function em()
    {
        return $this->getDoctrine()->getEntityManager();
    }
}