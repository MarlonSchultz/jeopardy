<?php

/**
 * @author dknx01 <e.witthauer@gmail.com>
 * @since 20.01.17 21:44
 */
class JeopardyCollection implements Countable, IteratorAggregate, ArrayAccess
{
    /**
     * @var array
     */
    private $elements = array();

    /**
     * @param array $elements
     */
    public function __construct(array $elements = array())
    {
        $this->elements = $elements;
    }


    /**
     * Applies the given function to each element in the collection and returns
     * a new collection with the elements returned by the function.
     *
     * @param Closure $func
     *
     * @return JeopardyCollection
     */
    public function map(Closure $func)
    {
        return new static(array_map($func, $this->elements));
    }

    /**
     * Returns all the elements of this collection that satisfy the predicate p.
     * The order of the elements is preserved.
     *
     * @param Closure $p The predicate used for filtering.
     *
     * @return JeopardyCollection A collection with the results of the filter operation.
     */
    public function filter(Closure $p)
    {
        return new static(array_filter($this->elements, $p));
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return count($this->elements);
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        return new ArrayIterator($this->elements);
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return $this->containsKey($offset);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        if (!isset($offset)) {
            $this->add($value);
        }

        $this->set($offset, $value);
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        unset($this->elements[$offset]);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return serialize($this->toArray());
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->elements;
    }

    /**
     * Checks whether the collection contains an element with the specified key/index.
     *
     * @param string|integer $offset The key/index to check for.
     *
     * @return boolean TRUE if the collection contains an element with the specified key/index,
     *                 FALSE otherwise.
     */
    private function containsKey($offset)
    {
        return isset($this->elements[$offset]) || array_key_exists($offset, $this->elements);
    }

    /**
     * Gets the element at the specified key/index.
     *
     * @param string|integer $key The key/index of the element to retrieve.
     *
     * @return mixed
     */
    private function get($key)
    {
        return isset($this->elements[$key]) ? $this->elements[$key] : null;
    }

    /**
     * Adds an element at the end of the collection.
     *
     * @param mixed $value The element to add.
     *
     * @return boolean Always TRUE.
     */
    private function add($value)
    {
        $this->elements[] = $value;

        return true;
    }

    /**
     * Sets an element in the collection at the specified key/index.
     *
     * @param string|integer $offset   The key/index of the element to set.
     * @param mixed          $value The element to set.
     *
     */
    private function set($offset, $value)
    {
        $this->elements[$offset] = $value;
    }
}