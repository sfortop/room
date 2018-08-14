<?php
/**
 * room
 *
 * @author Serhii Borodai <clarifying@gmail.com>
 */

namespace App\Action\CRUD\Create;


use App\Action\HandlerInterface;
use App\Entity\PriceInterval;
use App\Gateway\PriceIntervalGateway;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\ServerRequest;

/**
 * Class HandleOriginal
 * @package App\Action\Create
 * @author Serhii Borodai <clarifying@gmail.com>
 * @deprecated
 */
class HandleOriginal implements HandlerInterface
{

    /**
     * @var PriceIntervalGateway
     */
    private $priceIntervalGateway;

    public function __construct(PriceIntervalGateway $priceIntervalGateway)
    {
        $this->priceIntervalGateway = $priceIntervalGateway;
    }


    public function handle(ServerRequest $request)
    {
        //@todo form validation
        //@todo store state for not-valid form and allow edit
        //@todo add CSRF check
        /** @var PriceInterval $priceInterval */
        $priceInterval = $this->priceIntervalGateway->createEntityFromArray(
            $request->getParsedBody()
        );

        //because we lack validation on form just swap values if start > end
        if ($priceInterval->getDateStart() > $priceInterval->getDateEnd()) {
            $tmp = $priceInterval->getDateEnd();
            $priceInterval->setDateEnd($priceInterval->getDateStart());
            $priceInterval->setDateStart($tmp);
        }

        try {
            $id = $this->priceIntervalGateway->insert($priceInterval);
            return new RedirectResponse('/create/success.phtml?' . http_build_query(['id' => $id, 'routeNameSpace' => 'price-interval']));
        } catch (\Exception $exception) {
            return new RedirectResponse('/create/fail.phtml?' . http_build_query(['reason' => $exception->getMessage(), 'routeNameSpace' => 'price-interval']));
        }

    }
}