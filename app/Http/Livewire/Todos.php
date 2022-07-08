<?php

namespace App\Http\Livewire;

/**
 * Demo todo class
 */
class Todos
{
    public $draft = 'Some todos ...';

    public $todos;

    public function mount()
    {
        $this->todos = collect([
            'One todo',
            'Two todo',
        ]);
    }

    public function updatedDraft()
    {
        $this->draft = strtoupper($this->draft);
    }

    public function addTodo()
    {
        $this->todos->push($this->draft);
        $this->draft = '';
    }


    /**
     * Template
     *
     * @return heredoc
     */
    public function render()
    {
        return <<<'HTML'
            <div class="grid h-full gap-4 todos place-content-center">
                <div class="space-x-2">
                    <input wire:model="draft" type="text" class="px-4 py-2 border border-black rounded-full">
                    <button wire:click="addTodo" class="px-4 py-2 text-white bg-black rounded-full">Add Todos</button>
                </div>

                <ul class="mt-4 text-lg font-bold list-disc list-inside">
                    @foreach($todos as $todo)
                        <li>{{ $todo }}</li>
                    @endforeach
                </ul>
            </div>
        HTML;
    }
}
