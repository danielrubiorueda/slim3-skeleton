<?php
namespace App\Controller;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

final class HomeController
{
    private $view;
    private $logger;

    public function __construct($container)
    {
        $this->view = $container->get('view');
        $this->logger = $container->get('logger');
    }

    public function getHome(Request $request, Response $response, $args)
    {
        $this->view->render($response, 'home.twig');
        return $response;
    }
}
