<?php

namespace App\Http\Livewire;

use App\Models\Avisos\Seguro;
use App\Models\Suppliers\Supplier;
use App\Models\Users\User;
use Livewire\Component;
use Livewire\WithPagination;

class SegurosTable extends Component
{
    use WithPagination;

    public $buscar;
    public $selectedUser;
    public $selectedProveedor;
    public $selectedAnio;
    public $selectedMes;
    public $usuarios;
    public $proveedores;
    public $perPage = 10;
    public $sortColumn = 'created_at'; // Columna por defecto
    public $sortDirection = 'desc'; // Dirección por defecto
    protected $documentos; // Propiedad protegida para los usuarios

    public function mount(){
        $this->usuarios = User::all();
        $this->proveedores = Supplier::all();
    }


    public function render()
    {
        $this->actualizardocumentos(); // Ahora se llama directamente en render para refrescar los clientes.
        return view('livewire.seguros-table', [
            'documentos' => $this->documentos
        ]);
    }

    protected function actualizardocumentos()
    {
        // Comprueba si se ha seleccionado "Todos" para la paginación
        $query = Seguro::when($this->buscar, function ($query) {
            $buscar = $this->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('alta', 'like', "%{$buscar}%")
                ->orWhere('descripcion', 'like', "%{$buscar}%")
                ->orWhere('caducidad', 'like', "%{$buscar}%");
            });
        })
        ->when($this->selectedProveedor, function ($query) {
            $query->where('proveedor_id', $this->selectedProveedor);
        })
        ->when($this->selectedUser, function ($query) {
            $query->where('admin_user_id', $this->selectedUser);
        })
        ->when($this->selectedAnio, function ($query) {
            $query->whereYear('caducidad', $this->selectedAnio);
        })
        ->when($this->selectedMes, function ($query) {
            $query->whereMonth('caducidad', $this->selectedMes);
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
        if ($propertyName === 'buscar' || $propertyName === 'selectedUser' || $propertyName === 'selectedProveedor' || $propertyName === 'selectedAnio' || $propertyName === 'selectedMes') {
            $this->resetPage(); // Resetear la paginación solo cuando estos filtros cambien.
        }
    }

}
