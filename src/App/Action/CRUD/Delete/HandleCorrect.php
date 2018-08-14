<?php
/**
 * Copyright Serhii Borodai (c) 2017-2018.
 */

/**
 * Created by Serhii Borodai <clarifying@gmail.com>
 */

namespace App\Action\CRUD\Delete;


use App\Action\HandlerInterface;
use App\Gateway\CorrectStorageGateway;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequest;

class HandleCorrect implements HandlerInterface
{
    /**
     * @var CorrectStorageGateway
     */
    private $gateway;

    /**
     * HandleCorrect constructor.
     * @param CorrectStorageGateway $gateway
     */
    public function __construct(CorrectStorageGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function handle(ServerRequest $request)
    {
        $info = $request->getAttribute('routeParams');
        $ids = $info['id'] ?? null;
        if (!$ids) {
            return new JsonResponse(['url' => '/correct-storage/read/list.phtml', 'msg' => 'not found'], 404);
        }
        try {
            $idsToRemove = explode(',', $ids);
            $affected = $this->gateway->delete($idsToRemove);
            return new JsonResponse(['url' => '/correct-storage/read/list.phtml', 'msg' => sprintf('affected [%s]', $affected)]);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'url' => '/correct-storage/read/list.phtml', 'msg' => $exception->getMessage()
            ], 422);
        }
    }
}