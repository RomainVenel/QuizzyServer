<?php

namespace QuizzyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QuestionCompletion
 *
 * @ORM\Table(name="question_completion")
 * @ORM\Entity(repositoryClass="QuizzyBundle\Repository\QuestionCompletionRepository")
 */
class QuestionCompletion
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
     * @var int
     *
     * @ORM\Column(name="score", type="integer")
     */
    private $score;

    /**
     * @var int
     *
     * @ORM\Column(name="timer", type="integer")
     */
    private $timer;

    /**
     * @ORM\ManyToOne(targetEntity="QuizzyBundle\Entity\PartCompletion")
     * @ORM\JoinColumn(name="part_completion_id", referencedColumnName="id", nullable=false)
     */
    private $partCompletion;

    /**
     * @ORM\ManyToOne(targetEntity="QuizzyBundle\Entity\Question")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id", nullable=false)
     */
    private $question;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updated_at;


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
     * Set score
     *
     * @param integer $score
     *
     * @return QuestionCompletion
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
     * Set timer
     *
     * @param integer $timer
     *
     * @return QuestionCompletion
     */
    public function setTimer($timer)
    {
        $this->timer = $timer;

        return $this;
    }

    /**
     * Get timer
     *
     * @return int
     */
    public function getTimer()
    {
        return $this->timer;
    }

    /**
     * Set partCompletion
     *
     * @param \QuizzyBundle\Entity\PartCompletion $partCompletion
     *
     * @return QuestionCompletion
     */
    public function setPartCompletion(\QuizzyBundle\Entity\PartCompletion $partCompletion)
    {
        $this->partCompletion = $partCompletion;

        return $this;
    }

    /**
     * Get partCompletion
     *
     * @return \QuizzyBundle\Entity\PartCompletion
     */
    public function getPartCompletion()
    {
        return $this->partCompletion;
    }

    /**
     * Set question
     *
     * @param \QuizzyBundle\Entity\Question $question
     *
     * @return QuestionCompletion
     */
    public function setQuestion(\QuizzyBundle\Entity\Question $question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \QuizzyBundle\Entity\Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return QuestionCompletion
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return QuestionCompletion
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
}
