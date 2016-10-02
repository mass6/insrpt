<?php
namespace Insight\ProductDefinitions;

class AddNewResourcingRequestCommand {
    /**
     * @var
     */
    public $user;
    /**
     * @var
     */
    public $name;

    /**
     * @var
     */
    public $category;
    /**
     * @var
     */
    public $uom;
    /**
     * @var
     */
    public $price;
    /**
     * @var
     */
    public $short_description;
    /**
     * @var
     */
    public  $image1;

    /**
     * @param $user
     * @param $name
     * @param $category
     * @param $uom
     * @param $price
     * @param $short_description
     * @param $image1
     */
    public function __construct($user, $name, $category, $uom, $price, $short_description, $image1)
    {
        $this->name = $name;
        $this->category = $category;
        $this->uom = $uom;
        $this->price = $price;
        $this->short_description = $short_description;
        $this->image1 = $image1;
        $this->user = $user;
    }
}