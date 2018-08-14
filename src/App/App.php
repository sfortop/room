<?php
/**
 * Copyright Serhii Borodai (c) 2017-2018.
 */

/**
 * Created by Serhii Borodai <clarifying@gmail.com>
 */

namespace App;

use FastRoute;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\ServerRequestFactory;

class App
{
    const ROUTE_DISPATCHER = 'route.dispatcher';

    private $router;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->router = $container->get(self::ROUTE_DISPATCHER);
        $this->container = $container;
    }

    public function setErrorHandler()
    {
        set_error_handler(function ($errno, $errstr, $errfile, $errline ) {
            if (error_reporting()) {
                throw new \Exception(sprintf('%s:%s %s',$errfile, $errline, $errstr), $errno);
            } else {
                throw new \Exception('no reporting');
            }
        });
    }

    public function run()
    {

        $this->setErrorHandler();

        // Fetch method and URI from somewhere
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $this->router->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                $result = new HtmlResponse(sprintf('method not allowed. List of allowed methods %s', implode(',', $allowedMethods)));
                break;
            case FastRoute\Dispatcher::FOUND:
                $handlerName = $routeInfo[1];
                $handler = $this->container->get($handlerName);

                //@todo add type hinting check HandlerInterface
                $request = ServerRequestFactory::fromGlobals();

                $request = $request->withAttribute('routeParams', $routeInfo[2]);
                //@todo add try/catch and error/exception handler e.g filp/whoops or something other
                $result = $handler->handle($request);
                break;
            case FastRoute\Dispatcher::NOT_FOUND:
                $result = new \Zend\Diactoros\Response\EmptyResponse(404);
                break;
            default:
                $result = 'Undefined behavior';
                break;
        }
        return $result;
    }

}