<?php

namespace QuizzyBundle\Repository;

use Doctrine\ORM\EntityRepository;
use QuizzyBundle\Entity\User;

class QuizRepository extends EntityRepository
{

	public function getAllQuizCreated(User $user)
    {
        $dql = "
            SELECT q
            FROM QuizzyBundle:Quiz q
            WHERE q.user = :user
            AND q.isValidated IS NOT NULL 
        ";

        $params = [
            "user" => $user,
        ];

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameters($params);

        return $query->getResult();
    }
}
