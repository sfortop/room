<?php
/**
 * room
 *
 * @author Serhii Borodai <clarifying@gmail.com>
 */

namespace App\Action\Traits;


use App\Common\Constants;

/**
 * Trait PreparePrices requires property $gateway instanceof \App\Gateway\AbstractGateway
 *
 * @todo add validation `instanceof SomeInterface` for class which are uses trait
 * @package App\Action\Traits
 *
 *
 * @property \App\Gateway\AbstractGateway gateway
 */
trait PreparePrices
{
    /**
     *
     * basically we should leave at handler only mapping and model/service calls e.g.
     * ```
     * try {
     *   $dto = $this->mapper->hydrate($request->getParsedBody(), new CreateDTO());
     *   $this->service->create($dto);
     * } catch (MappingException | ValidationException $e) {
     *   // ... some code here to create response object with error mapping/validation messages
     * } catch (CreateException $e) {
     *   // ... some other code here to create response object with service error messages
     * }
     * ```
     *
     * @todo move logic into model at further development
     * @param $data
     *
     * @return array CorrectStorage[]
     */
    protected function preparePrices($data)
    {
        [$startDate, $endDate] = explode(Constants::DATE_RANGE_DELIMITER, $data['range']);

        $startDate = \DateTime::createFromFormat(Constants::DATE_FORMAT_PHP, $startDate);
        $endDate = \DateTime::createFromFormat(Constants::DATE_FORMAT_PHP, $endDate);

        $prices = [];
        while ($startDate <= $endDate) {
            // skip day in range if day of week not selected
            if (!$data['dow'] || ($data['dow'][$startDate->format('w')] ?? false)) {
                $price  = ['date' => $startDate->format(Constants::DATE_FORMAT_PHP)] + $data;
                $prices[] = $this->gateway->createEntityFromArray($price);
            }
            $startDate->modify('+1 day');
        }
        return $prices;
    }

}