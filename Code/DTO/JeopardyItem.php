<?php

namespace mgbs\DTO;

/**
 * @author dknx01 <e.witthauer@gmail.com>
 * @since 30.01.17 20:10
 */
class JeopardyItem
{
    /** @var  int */
    private $id;

    /**
     * @var string
     */
    private $category;

    /**
     * @var int
     */
    private $points;

    /**
     * @var string
     */
    private $answer;

    /**
     * @var string
     */
    private $question;

    /**
     * JeopardyItem constructor.
     * @param int $id
     * @param string $category
     * @param int $points
     * @param string $answer
     * @param string $question
     */
    public function __construct($id, $category, $points, $answer, $question)
    {
        $this->id = $id;
        $this->category = $category;
        $this->points = $points;
        $this->answer = $answer;
        $this->question = $question;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @return int
     */
    public function getPoints(): int
    {
        return $this->points;
    }

    /**
     * @return string
     */
    public function getAnswer(): string
    {
        return $this->answer;
    }

    /**
     * @return string
     */
    public function getQuestion(): string
    {
        return $this->question;
    }
}
