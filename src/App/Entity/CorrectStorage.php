<?php
/**
 * Copyright Serhii Borodai (c) 2017-2018.
 */

/**
 * Created by Serhii Borodai <clarifying@gmail.com>
 */

namespace App\Entity;


class CorrectStorage implements EntityInterface
{
    //limitation is required due heavy load for pagination on unlimited ranges
    //it's possible refactor and inject as configuration
    //also depending on available resources of DB server we can increase this limit e.g 3000+
    const MAX_LENGTH_INTERVAL = 365;

    protected $date;

    protected $price;

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = (float) $price;
    }

}