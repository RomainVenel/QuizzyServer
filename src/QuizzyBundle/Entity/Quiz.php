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
    const REFERENCE = 'QuizzyBundle:Quiz';

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
     * @var ArrayCollection|Part[]
     * @ORM\OneToMany(targetEntity="QuizzyBundle\Entity\Part", mappedBy="quiz", fetch="EXTRA_LAZY")
     */
    protected $parts;

    /**
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="quiz_shared",
     *      joinColumns={@ORM\JoinColumn(name="quiz_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *      )
     */
    private $UserShared;

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

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->parts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add part
     *
     * @param \QuizzyBundle\Entity\Part $part
     *
     * @return Quiz
     */
    public function addPart(\QuizzyBundle\Entity\Part $part)
    {
        $this->parts[] = $part;

        return $this;
    }

    /**
     * Remove part
     *
     * @param \QuizzyBundle\Entity\Part $part
     */
    public function removePart(\QuizzyBundle\Entity\Part $part)
    {
        $this->parts->removeElement($part);
    }

    /**
     * Get parts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParts()
    {
        return $this->parts;
    }

    /**
     * Add userShared
     *
     * @param \QuizzyBundle\Entity\User $userShared
     *
     * @return Quiz
     */
    public function addUserShared(\QuizzyBundle\Entity\User $userShared)
    {
        $this->UserShared[] = $userShared;

        return $this;
    }

    /**
     * Remove userShared
     *
     * @param \QuizzyBundle\Entity\User $userShared
     */
    public function removeUserShared(\QuizzyBundle\Entity\User $userShared)
    {
        $this->UserShared->removeElement($userShared);
    }

    /**
     * Get userShared
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserShared()
    {
        return $this->UserShared;
    }
}
