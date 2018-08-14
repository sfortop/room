<?php
/**
 * Copyright Serhii Borodai (c) 2017-2018.
 */

/**
 * Created by Serhii Borodai <clarifying@gmail.com>
 */

namespace App\Action\CRUD\Read;


use App\Action\HandlerInterface;
use App\Gateway\PriceIntervalGateway;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\ServerRequest;
use Zend\View\Renderer\RendererInterface;

/**
 * Class Original
 * @package App\Action\CRUD\Read
 * @deprecated
 */
class Original implements HandlerInterface
{
    /**
     * @var RendererInterface
     */
    private $renderer;
    /**
     * @var PriceIntervalGateway
     */
    private $gateway;

    /**
     * @var int
     */
    private $itemsPerPage;
    /**
     * @var int
     */
    private $defaultPage;

    /**
     * ReadList constructor.
     * @param RendererInterface $renderer
     * @param PriceIntervalGateway $gateway
     * @param int $itemsPerPage
     * @param int $defaultPage
     */
    public function __construct(RendererInterface $renderer, PriceIntervalGateway $gateway, $itemsPerPage = 10, $defaultPage = 1)
    {
        $this->renderer = $renderer;
        $this->gateway = $gateway;
        $this->itemsPerPage = $itemsPerPage;
        $this->defaultPage = $defaultPage;
    }

    /**
     * @param ServerRequest $request
     * @return HtmlResponse
     * @throws \Exception
     */
    public function handle(ServerRequest $request)
    {

        $limit = $request->getQueryParams()['limit'] ?? $this->itemsPerPage;
        $limit = $limit <= $this->itemsPerPage ? $limit : $this->itemsPerPage;

        $page = $request->getQueryParams()['page'] ?? 1;

        $paginator = $this->gateway->fetchAll(true);

        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        $paginator->setCurrentPageNumber($page);

        $content = $this->renderer->render('templates/read/list-original.phtml',
            [
                'paginator' => $paginator,
                'page' => $page,
                'limit' => $limit,
            ]);
        $layout = $this->renderer->render('templates/index.phtml', ['content' => $content, 'routeNameSpace' => 'price-interval']);

        return new HtmlResponse($layout);
    }
}