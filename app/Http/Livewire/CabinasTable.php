<?php

namespace App\Http\Livewire;

use App\Models\Salones\Caja;
use App\Models\Salones\Salon;
use App\Models\Users\User;
use Livewire\Component;
use Livewire\WithPagination;

class CabinasTable extends Component
{
    use WithPagination;

    public $buscar;
    public $selectedSalon;
    public $selectedUser;
    public $perPage = 10;
    public $sortColumn = 'created_at'; // Columna por defecto
    public $sortDirection = 'desc'; // Dirección por defecto
    public $usuarios;
    public $salones;
    protected $cabinas;

    public function mount()
    {
        $this->salones = Salon::all();
        $this->usuarios = User::where('inactive',0)->get();
    }

    public function render()
    {
        $this->actualizarCabinas();
        return view('livewire.cabinas-table', [
            'cabinas' => $this->cabinas
        ]);
    }

    protected function actualizarCabinas()
    {
        $query = Caja::when($this->selectedSalon, function ($query) {
                $query->where('salon_id', $this->selectedSalon);
            })
            ->when($this->selectedUser, function ($query) {
                $query->where('admin_user_id', $this->selectedUser);
            })
            ->when($this->buscar, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->whereHas('salon', function ($innerQuery) {
                        $innerQuery->where('nombre', 'like', '%' . $this->buscar . '%')
                        ->orWhere('direccion', 'like', '%' . $this->buscar . '%');
                    })
                    ->orWhereHas('usuario', function ($innerQuery) { // Busca en los conceptos de presupuesto
                        $innerQuery->where('name', 'like', '%' . $this->buscar . '%');
                    })
                    ->orWhere('fecha', 'like', '%' . $this->buscar . '%')
                    ->orWhere('monto', 'like', '%' . $this->buscar . '%');
                });
            });

            $query->orderBy($this->sortColumn, $this->sortDirection);

            // Verifica si se seleccionó 'all' para mostrar todos los registros
            $this->cabinas = $this->perPage === 'all' ? $query->get() : $query->paginate(is_numeric($this->perPage) ? $this->perPage : 10);
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
        if ($propertyName === 'buscar' || $propertyName === 'selectedUser' || $propertyName === 'selectedSalon') {
            $this->resetPage();
        }
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }
}
