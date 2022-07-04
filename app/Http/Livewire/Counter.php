<?php

namespace App\Http\Livewire;

/**
 * Demo counter class
 */
class Counter
{
    public $count = 0;

    /**
     * Template
     *
     * @return heredoc
     */
    public function render()
    {
        return <<<'HTML'
            <div class="grid h-full place-content-center">
                <div class="p-12 text-5xl text-center text-blue-700 border-4 border-blue-700 rounded-full counter">
                    <span>{{ $count }}</span>
                    <button wire:click="increment">+</button>
                    <input>
                </div>
            </div>
        HTML;
    }

    /**
     * Increment the count
     *
     * @return void
     */
    public function increment()
    {
        $this->count++;
    }
}
