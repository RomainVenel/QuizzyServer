<?php

namespace QuizzyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QuizCompletion
 *
 * @ORM\Table(name="quiz_completion")
 * @ORM\Entity(repositoryClass="QuizzyBundle\Repository\QuizCompletionRepository")
 */
class QuizCompletion
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
     * @ORM\ManyToOne(targetEntity="QuizzyBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="QuizzyBundle\Entity\Quiz")
     * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id", nullable=false)
     */
    private $quiz;

    /**
     * @var int
     *
     * @ORM\Column(name="score", type="integer", nullable=true)
     */
    private $score;

    /**
     * @var ArrayCollection|PartCompletion[]
     * @ORM\OneToMany(targetEntity="QuizzyBundle\Entity\PartCompletion", mappedBy="quizCompletion", fetch="EXTRA_LAZY")
     */
    protected $partsCompletion;

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
     * Set user
     *
     * @param \QuizzyBundle\Entity\User $user
     *
     * @return QuizCompletion
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
     * Set score
     *
     * @param integer $score
     *
     * @return QuizCompletion
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set quiz
     *
     * @param \QuizzyBundle\Entity\Quiz $quiz
     *
     * @return QuizCompletion
     */
    public function setQuiz(\QuizzyBundle\Entity\Quiz $quiz)
    {
        $this->quiz = $quiz;

        return $this;
    }

    /**
     * Get quiz
     *
     * @return \QuizzyBundle\Entity\Quiz
     */
    public function getQuiz()
    {
        return $this->quiz;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->partsCompletion = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add partsCompletion
     *
     * @param \QuizzyBundle\Entity\PartCompletion $partsCompletion
     *
     * @return QuizCompletion
     */
    public function addPartsCompletion(\QuizzyBundle\Entity\PartCompletion $partsCompletion)
    {
        $this->partsCompletion[] = $partsCompletion;

        return $this;
    }

    /**
     * Remove partsCompletion
     *
     * @param \QuizzyBundle\Entity\PartCompletion $partsCompletion
     */
    public function removePartsCompletion(\QuizzyBundle\Entity\PartCompletion $partsCompletion)
    {
        $this->partsCompletion->removeElement($partsCompletion);
    }

    /**
     * Get partsCompletion
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPartsCompletion()
    {
        return $this->partsCompletion;
    }
}
