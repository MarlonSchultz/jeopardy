<?php
declare(strict_types=1);

namespace mgbs\Controller;

use http\Env\Request;
use mgbs\Library\DITrait;
use mgbs\Model\PlayerAnswerModel;
use Symfony\Component\HttpFoundation\Response;

/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 16.01.17
 * Time: 19:37
 */
class RestController
{
    use DITrait;

    /**
     * @var PlayerAnswerModel
     */
    private $playeranswermodel;

    public function __construct()
    {
        $this->playeranswermodel = $this->getService('playeranswermodel');
    }

    /**
     * @return Response
     */
    public function buzzerAction($buzzer): Response
    {
        $this->playeranswermodel->insertBuzz((int) $buzzer);
        return new Response('KotRoller aufgerufen . ');
    }


}
