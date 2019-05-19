<?php

namespace QuizzyBundle\Repository;

use Doctrine\ORM\EntityRepository;
use QuizzyBundle\Entity\User;

class QuizRepository extends EntityRepository
{
    /**
     * @param User $user
     * @return array
     */
	public function getAllQuizCreated(User $user)
    {
        $dql = "
            SELECT q
            FROM QuizzyBundle:Quiz q
            WHERE q.user = :user
            AND q.isValidated IS NOT NULL 
        ";

        $query = $this->_em->createQuery($dql);
        $query->setParameter('user', $user);
        return $query->getResult();
    }

    /**
     * @param User $user
     * @return array
     */
    public function getQuizNotFinished(User $user)
    {
        $dql = "
            SELECT q
            FROM QuizzyBundle:Quiz q
            WHERE q.user = :user
            AND q.isValidated IS NULL 
        ";

        $query = $this->_em->createQuery($dql);
        $query->setParameter('user', $user);
        return $query->getResult();
    }
}
