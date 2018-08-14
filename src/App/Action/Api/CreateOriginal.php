<?php
/**
 * Copyright Serhii Borodai (c) 2017-2018.
 */

/**
 * Created by Serhii Borodai <clarifying@gmail.com>
 */

namespace App\Action\Api;


use App\Action\HandlerInterface;
use App\Entity\PriceInterval;
use App\Gateway\PriceIntervalGateway;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequest;
use Zend\Hydrator\HydratorInterface;


/**
 * Class CreateOriginal
 * @package App\Action\Api
 * @author Serhii Borodai <clarifying@gmail.com>
 *
 * @deprecated
 */
class CreateOriginal implements HandlerInterface
{
    /**
     * @var HydratorInterface
     */
    private $hydrator;
    /**
     * @var PriceIntervalGateway
     */
    private $priceIntervalGateway;

    public function __construct(HydratorInterface $hydrator, PriceIntervalGateway $priceIntervalGateway)
    {
        $this->hydrator = $hydrator;
        $this->priceIntervalGateway = $priceIntervalGateway;
    }

    public function handle(ServerRequest $request)
    {
        /** @var PriceInterval $priceInterval */
        $priceInterval = $this->priceIntervalGateway->createEntityFromArray(
            json_decode($request->getBody()->getContents(), true)
        );
        $this->priceIntervalGateway->insert($priceInterval);
        return new JsonResponse($this->hydrator->extract($priceInterval));
    }
}