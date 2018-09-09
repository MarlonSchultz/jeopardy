<?php
/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 09.09.18
 * Time: 18:14
 */

namespace mgbs\PHP\DTO;

use mgbs\DTO\JeopardyItem;
use PHPUnit\Framework\TestCase;

/**
 * @property JeopardyItem jeoPardyItemDTO
 */
class JeopardyItemTest extends TestCase
{

    private $jeoPardyItemDTO;

    public function setup()
    {
        $this->jeoPardyItemDTO = new JeopardyItem(1, 'cat', 2, 'answerstring', 'questionstring');
    }

    public function testGetCategory()
    {
        $this->assertSame('cat', $this->jeoPardyItemDTO->getCategory());
    }

    public function testGetId()
    {
        $this->assertEquals(1, $this->jeoPardyItemDTO->getId());
    }

    public function testGetPoints()
    {
        $this->assertEquals(2, $this->jeoPardyItemDTO->getPoints());

    }

    public function testGetQuestion()
    {
        $this->assertSame('questionstring', $this->jeoPardyItemDTO->getQuestion());

    }

    public function testGetAnswer()
    {
        $this->assertSame('answerstring', $this->jeoPardyItemDTO->getAnswer());

    }
}
