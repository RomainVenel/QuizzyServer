<?php

namespace QuizzyBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Doctrine\ORM\EntityManager;
use QuizzyBundle\Entity\User;

class QuizService
{
    const REFERENCE = "quizzy.quiz_service";
    protected $em;

    /**
     * QuizService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function getAllQuizFinished(User $user) 
    {
        return $this->getUserRepository()->getAllQuizCreated($user); 
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function getQuizNotFinished(User $user)
    {
        return $this->getUserRepository()->getQuizNotFinished($user);
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getUserRepository()
    {
        return $this->em->getRepository('QuizzyBundle:Quiz');
    }
}
