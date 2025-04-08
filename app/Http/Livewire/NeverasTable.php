<?php

namespace App\Http\Livewire;

use App\Models\Neveras\Neveras;
use Livewire\Component;
use Livewire\WithPagination;

class NeverasTable extends Component
{
    use WithPagination;


    public $buscar;
    public $sortColumn = 'created_at'; // Columna por defecto
    public $sortDirection = 'desc'; // Dirección por defecto
    public $perPage = 10;

    protected $neveras; // Propiedad protegida para los usuarios

    public function mount(){

    }


    public function render()
    {
        $this->actualizarNeveras(); // Ahora se llama directamente en render para refrescar los clientes.
        return view('livewire.neveras-table', [
            'neveras' => $this->neveras
        ]);
    }

    protected function actualizarNeveras()
    {
        // Comprueba si se ha seleccionado "Todos" para la paginación
        $query = Neveras::when($this->buscar, function ($query) {
            $query->where('nombre', 'like', '%' . $this->buscar . '%');
        });

        $query->orderBy($this->sortColumn, $this->sortDirection);

        // Verifica si se seleccionó 'all' para mostrar todos los registros
        $this->neveras = $this->perPage === 'all' ? $query->get() : $query->paginate(is_numeric($this->perPage) ? $this->perPage : 10);
    }
    public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }
    public function updating($propertyName)
    {
        if ($propertyName === 'buscar') {
            $this->resetPage(); // Resetear la paginación solo cuando estos filtros cambien.
        }
    }

}
