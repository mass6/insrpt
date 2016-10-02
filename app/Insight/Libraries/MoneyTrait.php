<?php

namespace Insight\Libraries;

trait MoneyTrait
{

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (in_array($key, $this->moneyFields)) {

            return $this->getPrice($key);
        }

        return $this->getAttribute($key);
    }


    /**
     * Set a given attribute on the model.
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->moneyFields)) {
            $this->setPrice($key, $value);

            return;
        }

        parent::setAttribute($key, $value);

    }


    /**
     * Mutates the database price to standard decimal format when retrieved from DB
     *
     * @return float|string
     */
    private function getPrice($price)
    {
        return isset($this->attributes[$price]) ? number_format($this->attributes[$price] / 100, 2) : null;
    }

    /**
     * Mutates the price from web form into integer for persisting to DB
     *
     * @param $attribute
     * @param $value
     */
    private function setPrice($attribute, $value)
    {
        if (!empty($value)) {
            $this->attributes[$attribute] = (int)(str_replace(',','',$value) * 100);
        } else {
            $this->attributes[$attribute] = null;
        }

    }
}