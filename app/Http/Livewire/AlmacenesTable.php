<?php

namespace App\Http\Livewire;

use App\Models\Almacenes\Almacen;
use App\Models\Salones\Salon;
use App\Models\Users\User;
use Livewire\Component;
use Livewire\WithPagination;

class AlmacenesTable extends Component
{
    use WithPagination;

    public $buscar;
    public $perPage = 10;
    public $sortColumn = 'created_at'; // Columna por defecto
    public $sortDirection = 'desc'; // Dirección por defecto
    protected $salones;

    public function mount(){
    }

    public function render()
    {
        $this->actualizarClientes();
        return view('livewire.almacenes-table', [
            'almacenes' => $this->salones
        ]);
    }

    protected function actualizarClientes()
    {
        $query = Almacen::when($this->buscar, function ($query) {
                $query->where('nombre', 'like', '%' . $this->buscar . '%')
                      ->orWhere('direccion', 'like', '%' . $this->buscar . '%');
            });

            $query->orderBy($this->sortColumn, $this->sortDirection);

            // Verifica si se seleccionó 'all' para mostrar todos los registros
            $this->salones = $this->perPage === 'all' ? $query->get() : $query->paginate(is_numeric($this->perPage) ? $this->perPage : 10);
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
            $this->resetPage();
        }
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }
}
