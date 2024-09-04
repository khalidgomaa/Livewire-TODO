<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class TodoList extends Component
{
  use WithPagination;
  #[Rule('required|min:3|max:50')]
  public $name ;
  public $search;

    public function create()
    {

    
      $validated=$this->validateOnly('name');
      Todo::create($validated);
      $this->reset('name');
      session()->flash('success','created succeeefully');
    }


    public function render()
    {
   
        $todos=Todo::paginate(6);

        return view('livewire.todo-list',compact('todos'));
    }
}
