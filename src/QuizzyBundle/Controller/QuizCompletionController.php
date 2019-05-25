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
use QuizzyBundle\Entity\User;
class QuizCompletionController extends Controller
{
	
	/**
     * @Route("/{user}/{quiz}/quizCompletion/new", requirements={"user" = "\d+"})
     */
    public function newQuizCompletionAction(Request $request, $user, $quiz)
    {

        $user     = $this->em()->getRepository('QuizzyBundle:User')->find($user);
        $question = $this->em()->getRepository('QuizzyBundle:Question')->find($question);

        $quizCompletion = new QuizCompletion();
        $quizCompletion->setUser($user);
        $quizCompletion->setQuiz($quiz);

        $this->em()->persist($quizCompletion);

        $this->em()->flush();

        $res = [
            "id" => $quizCompletion->getId()
        ];
        return new JsonResponse($res, 200);
    }
}