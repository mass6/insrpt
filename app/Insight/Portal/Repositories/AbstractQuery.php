<?php

namespace Insight\Portal\Repositories;

use Carbon\Carbon;

abstract class AbstractQuery implements QueryInterface
{

    /**
     * @var Carbon
     */
    protected $carbon;

    protected $params = [];

    protected $timezone;

    protected $fields;

    protected $orderBy;

    protected $dir;

    protected $sub_call = [];

    protected $filter = [];


    public function __construct(Carbon $carbon)
    {
        $this->carbon = $carbon;
    }

    public abstract function getAllowedFields();

    public abstract function getAllowedFilters();

    public function setParam($param, $value)
    {
        if (property_exists($this, $param)) {
            $this->$param = $value;
        }
        else {
            $this->params[$param] = $value;
        }
    }


    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;

        return $this;
    }


    public function setFields(Array $fields)
    {
        foreach ($fields as $field) {
            $this->validateField($field);
        }
        $this->fields = implode(',', $fields);

        return $this;
    }


    public function removeFieldLimits()
    {
        $this->fields = null;

        return $this;
    }


    public function orderBy($orderBy, $direction = 'asc')
    {
        $this->orderBy = $orderBy;
        $this->dir = $direction;

        return $this;
    }

    public function setSubCalls($sub_call)
    {
        if (is_array($sub_call)) {
            $this->sub_call = $sub_call;
        }
        else {
            $this->sub_call = [$sub_call];
        }

        return $this;
    }


    public function appendSubCall($sub_call)
    {
        if (is_array($sub_call)) {
            $this->sub_call = array_merge($this->sub_call, $sub_call);
        }
        else {
            $this->sub_call[] = $sub_call;
        }

        return $this;
    }


    public function setFilter($attribute, $value, $comparison = 'eq')
    {
        $this->validateFilter($attribute);
        $this->filter[] = ['attribute' => $attribute, $comparison => $value];

        return $this;
    }


    public function setFromDateFieldFilter($field, $date)
    {
        $this->validateFilter($field);

        $this->filter[] = ['attribute' => $field, 'gteq' => $this->parseToTimezone($date, $this->timezone)];

        return $this;
    }


    public function setToDateFieldFilter($field, $date)
    {
        $this->validateFilter($field);
        $this->filter[] = ['attribute' => $field, 'lteq' => $this->parseToTimezone($date . ' 23:59:59', $this->timezone)];

        return $this;
    }


    public function setMultipleFieldFilter(Array $fields, $searchTerm)
    {
        $conditions = [];
        foreach ($fields as $field)
        {
            $conditions[] = [ 'like' => '%' . $searchTerm . '%' ];
        }
        $this->filter[] = [$fields, $conditions];
    }


    public function getParam($param)
    {
        if (property_exists($this, $param)) {
            return $this->$param;
        }

        return array_get($this->params, $param, null);
    }

    public abstract function getQueryParams();

    protected function mergeParams()
    {
        $params = [];
        foreach($this->params as $key => $value) {
            if (isset($key)) {
                $params[$key] = $value;
            }
        }
        $this->timezone ? $params['timezone'] = $this->timezone : null;
        $this->fields ? $params['fields'] = $this->fields : null;
        $this->sub_call ? $params['sub_call'] = $this->sub_call : null;
        $this->filter ? $params['filter'] = $this->filter : null;
        if ($this->orderBy) {
            $params['order'] = $this->orderBy;
            $params['dir'] = $this->dir;
        }

        return $params;
    }

    protected function validateField($attribute)
    {
        if (! in_array($attribute, $this->getAllowedFields())) {
            throw new \InvalidArgumentException("{$attribute} is not in the list of available report fields.");
        }
    }

    protected function validateFilter($attribute)
    {
        if (! in_array($attribute, array_merge($this->getAllowedFields(), $this->getAllowedFilters()))) {
            throw new \InvalidArgumentException("{$attribute} is not in the list of available report filters.");
        }
    }

    /**
     * @param        $string
     * @param null   $timezone
     * @param string $targetTimezone
     *
     * @return string
     */
    protected function parseToTimezone($string, $timezone = null, $targetTimezone = 'UTC')
    {
        return $this->carbon->parse($string, $timezone ?: getenv('APP_TIMEZONE'))->setTimezone($targetTimezone)->toDateTimeString();
    }



}
