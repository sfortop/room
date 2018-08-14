<?php
/**
 * Copyright Serhii Borodai (c) 2017-2018.
 */

/**
 * Created by Serhii Borodai <clarifying@gmail.com>
 */

namespace App\Action\Api;


use App\Action\CRUD\Delete\HandleCorrect;
use App\Action\HandlerInterface;
use Zend\Diactoros\ServerRequest;


// @todo create separate api handler implementation
// Current api realization uses default handler
// So we has unnecessary `url` in responses

/**
 * @api {delete} /correct-storage/delete/{id} Delete price
 * @apiName correct-storage.delete
 * @apiVersion 0.1.0
 * @apiGroup Price
 *
 * @apiParam {String} id one or multiple ids separated by comma
 *
 * @apiSuccess {object} response
 * @apiSuccess {String} response.url used only for html version to receive redirect url on success
 * @apiSuccess {String} response.msg
 *
 * @apiSuccessExample Success
 *     HTTP/1.1 200 OK
 *     {
 *       "url": "/success/url",
 *       "msg": "affected [number]"
 *     }
 *
 * @apiError {object} response
 * @apiError {String} response.url used only for html version to receive redirect url on error
 * @apiError {String} response.msg
 *
 * @apiErrorExample Not Found
 *     HTTP/1.1 404 Not Found
 *     {
 *       "url": "/not-found-id/url",
 *       "msg": "not found"
 *     }
 * @apiErrorExample Other
 *     HTTP/1.1 422
 *     {
 *       "url": "/url-to-go-on-error",
 *       "msg": "error message"
 *     }
 */

/**
 * Class DeleteCorrect
 *
 * @package App\Action\Api
 * @author Serhii Borodai <clarifying@gmail.com>
 *
 */
class DeleteCorrect implements HandlerInterface
{
    /**
     * @var HandleCorrect
     */
    private $handleCorrect;

    /**
     * DeleteCorrect constructor.
     *
     * @param HandleCorrect $handleCorrect
     */
    public function __construct(HandleCorrect $handleCorrect)
    {
        $this->handleCorrect = $handleCorrect;
    }

    public function handle(ServerRequest $request)
    {
        return $this->handleCorrect->handle($request);
    }
}