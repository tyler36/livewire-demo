<html class="h-full">
    <script src="https://cdn.tailwindcss.com"></script>

    {!! livewire(App\Http\Livewire\Counter::class) !!}
</html>

<?php

function livewire($class) {
    $component = new $class;

    return Blade::render($component->render(), getProperties($component));
}

function getProperties($component) {
    $properties = [];

    $reflectedProperties = (new ReflectionClass($component))->getProperties(ReflectionProperty::IS_PUBLIC);
    foreach ($reflectedProperties as $property) {
        $properties[$property->getName()] = $property->getValue($component);
    }

    return $properties;
}
