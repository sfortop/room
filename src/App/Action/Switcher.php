<?php
/**
 * Copyright Serhii Borodai (c) 2017-2018.
 */

/**
 * Created by Serhii Borodai <clarifying@gmail.com>
 */

namespace App\Action;


use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\ServerRequest;
use Zend\View\Renderer\RendererInterface;

class Switcher implements HandlerInterface
{
    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * Switcher constructor.
     * @param RendererInterface $renderer
     */
    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function handle(ServerRequest $request)
    {
        $navbar = $this->renderer->render('templates/switcher.phtml');
        $readme = $this->renderer->render('templates/readme.phtml');
        $layout = $this->renderer->render('templates/index.phtml', ['navbar' => $navbar, 'content' => $readme]);

        return new HtmlResponse($layout);
    }
}