<?php namespace Insight\ProductDefinitions;
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 6/16/14
 * Time: 3:47 PM
 */
use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;

/**
 * Class ProductImages
 * @package Insight\ProductDefinitions
 */
class ProductImage extends \Eloquent implements StaplerableInterface
{
    use EloquentTrait;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = array()) {
        $this->hasAttachedFile('image', [
            'styles' => [
                'medium' => '300x300',
                'thumb' => '100x100'
            ]
        ]);

        parent::__construct($attributes);
    }

    /**
     * Defined attributes that may not be mass-assigned
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return mixed
     */
    public function imageable()
    {
        return $this->morphTo();
    }

    public function uploadedBy()
    {
        return $this->belongsTo('Insight\Users\User', 'user_id', 'id');
    }

}