<?php namespace Insight\Settings;
/**
 * Insight Client Management Portal:
 * Date: 7/28/14
 * Time: 3:32 PM
 */

/**
 * Class SettingRepository
 * @package Insight\Settings
 */

use Insight\Portal\Connections\Webservices;
class SettingRepository
{
    /**
     * @return mixed
     */
    public function getAll()
    {
        return Setting::all();
    }

    public function findByName($name)
    {
        return Setting::where('name', $name)->first()->pluck('value');
    }

    /**
     * @param $type
     * @return mixed
     */
    public function findByType($type)
    {
        return Setting::where('type', $type)->get();
    }

    /**
     * @return mixed
     */
    public function getTypes()
    {
        // returns a distinct column list of setting types
        return Setting::distinct()->lists('type');
    }

    /**
     *
     *
     * @return array
     */
    public function getGrouped()
    {
        $types = $this->getTypes();
        $settings = [];

        // build the array of name/value settings
        foreach($types as $type)
        {
            $typeSettings = $this->findByType($type);
            foreach($typeSettings as $index => $value){
                $settings[$type][$value['name']] = $value['value'];
            }
        }
        return $settings;
    }

    //Get all of categories via API
    public function getAllCategories(){
        $webservice = new Webservices();
        $categoriesList = $webservice->getAllCategories();
        return $categoriesList;
    }
} 