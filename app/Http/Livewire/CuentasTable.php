<?php

namespace App\Http\Livewire;

use App\Models\Accounting\AssociatedExpenses;
use App\Models\CrmActivities\CrmActivitiesMeetings;
use App\Models\Tpv\Order;
use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CuentasTable extends Component
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
    protected $orders; // Propiedad protegida para los usuarios

    public function mount(){
        $this->usuarios = User::all();
        $this->selectedUser = Auth::user()->id;
    }


    public function render()
    {
        $this->actualizar(); // Ahora se llama directamente en render para refrescar los clientes.
        return view('livewire.cuentas-table', [
            'orders' => $this->orders
        ]);
    }

    protected function actualizar()
    {
        $query = Order::where('status', '1');

        $query->orderBy($this->sortColumn, $this->sortDirection);

        // Verifica si se seleccionó 'all' para mostrar todos los registros
        $this->orders = $this->perPage === 'all' ? $query->get() : $query->paginate(is_numeric($this->perPage) ? $this->perPage : 10);
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
