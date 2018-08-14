<?php
/**
 * room
 *
 * @author Serhii Borodai <clarifying@gmail.com>
 */


namespace App\Entity;

use App\Common\Constants;


/**
 * Class PriceInterval
 * @package App\Entity
 *
 * Bad idea to handle intervals in this way
 * @see CorrectStorage class
 *
 */
class PriceInterval implements EntityInterface
{
    /**
     * @var integer
     */
    protected $id;
    /**
     * @var string
     */
    protected $date_start;

    /**
     * @var string
     */
    protected $date_end;

    /**
     * @var float
     */
    protected $price;

    protected $dow = [
        false, //sunday
        false, //monday
        false, //tuesday
        false, //wednesday
        false, //thursday
        false, //friday
        false, //saturday
    ];
    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    //@todo move to specific hydrator/mapper instead
    public function setRange($range): void
    {
        $tmp = explode(Constants::DATE_RANGE_DELIMITER, $range);
        $this->setDateStart($tmp[0]);
        $this->setDateEnd($tmp[1]);
    }

    /**
     * @return string
     */
    public function getDateStart(): string
    {
        return $this->date_start;
    }

    /**
     * @param string $date_start
     */
    public function setDateStart(string $date_start): void
    {
        $this->date_start = $date_start;
    }

    /**
     * @return string
     */
    public function getDateEnd(): string
    {
        return $this->date_end;
    }

    /**
     * @param string $date_end
     */
    public function setDateEnd(string $date_end): void
    {
        $this->date_end = $date_end;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price): void
    {
        $this->price = (float) $price;
    }

    public function setDow(bool ...$dow)
    {
        $this->dow = array_replace($this->dow, $dow);
    }

    /**
     * @return bool
     */
    public function getMon(): bool
    {
        return $this->dow[1];
    }

    /**
     * @param bool $mon
     */
    public function setMon(bool $mon): void
    {
        $this->dow[1] = $mon;
    }

    /**
     * @return bool
     */
    public function getTue(): bool
    {
        return $this->dow[2];
    }

    /**
     * @param bool $tue
     */
    public function setTue(bool $tue): void
    {
        $this->dow[2] = $tue;
    }

    /**
     * @return bool
     */
    public function getWed(): bool
    {
        return $this->dow[3];
    }

    /**
     * @param bool $wed
     */
    public function setWed(bool $wed): void
    {
        $this->dow[3] = $wed;
    }

    /**
     * @return bool
     */
    public function getThu(): bool
    {
        return $this->dow[4];
    }

    /**
     * @param bool $thu
     */
    public function setThu(bool $thu): void
    {
        $this->dow[4] = $thu;
    }

    /**
     * @return bool
     */
    public function getFri(): bool
    {
        return $this->dow[5];
    }

    /**
     * @param bool $fri
     */
    public function setFri(bool $fri): void
    {
        $this->dow[5] = $fri;
    }

    /**
     * @return bool
     */
    public function getSat(): bool
    {
        return $this->dow[6];
    }

    /**
     * @param bool $sat
     */
    public function setSat(bool $sat): void
    {
        $this->dow[6] = $sat;
    }

    /**
     * @return bool
     */
    public function getSun(): bool
    {
        return $this->dow[0];
    }

    /**
     * @param bool $sun
     */
    public function setSun(bool $sun): void
    {
        $this->dow[0] = $sun;
    }

}

