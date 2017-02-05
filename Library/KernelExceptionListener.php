<?php
/**
 * @author dknx01 <e.witthauer@gmail.com>
 * @since 29.01.17 21:43
 */

namespace mgbs\Library;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class KernelExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getException();

        // Customize your response object to display the exception details
        $response = new Response();
        /** @var \Twig_Environment $twig */
        $twig = DI::getContainer()->get('twig');
        $twig->enableDebug();
        $content = $twig->render('exception.html.twig', array('exception' => $exception));
        $response->setContent($content);

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Send the modified response object to the event
        $event->setResponse($response);
    }
}