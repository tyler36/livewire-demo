<?php

namespace App\Http\Livewire;

class Counter
{
    public $count = 0;

    public function render()
    {
        return <<<'HTML'
            <div class="grid place-content-center h-full">
                <div class="counter rounded-full border-4 text-blue-700 border-blue-700 text-center text-5xl p-12">
                    <span>{{ $count }}</span>
                    <button wire:click="increment">+</button>
                </div>
            </div>
        HTML;
    }

    public function increment()
    {
        $this->count++;
    }
}
