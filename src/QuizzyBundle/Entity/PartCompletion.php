<?php

namespace QuizzyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PartCompletion
 *
 * @ORM\Table(name="part_completion")
 * @ORM\Entity(repositoryClass="QuizzyBundle\Repository\PartCompletionRepository")
 */
class PartCompletion
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
     * @ORM\ManyToOne(targetEntity="QuizzyBundle\Entity\QuizCompletion")
     * @ORM\JoinColumn(name="quiz_completion_id", referencedColumnName="id", nullable=false)
     */
    private $quizCompletion;

    /**
     * @ORM\ManyToOne(targetEntity="QuizzyBundle\Entity\Part")
     * @ORM\JoinColumn(name="part_id", referencedColumnName="id", nullable=false)
     */
    private $part;

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
     * Set quizCompletion
     *
     * @param \QuizzyBundle\Entity\QuizCompletion $quizCompletion
     *
     * @return PartCompletion
     */
    public function setQuizCompletion(\QuizzyBundle\Entity\QuizCompletion $quizCompletion)
    {
        $this->quizCompletion = $quizCompletion;

        return $this;
    }

    /**
     * Get quizCompletion
     *
     * @return \QuizzyBundle\Entity\QuizCompletion
     */
    public function getQuizCompletion()
    {
        return $this->quizCompletion;
    }

    /**
     * Set part
     *
     * @param \QuizzyBundle\Entity\Part $part
     *
     * @return PartCompletion
     */
    public function setPart(\QuizzyBundle\Entity\Part $part)
    {
        $this->part = $part;

        return $this;
    }

    /**
     * Get part
     *
     * @return \QuizzyBundle\Entity\Part
     */
    public function getPart()
    {
        return $this->part;
    }

}
