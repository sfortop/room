<?php
/**
 * room
 *
 * @author Serhii Borodai <clarifying@gmail.com>
 */

namespace App\Gateway;


interface TableGatewayBasedInterface
{
    /**
     * @return string
     */
    public function getTable() : string;

}