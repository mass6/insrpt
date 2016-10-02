<?php

namespace Insight\Sourcing;

use Cartalyst\Sentry\Facades\Laravel\Sentry;
use Laracasts\Commander\Events\EventGenerator;

class SourcingRequest extends \Eloquent
{

    use EventGenerator;

    /**
     * @var array
     */
    public static $statuses = [
        'ASS' => 'Assessing',
        'SRC' => 'Sourcing',
        'PRI' => 'Pricing',
        'CLS' => 'Closed'
    ];

    /**
     * @var array
     */
    public static $reasonsClosed = [
        'COM' => 'Completed',
        'DUP' => 'Duplicate',
    ];
    /**
     * Allowed fields for mass assignment
     *
     * @var array
     */
    protected $fillable = [

        'batch',
        'customer_id',
        'received_on',
        'customer_sku',
        'customer_product_description',
        'customer_uom',
        'customer_price',
        'customer_price_currency',
        'tss_sku',
        'tss_product_name',
        'tss_uom',
        'tss_buy_price',
        'tss_buy_price_currency',
        'supplier_name',
        'tss_sell_price',
        'tss_sell_price_currency',
        'tss_buy_price_margin',
        'tss_sell_price_margin',
        'customer_sell_price_margin',
        'status',
        'created_by_id',
        'updated_by_id',
        'assigned_to_id',
        'closed_at',
        'reason_closed',
        'remarks',
    ];

    /**
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'closed_at', 'received_on'];

    /**
     * @var array
     */
    private $pricesArray = [
        'customer_price',
        'tss_buy_price',
        'tss_sell_price',
        'tss_buy_price_margin',
        'tss_sell_price_margin',
        'customer_sell_price_margin',
    ];

    /**
     * @return mixed
     */
    public function customer()
    {
        return $this->belongsTo('Insight\Companies\Company', 'customer_id');
    }

    /**
     * User whom originally created the product
     *
     * @return mixed
     */
    public function createdBy()
    {
        return $this->belongsTo('Insight\Users\User', 'created_by_id');
    }

    /**
     * @return mixed
     */
    public function assignedTo()
    {
        return $this->belongsTo('Insight\Users\User', 'assigned_to_id');
    }

    /**
     * Last user to update the item
     *
     * @return mixed
     */
    public function updatedBy()
    {
        return $this->belongsTo('Insight\Users\User', 'updated_by_id');
    }

    /** Sourcing Request can have many associated comments
     * @return mixed
     */
    public function comments()
    {
        return $this->morphMany('Insight\Comments\Comment', 'commentable');
    }

    /**
     * @return mixed
     */
    public function statusName()
    {
        return self::$statuses[$this->attributes['status']];
    }


    /**
     * @return mixed
     */
    public function reasonClosedName()
    {
        return self::$reasonsClosed[$this->attributes['reason_closed']];
    }

    /**
     * @return array
     */
    public static function assignableUsersList()
    {
        $users = Sentry::findAllUsersWithAccess(array('sourcing-requests'));

        $userList = [];
        foreach ($users as $user) {
            $userList[$user->id] = $user->name();
        }

        return [null => ''] + $userList;
    }

    /**
     * Represents the margin achieved between customer's current buy price and 36S buy price from new supplier
     * (fx.
     *  Customer Price = 60
     *  36S Buy Price = 30
     *  36S Buy Price Margin: (60 - 30) / 60 = .5, or 50%
     *
     * @return string
     */
    public function tssBuyPriceMargin()
    {
        if ((isset($this->attributes['customer_price']) &&  $this->attributes['customer_price'] > 0) && (isset($this->attributes['tss_buy_price']) &&  $this->attributes['tss_buy_price']> 0)) {
            return number_format(((($this->attributes['customer_price'] - $this->attributes['tss_buy_price']) / $this->attributes['customer_price'] ) * 100), 2);
        }
    }
    /**
     * Represents the margin achieved by 36S between 36S buy price and 36S sell price to customer
     * (fx.
     *  36S Buy Price = 30
     *  36S Sell Price = 45
     *  36S Sell Price Margin: (45 - 30) / 30 = .5, or 50%
     *
     * @return string
     */
    public function tssSellPriceMargin()
    {
        if ((isset($this->attributes['tss_sell_price']) &&  $this->attributes['tss_sell_price'] > 0) && (isset($this->attributes['tss_buy_price']) &&  $this->attributes['tss_buy_price'] > 0)) {
            return number_format(((($this->attributes['tss_sell_price'] - $this->attributes['tss_buy_price']) / $this->attributes['tss_buy_price'] ) * 100), 2);
        }

    }
    /**
     * Represents the margin achieved by 36S between 36S buy price and 36S sell price to customer
     * (fx.
     *  Customer Price = 60
     *  36S Sell Price = 45
     *  Customer Sell Price Margin: (60 - 45) / 60 = .25, or 25%
     *
     * @return string
     */
    public function customerSellPriceMargin()
    {
        if ((isset($this->attributes['customer_price']) && $this->attributes['customer_price'] > 0) && (isset($this->attributes['tss_sell_price']) && $this->attributes['tss_sell_price'] > 0)) {
            return number_format(((($this->attributes['customer_price'] - $this->attributes['tss_sell_price']) / $this->attributes['customer_price'] ) * 100), 2);
        }
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (in_array($key, $this->pricesArray)) {

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
        if (in_array($key, $this->pricesArray)) {
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