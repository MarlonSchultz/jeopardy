<?php
declare(strict_types = 1);
namespace mgbs\Controller;

use mgbs\Model\ModelAbstract;
use Symfony\Component\HttpFoundation\Response;

/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 16.01.17
 * Time: 19:37
 */
class IndexController
{
    /**
     * @var ModelAbstract
     */
    private $questionsModel;

    public function __construct($questionsModel)
    {
        $this->questionsModel = $questionsModel;
    }

    public function indexAction()
    {

        return new Response('basics are set');
    }
}