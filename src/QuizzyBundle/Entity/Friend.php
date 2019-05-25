<?php

namespace QuizzyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="friend_user")
 * @ORM\Entity(repositoryClass="QuizzyBundle\Repository\FriendRepository")
 */
class Friend
{
    const REFERENCE = 'QuizzyBundle:Friend';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="QuizzyBundle\Entity\User", inversedBy="friendByUserSender")
     * @ORM\JoinColumn(name="user_sender", referencedColumnName="id", nullable=false)
     */
    private $user_sender;

    /**
     * @ORM\ManyToOne(targetEntity="QuizzyBundle\Entity\User", inversedBy="friendByUser")
     * @ORM\JoinColumn(name="user", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var bool
     *
     * @ORM\Column(name="accepted", type="boolean")
     */
    private $accepted;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set accepted
     *
     * @param boolean $accepted
     *
     * @return Friend
     */
    public function setAccepted($accepted)
    {
        $this->accepted = $accepted;

        return $this;
    }

    /**
     * Get accepted
     *
     * @return boolean
     */
    public function getAccepted()
    {
        return $this->accepted;
    }

    /**
     * Set userSender
     *
     * @param \QuizzyBundle\Entity\User $userSender
     *
     * @return Friend
     */
    public function setUserSender(\QuizzyBundle\Entity\User $userSender)
    {
        $this->user_sender = $userSender;

        return $this;
    }

    /**
     * Get userSender
     *
     * @return \QuizzyBundle\Entity\User
     */
    public function getUserSender()
    {
        return $this->user_sender;
    }

    /**
     * Set user
     *
     * @param \QuizzyBundle\Entity\User $user
     *
     * @return Friend
     */
    public function setUser(\QuizzyBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \QuizzyBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
