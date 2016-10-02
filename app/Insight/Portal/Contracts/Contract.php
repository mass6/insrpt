<?php namespace Insight\Portal\Contracts; 
/**
 * Insight Client Management Portal:
 * Date: 8/13/14
 * Time: 10:05 AM
 */


class Contract extends \Eloquent
{

    protected $guarded = ['id'];

    protected $table = 'contracts';

    public static function findAllByDataGroup($dataGroup)
    {
        return static::where('customer', $dataGroup )->get();
    }

    public static function getContractIdsByDataGroup($dataGroup)
    {
        return static::where('customer', $dataGroup )->lists('name', 'id');
    }

    public function productRequests()
    {
        return $this->belongsToMany('Insight\ProductRequests\ProductRequest')->withTimestamps();
    }
}