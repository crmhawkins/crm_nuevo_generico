<?php

namespace App\Http\Livewire;

use App\Models\Documentos\Documento;
use App\Models\Users\User;
use Livewire\Component;
use Livewire\WithPagination;

class DocumentosTable extends Component
{
    use WithPagination;

    public $buscar;
    public $selectedUser;
    public $selectedAnio;
    public $selectedMes;
    public $usuarios;
    public $perPage = 10;
    public $sortColumn = 'created_at'; // Columna por defecto
    public $sortDirection = 'desc'; // Dirección por defecto
    protected $documentos; // Propiedad protegida para los usuarios

    public function mount(){
        $this->usuarios = User::all();
    }


    public function render()
    {
        $this->actualizardocumentos(); // Ahora se llama directamente en render para refrescar los clientes.
        return view('livewire.documentos-table', [
            'documentos' => $this->documentos
        ]);
    }

    protected function actualizardocumentos()
    {
        // Comprueba si se ha seleccionado "Todos" para la paginación
        $query = Documento::when($this->buscar, function ($query) {
            $query->where('nombre', 'like', '%' . $this->buscar . '%');
        })
        ->when($this->selectedUser, function ($query) {
            $query->where('admin_user_id', $this->selectedUser);
        })
        ->when($this->selectedAnio, function ($query) {
            $query->whereYear('fecha', $this->selectedAnio);
        })
        ->when($this->selectedMes, function ($query) {
            $query->whereMonth('fecha', $this->selectedMes);
        });

        $query->orderBy($this->sortColumn, $this->sortDirection);

        // Verifica si se seleccionó 'all' para mostrar todos los registros
        $this->documentos = $this->perPage === 'all' ? $query->get() : $query->paginate(is_numeric($this->perPage) ? $this->perPage : 10);
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
        if ($propertyName === 'buscar' || $propertyName === 'selectedUser' || $propertyName === 'selectedAnio' || $propertyName === 'selectedMes') {
            $this->resetPage(); // Resetear la paginación solo cuando estos filtros cambien.
        }
    }

}
