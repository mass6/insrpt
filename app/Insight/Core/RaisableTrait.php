<?php 

namespace Insight\Core;

use Illuminate\Database\Eloquent\Model;

trait RaisableTrait
{
    protected function raiseEvents(Model $model, $event)
    {

        if (is_array($event)) {
            foreach ($event as $eventName) {
                $this->raiseEvents($model, $eventName);
            }
        }
        else {

            $reflectionClass = new \ReflectionClass($model);

            $className = $reflectionClass->getShortName();
            $namespace = $reflectionClass->getNamespaceName();
            $eventClassName = $namespace . '\Events\\' . $className . 'Was' . ucfirst($event) ;

            if (class_exists($eventClassName)) {
                $model->raise(new $eventClassName($model));
            }
        }
    }
} 