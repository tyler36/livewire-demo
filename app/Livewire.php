<?php

namespace App;

use Illuminate\Support\Facades\Blade;
use ReflectionClass;
use ReflectionProperty;
use stdClass;

/**
 * DIY Livewire package
 */
class Livewire
{
    /**
     * First render of class
     *
     * @param String $class Class to render
     *
     * @return String
     */
    function initialRender($class)
    {
        $component = new $class;

        [$html, $snapshot] = $this->toSnapshot($component);

        $snapshotAttribute = htmlentities(json_encode($snapshot));

        return <<<HTML
            <div wire:snapshot="$snapshotAttribute">
                {$html}
            </div>
        HTML;
    }

    /**
     * Instantiate a class from snapshot
     *
     * @param Array $snapshot
     * @return void
     */
    public function fromSnapshot($snapshot)
    {
        $class = $snapshot['class'];
        $data = $snapshot['data'];

        $component = new $class;
        $this->setProperties($component, $data);

        return $component;
    }

    /**
     * Convert a component into a snapshot
     *
     * @param [type] $component
     * @return void
     */
    public function toSnapshot($component)
    {
        $html = Blade::render(
            $component->render(),
            $properties = $this->getProperties($component)
        );

        $snapshot = [
            'class' => get_class($component),
            'data'  => $properties
        ];

        return [$html, $snapshot];
    }


    /**
     * Return an array of public properties
     *
     * @param stdClass $component Component to probe
     *
     * @return Array
     */
    function getProperties($component)
    {
        $properties = [];

        $reflectedProperties = (new ReflectionClass($component))->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach ($reflectedProperties as $property) {
            $properties[$property->getName()] = $property->getValue($component);
        }

        return $properties;
    }

    /**
     * Set public properties on conponent
     *
     * @param stdClass $component Component to set
     * @param Array $properties
     * @return void
     */
    public function setProperties($component, $properties)
    {
        foreach ($properties as $key => $value) {
            $component->{$key} = $value;
        }
    }

    /**
     * Call a specific method on a $component
     *
     * @param stdClass $component
     * @param String $method
     * @return void
     */
    public function callMethod($component, $method)
    {
        $component->{$method}();
    }

    /**
     * Update a specific property
     *
     * @param stdClass $component
     * @param String $propery
     * @param String $value
     * @return void
     */
    public function updateProperty($component, $property, $value)
    {
        $component->{$property} = $value;
    }
}
