<?php

namespace Insight\Portal\Repositories;

interface QueryInterface
{
    public function getAllowedFields();

    public function getAllowedFilters();

    public function setParam($param, $value);

    public function setTimezone($timezone);

    public function setFields(Array $fields);

    public function removeFieldLimits();

    public function orderBy($orderBy, $direction = 'asc');

    public function setSubCalls($sub_call);

    public function appendSubCall($sub_call);

    public function setFilter($attribute, $value, $comparison = 'eq');

    public function setFromDateFieldFilter($field, $date);

    public function setToDateFieldFilter($field, $date);

    public function setMultipleFieldFilter(Array $fields, $searchTerm);

    public function getParam($param);

    public function getQueryParams();
}