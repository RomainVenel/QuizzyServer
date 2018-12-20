<?php

namespace QuizzyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnswerCompletion
 *
 * @ORM\Table(name="answer_completion")
 * @ORM\Entity(repositoryClass="QuizzyBundle\Repository\AnswerCompletionRepository")
 */
class AnswerCompletion
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
     * @ORM\ManyToOne(targetEntity="QuizzyBundle\Entity\QuestionCompletion")
     * @ORM\JoinColumn(name="question_completion_id", referencedColumnName="id", nullable=false)
     */
    private $questionCompletion;

    /**
     * @ORM\ManyToOne(targetEntity="QuizzyBundle\Entity\Answer")
     * @ORM\JoinColumn(name="answer_id", referencedColumnName="id", nullable=false)
     */
    private $answer;

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
     * Set questionCompletion
     *
     * @param \QuizzyBundle\Entity\QuestionCompletion $questionCompletion
     *
     * @return AnswerCompletion
     */
    public function setQuestionCompletion(\QuizzyBundle\Entity\QuestionCompletion $questionCompletion)
    {
        $this->questionCompletion = $questionCompletion;

        return $this;
    }

    /**
     * Get questionCompletion
     *
     * @return \QuizzyBundle\Entity\QuestionCompletion
     */
    public function getQuestionCompletion()
    {
        return $this->questionCompletion;
    }

    /**
     * Set answer
     *
     * @param \QuizzyBundle\Entity\Answer $answer
     *
     * @return AnswerCompletion
     */
    public function setAnswer(\QuizzyBundle\Entity\Answer $answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return \QuizzyBundle\Entity\Answer
     */
    public function getAnswer()
    {
        return $this->answer;
    }

}
