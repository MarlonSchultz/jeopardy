<?php
/**
 * @author dknx01 <e.witthauer@gmail.com>
 * @since 29.01.17 22:12
 */

namespace mgbs\Library;

trait DITrait
{
    /**
     * @param string $name
     * @return object
     */
    private function getService($name)
    {
        return DI::getContainer()->get($name);
    }
    /**
     * @param string $name
     * @return object
     */
    private function getParameter($name)
    {
        return DI::getContainer()->getParameter($name);
    }
}