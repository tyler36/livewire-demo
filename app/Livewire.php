<?php

namespace App;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
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
        $component = new $class();

        if (method_exists($component, 'mount')) {
            $component->mount();
        }

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
        $this->verifyChecksum($snapshot);

        $class = $snapshot['class'];
        $data = $snapshot['data'];
        $meta = $snapshot['meta'];

        $component = new $class();

        $properties = $this->hydrateProperties($data, $meta);

        $this->setProperties($component, $properties);

        return $component;
    }

    public function verifyChecksum($snapshot)
    {
        $checksum = $snapshot['checksum'];
        unset($snapshot['checksum']);

        if ($checksum !== $this->generateChecksum($snapshot)) {
            throw new Exception('Hey, stop hacking me!');
        }
    }
    public function hydrateProperties($data, $meta)
    {
        $properties = [];

        foreach ($data as $key => $value) {
            if (isset($meta[$key]) && $meta[$key] === 'collection') {
                $value = collect($value);
            }

            $properties[$key] = $value;
        }

        return $properties;
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

        [$data, $meta] = $this->dehydrateProperties($properties);

        $snapshot = [
            'class' => get_class($component),
            'data'  => $data,
            'meta'  => $meta,
        ];

        $snapshot['checksum'] = $this->generateChecksum($snapshot);

        return [$html, $snapshot];
    }

    public function generateChecksum($snapshot)
    {
        return md5(json_encode($snapshot));
    }

    public function dehydrateProperties($properties)
    {
        $data = $meta = [];

        foreach ($properties as $key => $value) {
            if ($value instanceof Collection) {
                $value = $value->toArray();
                $meta[$key] = 'collection';
            }

            $data[$key] = $value;
        }

        return [$data, $meta];
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

        $updatedHook = 'updated' . Str::title($property);

        if (method_exists($component, $updatedHook)) {
            $component->{$updatedHook}();
        }
    }
}
