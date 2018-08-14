<?php
/**
 * Copyright Serhii Borodai (c) 2017-2018.
 */

/**
 * Created by Serhii Borodai <clarifying@gmail.com>
 */

namespace App\Action\CRUD\Create;


use App\Action\HandlerInterface;
use App\Action\Traits\PreparePrices;
use App\Gateway\CorrectStorageGateway;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\ServerRequest;

class HandleCorrect implements HandlerInterface
{
    use PreparePrices;

    /**
     * @var CorrectStorageGateway
     */
    private $gateway;

    public function __construct(CorrectStorageGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function handle(ServerRequest $request)
    {
        //@todo form validation
        //@todo store state for not-valid form and allow edit
        //@todo add CSRF check

        $data = $request->getParsedBody();
        $prices = $this->preparePrices($data);
        try {
            $number = $this->gateway->insertBulk(... $prices);
            return new RedirectResponse('/create/success.phtml?' . http_build_query(['number' => $number, 'routeNameSpace' => 'correct-storage']));
        } catch (\Exception $exception) {
            return new RedirectResponse('/create/fail.phtml?' . http_build_query(['reason' => $exception->getMessage(), 'routeNameSpace' => 'correct-storage']));
        }
    }
}