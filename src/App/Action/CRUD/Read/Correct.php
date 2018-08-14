<?php
/**
 * Copyright Serhii Borodai (c) 2017-2018.
 */

/**
 * Created by Serhii Borodai <clarifying@gmail.com>
 */

namespace App\Action\CRUD\Read;

use App\Action\HandlerInterface;
use App\Common\Constants;
use App\Entity\PriceInterval;
use App\Gateway\CorrectStorageGateway;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\ServerRequest;
use Zend\View\Renderer\RendererInterface;

class Correct implements HandlerInterface
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
     * @param CorrectStorageGateway $gateway
     * @param int $itemsPerPage
     * @param int $defaultPage
     */
    public function __construct(RendererInterface $renderer, CorrectStorageGateway $gateway, $itemsPerPage = 10, $defaultPage = 1)
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

        $fromDate = $request->getQueryParams()['fromDate'] ?? date(Constants::DATE_FORMAT_PHP);
        $prevDate = (array) ($request->getQueryParams()['prevDate'] ?? []);

        $resultSet = $this->gateway->fetchPaginatedInterval($fromDate, $limit, new PriceInterval());
        //@todo not completed. We also should use min/max from selected ranges
        $minMax = $this->gateway->fetchMinMaxDate();

        $prevDate  = !empty($prevDate) ? $prevDate :
            ($minMax['min'] < $fromDate ? [$minMax['min']] : []);
        $content = $this->renderer->render('templates/read/list-correct.phtml',
            [
                'paginated' => $resultSet,
                'fromDate' => $fromDate,
                'prevDate' => $prevDate,
                'limit' => $limit,
                'max' => $minMax['max'],
            ]);
        $layout = $this->renderer->render('templates/index.phtml', ['content' => $content, 'routeNameSpace' => 'correct-storage']);

        return new HtmlResponse($layout);
    }
}