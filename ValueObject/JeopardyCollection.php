<?php

namespace mgbs\ValueObject;

/**
 * @author dknx01 <e.witthauer@gmail.com>
 * @since 21.01.17 11:17
 */
class JeopardyCollection implements \Countable, \IteratorAggregate
{
    /**
     * @var array
     */
    private $elements = array();

    /**
     * @param int $value
     * @param JeopardyRowCollection $items
     * @return JeopardyCollection
     */
    public function addElement($value, JeopardyRowCollection $items) :JeopardyCollection
    {
        $this->elements[$value] = $items;
        return $this;
    }

    /**
     * @param $value
     * @return JeopardyRowCollection
     * @throws \InvalidArgumentException
     */
    public function getElementsByValue($value) :JeopardyRowCollection
    {
        if (array_key_exists($value, $this->elements)) {
            return $this->elements[$value];
        }
        throw new \InvalidArgumentException('No elements for ' . $value . ' where added');
    }

    /**
     * @inheritdoc
     */
    public function getIterator() :\ArrayIterator
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * @inheritdoc
     */
    public function count() :int
    {
        return count($this->elements);
    }

    public function getAllCategories() :array
    {
        return current($this->elements)->getAllCategories();
    }
}