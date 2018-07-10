<?php
declare(strict_types = 1);

namespace mgbs\ValueObject;

/**
 * @author dknx01 <e.witthauer@gmail.com>
 * @since 20.01.17 21:44
 */
class JeopardyItem
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $question = '';

    /**
     * @var string
     */
    private $answer = '';

    /**
     * @var string
     */
    private $category = '';

    /**
     * @var int
     */
    private $value = 0;

    /**
     * JeopardyItem constructor.
     * @param string $question
     * @param string $answer
     * @param string $category
     * @param int $value
     */
    public function __construct($question, $answer, $category, $value)
    {
        $this->question = $question;
        $this->answer = $answer;
        $this->category = $category;
        $this->value = $value;
        $this->id = uniqid();
    }

    /**
     * @return string
     */
    public function getId() :string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getQuestion(): string
    {
        return $this->question;
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
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }
}
