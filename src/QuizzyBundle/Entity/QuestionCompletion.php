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
     * @ORM\Column(name="timer", type="integer", nullable=true)
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
     * @var ArrayCollection|AnswerCompletion[]
     * @ORM\OneToMany(targetEntity="QuizzyBundle\Entity\AnswerCompletion", mappedBy="questionCompletion", fetch="EXTRA_LAZY")
     */
    protected $answersCompletion;

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
     * Constructor
     */
    public function __construct()
    {
        $this->answersCompletion = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add answersCompletion
     *
     * @param \QuizzyBundle\Entity\AnswerCompletion $answersCompletion
     *
     * @return QuestionCompletion
     */
    public function addAnswersCompletion(\QuizzyBundle\Entity\AnswerCompletion $answersCompletion)
    {
        $this->answersCompletion[] = $answersCompletion;

        return $this;
    }

    /**
     * Remove answersCompletion
     *
     * @param \QuizzyBundle\Entity\AnswerCompletion $answersCompletion
     */
    public function removeAnswersCompletion(\QuizzyBundle\Entity\AnswerCompletion $answersCompletion)
    {
        $this->answersCompletion->removeElement($answersCompletion);
    }

    /**
     * Get answersCompletion
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnswersCompletion()
    {
        return $this->answersCompletion;
    }
}
