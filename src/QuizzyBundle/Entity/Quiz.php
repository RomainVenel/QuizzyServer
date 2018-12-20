<?php

namespace QuizzyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Quiz
 *
 * @ORM\Table(name="quiz")
 * @ORM\Entity(repositoryClass="QuizzyBundle\Repository\QuizRepository")
 */
class Quiz
{
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="is_validated", type="datetime", nullable=true)
     */
    private $isValidated;

    /**
     * @var float
     *
     * @ORM\Column(name="popularity", type="float", nullable=true)
     */
    private $popularity;

    /**
     * @ORM\ManyToOne(targetEntity="QuizzyBundle\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="QuizzyBundle\Entity\Media")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true)
     */
    private $media;

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
     * Set name
     *
     * @param string $name
     *
     * @return Quiz
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Set isValidated
     *
     * @param \DateTime $isValidated
     *
     * @return Quiz
     */
    public function setIsValidated($isValidated)
    {
        $this->isValidated = $isValidated;

        return $this;
    }

    /**
     * Get isValidated
     *
     * @return \DateTime
     */
    public function getIsValidated()
    {
        return $this->isValidated;
    }

    /**
     * Set popularity
     *
     * @param float $popularity
     *
     * @return Quiz
     */
    public function setPopularity($popularity)
    {
        $this->popularity = $popularity;

        return $this;
    }

    /**
     * Get popularity
     *
     * @return float
     */
    public function getPopularity()
    {
        return $this->popularity;
    }

    /**
     * Set user
     *
     * @param \QuizzyBundle\Entity\User $user
     *
     * @return Quiz
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

    /**
     * Set media
     *
     * @param \QuizzyBundle\Entity\Media $media
     *
     * @return Quiz
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

}
