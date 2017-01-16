<?php
declare(strict_types = 1);
use Symfony\Component\Routing\Annotation;
/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 16.01.17
 * Time: 19:37
 */
class IndexController
{
    /**
     * @Annotation\Route('/', name="home")
     */
    public function indexAction()
    {

    }
}