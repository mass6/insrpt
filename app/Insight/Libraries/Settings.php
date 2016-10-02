<?php

namespace Insight\Libraries;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Settings
 * @package Insight\Libraries
 */
class Settings
{

    /**
     * @var
     */
    protected $settings;
    /**
     * @var Model
     */
    protected $model;

    /**
     * @param $settings
     * @param Model $model
     */
    public function __construct($settings, Model $model)
    {
        $this->settings = isset($settings) ? $settings : [];
        $this->model = $model;
    }

    /**
     * Retrieve the given setting.
     *
     * @param  string $key
     * @param null $fallback
     * @return string
     */
    public function get($key, $fallback = null)
    {
        if ($element = array_get($this->settings, $key, null)) {
            return $element;
        }

        return $fallback;
    }

    /**
     * Create and persist a new setting.
     *
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        array_set($this->settings, $key, $value);
        $this->persist();
    }

    /**
     * Determine if the given setting exists.
     *
     * @param  string $key
     * @return boolean
     */
    public function has($key)
    {
        return array_key_exists($key, $this->settings);
    }

    /**
     * Retrieve an array of all settings.
     *
     * @return array
     */
    public function all()
    {
        return $this->settings;
    }

    /**
     * Merge the given attributes with the current settings.
     * But do not assign any new settings.
     *
     * @param  array $attributes
     * @return mixed
     */
    public function merge(array $attributes)
    {
        $this->settings = array_merge(
            $this->settings, $attributes);

        return $this->persist();
    }

    /**
     * Persist the settings.
     *
     * @return mixed
     */
    protected function persist()
    {
        return $this->model->update(['settings' => json_encode($this->settings)]);
    }

    /**
     * Magic property access for settings.
     *
     * @param  string $key
     * @throws \Exception
     * @return void
     */
    public function __get($key)
    {
        if ($this->has($key)) {
            return $this->get($key);
        }
        throw new \Exception("The {$key} setting does not exist.");
    }

} 