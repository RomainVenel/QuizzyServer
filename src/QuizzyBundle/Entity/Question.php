<?php

namespace QuizzyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="QuizzyBundle\Repository\QuestionRepository")
 */
class Question
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
     * @var int
     *
     * @ORM\Column(name="max_score", type="integer")
     */
    private $maxScore;

    /**
     * @ORM\ManyToOne(targetEntity="QuizzyBundle\Entity\Part")
     * @ORM\JoinColumn(name="part_id", referencedColumnName="id", nullable=false)
     */
    private $part;

    /**
     * @ORM\ManyToOne(targetEntity="QuizzyBundle\Entity\TypeQuestion")
     * @ORM\JoinColumn(name="type_question_id", referencedColumnName="id", nullable=false)
     */
    private $typeQuestion;

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
     * @return Question
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
     * Set maxScore
     *
     * @param integer $maxScore
     *
     * @return Question
     */
    public function setMaxScore($maxScore)
    {
        $this->maxScore = $maxScore;

        return $this;
    }

    /**
     * Get maxScore
     *
     * @return int
     */
    public function getMaxScore()
    {
        return $this->maxScore;
    }

    /**
     * Set part
     *
     * @param \QuizzyBundle\Entity\Part $part
     *
     * @return Question
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
     * Set typeQuestion
     *
     * @param \QuizzyBundle\Entity\TypeQuestion $typeQuestion
     *
     * @return Question
     */
    public function setTypeQuestion(\QuizzyBundle\Entity\TypeQuestion $typeQuestion)
    {
        $this->typeQuestion = $typeQuestion;

        return $this;
    }

    /**
     * Get typeQuestion
     *
     * @return \QuizzyBundle\Entity\TypeQuestion
     */
    public function getTypeQuestion()
    {
        return $this->typeQuestion;
    }

    /**
     * Set media
     *
     * @param \QuizzyBundle\Entity\Media $media
     *
     * @return Question
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
