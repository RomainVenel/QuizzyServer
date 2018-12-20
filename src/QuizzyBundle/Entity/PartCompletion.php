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

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return PartCompletion
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
     * @return PartCompletion
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
