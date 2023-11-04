<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Task as TaskModel;
class Task extends Component
{
    public $tasks;
    public TaskModel $task;
    // Para definir las propiedades del componente como del binding model
    protected $rules = ['task.text' => 'required|max:40'];

    public function mount(): void
    {
        $this->tasks    = TaskModel::get();
        $this->task     = new TaskModel();
    }

    public function updatedTaskText(): void
    {
        // Adiciono las reglas acá adentro porque cambian con relacion
        // a la regla inicial
        $this->validate(['task.text' => 'max:40']);
    }

    public function save(): void
    {
        $this->validate();
        $this->task->save();
        $this->mount();
        $this->emitUp('taskSaved','Tarea guarda correctamente..');
        // Se mueve el mensaje de session al componente main para que sea ejecutado desde alli
        // session()->flash('message','Tarea Guardada correctamente');
    }

    public function update(TaskModel $task)
    {
        $task->update(['done' => !$task->done]);
        $this->mount();
    }
    public function edit(TaskModel $task)
    {
        $this->task = $task;
    }

    public function delete($id)
    {
        $taskToDelete = TaskModel::find($id);
        if (!is_null($taskToDelete)){
            $taskToDelete->delete();
            $this->mount();
            $this->emitUp('taskSaved','Tarea eliminada correctamente..');
        }
    }
    public function render()
    {
        return view('livewire.task');
    }
}
