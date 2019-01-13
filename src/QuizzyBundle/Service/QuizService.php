<?php

namespace QuizzyBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Doctrine\ORM\EntityManager;
use QuizzyBundle\Entity\User;

class QuizService
{
    const REFERENCE = "quizzy.quiz_service";
    protected $em;

    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }


    public function getAllQuizFinished(User $user) 
    {
        return $this->getUserRepository()->getAllQuizCreated($user); 
    }

    private function getUserRepository()
    {
        return $this->em->getRepository('QuizzyBundle:Quiz');
    }
}
