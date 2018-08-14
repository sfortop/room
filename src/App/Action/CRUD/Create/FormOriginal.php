<?php
/**
 * Copyright Serhii Borodai (c) 2017-2018.
 */

/**
 * Created by Serhii Borodai <clarifying@gmail.com>
 */

namespace App\Action\CRUD\Create;


use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\ServerRequest;
use Zend\View\Renderer\RendererInterface;

/**
 * Class FormOriginal
 * @package App\Action\Create
 * @author Serhii Borodai <clarifying@gmail.com>\
 * @deprecated use realizations with `correct` in names
 */
class FormOriginal
{

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * Create constructor.
     * @param RendererInterface $renderer
     */
    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function handle(ServerRequest $request)
    {
        //@todo add CSRF token

        $content = $this->renderer->render('templates/create/form-original.phtml', ['routeNameSpace' => $this->getRouteNameSpace()]);
        $layout = $this->renderer->render('templates/index.phtml', ['content' => $content, 'routeNameSpace' => $this->getRouteNameSpace()]);

        return new HtmlResponse($layout);
    }

    function getRouteNameSpace(): string
    {
        return 'price-interval';
    }
}