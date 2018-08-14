<?php
/**
 * room
 *
 * @author Serhii Borodai <clarifying@gmail.com>
 */

namespace App\Action\CRUD\Update;


use App\Action\HandlerInterface;
use App\Gateway\CorrectStorageGateway;
use Zend\Diactoros\Response\RedirectResponse;
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
        $data = $request->getParsedBody();

        $pricesInfo = $data['price'];
        if ($data['all']) {
            $pricesInfo = array_fill_keys(array_keys($pricesInfo), $data['all']);
        }

        $prices = [];
        foreach ($pricesInfo as $date => $price) {
            $prices[] = $this->gateway->createEntityFromArray([
                'date' => $date,
                'price' => $price
            ]);
        }
        try {
            $count = $this->gateway->updateBulk(...$prices);

            return new RedirectResponse('/edit/success.phtml?' . http_build_query(['count' => $count, 'routeNameSpace' => 'correct-storage']));
        } catch (\Exception $exception) {
            return new RedirectResponse('/edit/fail.phtml?' . http_build_query(['reason' => $exception->getMessage(), 'routeNameSpace' => 'correct-storage']));
        }
    }
}