<?php

namespace App\Livewire;

use App\Models\Todo;
use Exception;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class TodoList extends Component
{
  use WithPagination;
  #[Rule('required|min:3|max:50')]
  public $name ;
  public $search;
  public $editedID;

  #[Rule('required|min:3|max:50')]
  public $updatedName;

    public function create()
    {


      $validated=$this->validateOnly('name');
      Todo::create($validated);
      $this->reset('name');
      session()->flash('success','created succeeefully');
      $this->resetPage();
    }
    public function delete($todoID)
    {
        try {
            Todo::findOrFail($todoID)->delete();
            session()->flash('success', 'Todo deleted successfully');
        } catch (Exception $e) {
            session()->flash('error', 'Error deleting the Todo' );
            return;
        }

    }
    public function toggle($todoID)
    {
      $todo=Todo::find($todoID);
      $todo->completed=!$todo->completed;
      $todo->save();
    }

    public function edit($id){
        $this->editedID=$id;
        $todo=Todo::find($id);
        $this->updatedName = $todo->name;
}

    public function update()
    {

       $validated=$this->validateOnly('updatedName');

        $todo = Todo::find($this->editedID);
        $todo->name = $validated['updatedName'];
        $todo->save();

        $this-> cancelUpdate();
        session()->flash('success', 'Todo updated successfully');
    }
    public function cancelUpdate()
    {
        $this->reset('editedID','updatedName');
    }

    public function render()
    {

      $todos= Todo::latest()
      ->where('name', 'like', "%{$this->search}%")
      ->paginate(6);
        return view('livewire.todo-list',compact('todos'));
    }
}
