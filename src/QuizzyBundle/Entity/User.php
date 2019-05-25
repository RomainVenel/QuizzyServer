<?php

namespace QuizzyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="QuizzyBundle\Repository\UserRepository")
 */
class User
{
    const REFERENCE = 'QuizzyBundle:User';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birth_date", type="datetime")
     */
    private $birthDate;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="QuizzyBundle\Entity\Media")
     * @ORM\JoinColumn(name="media", referencedColumnName="id")
     */
    private $media;

    /**
     * @ORM\ManyToMany(targetEntity="Quiz")
     * @ORM\JoinTable(name="quiz_shared",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="quiz_id", referencedColumnName="id")}
     *      )
     */
    private $QuizShared;

    /**
     * @var ArrayCollection|Friend[]
     * @ORM\OneToMany(targetEntity="QuizzyBundle\Entity\Friend", mappedBy="user_sender", fetch="EXTRA_LAZY")
     */
    protected $friendByUserSender;

    /**
     * @var ArrayCollection|Friend[]
     * @ORM\OneToMany(targetEntity="QuizzyBundle\Entity\Friend", mappedBy="user", fetch="EXTRA_LAZY")
     */
    protected $friendByUser;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     *
     * @return User
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Set media
     *
     * @param \QuizzyBundle\Entity\Media $media
     *
     * @return User
     */
    public function setMedia(\QuizzyBundle\Entity\Media $media = null)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return \QuizzyBundle\Entity\Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Add quizShared
     *
     * @param \QuizzyBundle\Entity\Quiz $quizShared
     *
     * @return User
     */
    public function addQuizShared(\QuizzyBundle\Entity\Quiz $quizShared)
    {
        $this->QuizShared[] = $quizShared;

        return $this;
    }

    /**
     * Remove quizShared
     *
     * @param \QuizzyBundle\Entity\Quiz $quizShared
     */
    public function removeQuizShared(\QuizzyBundle\Entity\Quiz $quizShared)
    {
        $this->QuizShared->removeElement($quizShared);
    }

    /**
     * Get quizShared
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuizShared()
    {
        return $this->QuizShared;
    }

    /**
     * Add friendByUserSender
     *
     * @param \QuizzyBundle\Entity\Friend $friendByUserSender
     *
     * @return User
     */
    public function addFriendByUserSender(\QuizzyBundle\Entity\Friend $friendByUserSender)
    {
        $this->friendByUserSender[] = $friendByUserSender;

        return $this;
    }

    /**
     * Remove friendByUserSender
     *
     * @param \QuizzyBundle\Entity\Friend $friendByUserSender
     */
    public function removeFriendByUserSender(\QuizzyBundle\Entity\Friend $friendByUserSender)
    {
        $this->friendByUserSender->removeElement($friendByUserSender);
    }

    /**
     * Get friendByUserSender
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFriendByUserSender()
    {
        return $this->friendByUserSender;
    }

    /**
     * Add friendByUser
     *
     * @param \QuizzyBundle\Entity\Friend $friendByUser
     *
     * @return User
     */
    public function addFriendByUser(\QuizzyBundle\Entity\Friend $friendByUser)
    {
        $this->friendByUser[] = $friendByUser;

        return $this;
    }

    /**
     * Remove friendByUser
     *
     * @param \QuizzyBundle\Entity\Friend $friendByUser
     */
    public function removeFriendByUser(\QuizzyBundle\Entity\Friend $friendByUser)
    {
        $this->friendByUser->removeElement($friendByUser);
    }

    /**
     * Get friendByUser
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFriendByUser()
    {
        return $this->friendByUser;
    }
}
