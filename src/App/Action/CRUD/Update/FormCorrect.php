<?php
/**
 * room
 *
 * @author Serhii Borodai <clarifying@gmail.com>
 */

namespace App\Action\CRUD\Update;


use App\Action\HandlerInterface;
use App\Gateway\CorrectStorageGateway;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\ServerRequest;
use Zend\View\Renderer\RendererInterface;

class FormCorrect implements HandlerInterface
{
    /**
     * @var RendererInterface
     */
    private $renderer;
    /**
     * @var CorrectStorageGateway
     */
    private $gateway;

    /**
     * FormCorrect constructor.
     * @param RendererInterface $renderer
     * @param CorrectStorageGateway $gateway
     */
    public function __construct(RendererInterface $renderer, CorrectStorageGateway $gateway)
    {
        $this->renderer = $renderer;
        $this->gateway = $gateway;
    }

    public function handle(ServerRequest $request)
    {
        //@todo add CSRF token
        //@todo move info about attribute name to constant

        $routeParams = $request->getAttribute('routeParams');

        $prices = $this->gateway->fetchByPriceIntervalInfo($routeParams['price-interval']);

        $content = $this->renderer->render('templates/edit/form-correct.phtml', [
            'routeNameSpace' => $this->getRouteNameSpace(),
            'prices' => $prices,
        ]);
        $layout = $this->renderer->render('templates/index.phtml', ['content' => $content, 'routeNameSpace' => $this->getRouteNameSpace()]);

        return new HtmlResponse($layout);

    }

    function getRouteNameSpace(): string
    {
        return 'correct-storage';
    }
}