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
        $quiz = $this->em()->getRepository('QuizzyBundle:Quiz')->find($quiz);

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

    /**
     * @Route("/{user}/{quiz}/quizCompletion/get", requirements={"user" = "\d+"})
     */
    public function getQuizCompletionAction(Request $request, $user, $quiz)
    {

        $user     = $this->em()->getRepository('QuizzyBundle:User')->find($user);
        $quiz = $this->em()->getRepository('QuizzyBundle:Quiz')->find($quiz);
        $qc = $this->em()->getRepository('QuizzyBundle:QuizCompletion')->findOneBy([
            'user' => $user,
            'quiz' => $quiz,
        ]);

        $tabQc = [];
        $tabQc['id']   = $qc->getId();
        $tabQc['user'] = $qc->getUser()->getId();
        $tabQc['quiz'] = $qc->getQuiz()->getId();

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