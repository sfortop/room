<?php
/**
 * room
 *
 * @author Serhii Borodai <clarifying@gmail.com>
 */

namespace App\Action\Api;


use App\Action\HandlerInterface;
use App\Action\Traits\PreparePrices;
use App\Common\Constants;
use App\Gateway\CorrectStorageGateway;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequest;

/**
 * @api {post} /api/correct-storage/create/ Create interval
 * @apiName correct-storage.create
 * @apiVersion 0.1.0
 * @apiGroup Price
 *
 * @apiParam {Object} interval
 * @apiParam {String} interval.price
 * @apiParam {String} interval.dateStart
 * @apiParam {String} interval.dateEnd
 * @apiParam {Boolean[]} interval.dow days of week when price enabled
 * @apiParamExample {json} request
 * {
 *      "price":"0.01",
 *      "dateStart":"2018-08-01",
 *      "dateEnd":"2018-08-10",
 *      "dow":[true, true, false, false, false, true, true]
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
 * @apiSampleRequest /api/correct-storage/create/
 *
 *
 * @apiSuccessExample {json} Success
 *     HTTP/1.1 200 OK
 *     {
 *       "url": "/success/url",
 *       "msg": "some message"
 *     }
 *
 * @apiErrorExample {json} Not Found
 *     HTTP/1.1 404 Not Found
 *     {
 *       "url": "/not-found-id/url",
 *       "msg": "some error message"
 *     }
 * @apiErrorExample {json} Other error
 *     HTTP/1.1 422
 *     {
 *       "url": "/other-error/url",
 *       "msg": "some error message"
 *     }
 */
class CreateCorrect  implements HandlerInterface
{

    use PreparePrices;
    /**
     * @var CorrectStorageGateway
     */
    private $gateway;


    /**
     * CreateCorrect constructor.
     * @param CorrectStorageGateway $gateway
     */
    public function __construct(CorrectStorageGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function handle(ServerRequest $request)
    {
        try {
            $data = json_decode($request->getBody()->getContents(), true);

            $data['range'] = $data['dateStart'] . Constants::DATE_RANGE_DELIMITER . $data['dateEnd'];

            $prices = $this->preparePrices($data);
            $number = $this->gateway->insertBulk(... $prices);
            return new JsonResponse(['msg' => sprintf("%s prices inserted", $number)]);
        } catch (\Exception $exception) {
            return new JsonResponse(['msg' => $exception->getMessage()], 422);
        }
    }
}