<?php
/**
 * Copyright Serhii Borodai (c) 2017-2018.
 */

/**
 * Created by Serhii Borodai <clarifying@gmail.com>
 */

namespace App\Action\Api;


use App\Action\HandlerInterface;
use App\Entity\CorrectStorage;
use App\Gateway\CorrectStorageGateway;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequest;

/**
 * @api {put} /api/correct-storage/update/ Update price
 * @apiName correct-storage.update
 * @apiVersion 0.1.0
 * @apiGroup Price
 *
 * @apiParam {Object} object
 * @apiParam {Date} object.date Used to identify which price should be updated
 * @apiParam {String} object.price Price value
 *
 * @apiParamExample {json} request
 * {
 *      "date":"2018-08-01"
 *      "price":"0.01",
 * }
 *
 * @apiSuccess {Object} response
 * @apiSuccess {String} response.msg
 * @apiSuccess {String} response.url
 *
 * @apiError {Object} response Error response object
 * @apiError {String} response.url
 * @apiError {String} response.msg
 *
 * @apiSuccessExample {json} Success
 *     HTTP/1.1 200 OK
 *     {
 *       "affected": 1
 *     }
 *
 * @apiErrorExample {json} Not Found
 *     HTTP/1.1 404 Not Found
 *     {
 *       "msg": "price with Date: [2018-08-18] not found"
 *     }
 * @apiErrorExample {json} Other error
 *     HTTP/1.1 422
 *     {
 *       "msg": "some error message"
 *     }
 */

class UpdateCorrect implements HandlerInterface
{
    /**
     * @var CorrectStorageGateway
     */
    private $gateway;


    /**
     * UpdateCorrect constructor.
     * @param CorrectStorageGateway $gateway
     */
    public function __construct(CorrectStorageGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function handle(ServerRequest $request)
    {

        //@todo create DTO object with validation and don't use Entity
        /** @var CorrectStorage $priceInfo */
        $priceInfo = $this->gateway->createEntityFromArray(json_decode($request->getBody()->getContents(), true));

        if (!$priceInfo->getPrice()) {
            return new JsonResponse(['msg' => 'Zero price error'], 422);
        }

        $price = $this->gateway->fetchByDate($priceInfo->getDate());

        if (!$price) {
            return new JsonResponse(['msg' => sprintf('price with Date: [%s] not found')], 404);
        }

        try {
            $affected = $this->gateway->updateWith($price, $priceInfo->getPrice());
        } catch (\Exception $exception) {
            return new JsonResponse(['msg' => $exception->getMessage()], 422);
        }

        return new JsonResponse(['affected' => $affected]);

    }
}