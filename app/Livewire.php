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

        $html = Blade::render(
            $component->render(),
            $this->getProperties($component)
        );

        $snapshot = [
            'class' => get_class($component),
            'data' => $this->getProperties($component)
        ];
        $snapshotAttribute = htmlentities(json_encode($snapshot));

        return <<<HTML
            <div wire:snapshot="$snapshotAttribute">
                {$html}
            </div>
        HTML;
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

}