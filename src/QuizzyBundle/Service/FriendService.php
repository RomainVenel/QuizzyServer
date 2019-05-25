<?php

namespace QuizzyBundle\Service;

use QuizzyBundle\Entity\Friend;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Doctrine\ORM\EntityManager;
use QuizzyBundle\Entity\User;

class FriendService
{
    const REFERENCE = "quizzy.friend_service";

    protected $em;

    /**
     * FriendService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function getFriendsByUser(User $user)
    {
        return $this->getFriendRepository()->getFriendsByUser($user);
    }

    /**
     * @param User $currentUser
     * @param User $user
     * @return mixed
     */
    public function getFriends(User $currentUser, User $user)
    {
        return $this->getFriendRepository()->getFriend($currentUser, $user);
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function getFriendsRequestByUser(User $user)
    {
        return $this->getFriendRepository()->getFriendsRequestByUser($user);
    }

    /**
     * @param User $user
     * @param $search
     * @return mixed
     */
    public function getFriendsListCanBeAdd(User $user, $search)
    {
        return $this->getUserRepository()->getFriendsListCanBeAdd($user, $search);
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getFriendRepository()
    {
        return $this->em->getRepository(Friend::REFERENCE);
    }

    private function getUserRepository()
    {
        return $this->em->getRepository(User::REFERENCE);
    }
}
