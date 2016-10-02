<?php

namespace Insight\Libraries;

/**
 * Class UniqueIdGenerator
 * @package Insight\Libraries
 */
/**
 * Class UniqueIdGenerator
 * @package Insight\Libraries
 */
class UniqueIdGenerator
{

    /**
     * @return bool|string
     */
    public function generateId($model, $fieldName, $prefix = false)
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';

        $id = $prefix ? $prefix : '';
        foreach (array_rand(str_split($letters), 4) as $letter) $id .= $letters[$letter];
        $id .= '-';
        foreach (array_rand(str_split($numbers), 4) as $number) $id .= $numbers[$number];

        // ensure id generated is unique to model
        if ($model->where($fieldName, $id)->first()) {
            return $this->generateId();
        }

        return $id;
    }

}
