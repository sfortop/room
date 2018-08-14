<?php
/**
 * room
 *
 * @author Serhii Borodai <clarifying@gmail.com>
 */

namespace App\Action\CRUD\Create;


use App\Action\HandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\ServerRequest;
use Zend\View\Renderer\RendererInterface;

class Fail implements HandlerInterface
{
    /**
     * @var RendererInterface
     */
    private $renderer;


    /**
     * Fail constructor.
     * @param RendererInterface $renderer
     */
    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function handle(ServerRequest $request)
    {
        $content = $this->renderer->render('templates/create/fail.phtml', [
            'reason' => $request->getQueryParams()['reason'] ?? null,
            'routeNameSpace' => $request->getQueryParams()['routeNameSpace']
        ]);
        $layout = $this->renderer->render('templates/index.phtml', ['content' => $content, 'routeNameSpace' => $request->getQueryParams()['routeNameSpace']]);
        return new HtmlResponse($layout);
    }
}