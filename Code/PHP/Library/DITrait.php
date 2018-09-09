<?php
declare(strict_types = 1);
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
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     */
    private function getService($name)
    {
        return DI::getContainer()->get($name);
    }

    /**
     * @param string $name
     * @return object
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    private function getParameter($name)
    {
        return DI::getContainer()->getParameter($name);
    }
}
