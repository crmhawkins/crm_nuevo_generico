<?php

namespace App\Http\Livewire;

use App\Models\Salones\Salon;
use App\Models\Tpv\Caja;
use App\Models\Users\User;
use Livewire\Component;
use Livewire\WithPagination;

class CajasTable extends Component
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
    protected $cajas;

    public function mount()
    {
        $this->salones = Salon::all();
        $this->usuarios = User::where('inactive',0)->get();
    }

    public function render()
    {
        $this->actualizarCajas();
        return view('livewire.cajas-table', [
            'cajas' => $this->cajas
        ]);
    }

    protected function actualizarCajas()
    {
        $query = Caja::when($this->selectedSalon, function ($query) {
                $query->where('salon_id', $this->selectedSalon);
            })
            ->when($this->buscar, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->whereHas('salon', function ($innerQuery) {
                        $innerQuery->where('nombre', 'like', '%' . $this->buscar . '%')
                        ->orWhere('direccion', 'like', '%' . $this->buscar . '%');
                    })
                    ->orWhere('apertura', 'like', '%' . $this->buscar . '%')
                    ->orWhere('cierre', 'like', '%' . $this->buscar . '%')
                    ->orWhere('previsto', 'like', '%' . $this->buscar . '%')
                    ->orWhere('diferencia', 'like', '%' . $this->buscar . '%')
                    ->orWhere('cambio', 'like', '%' . $this->buscar . '%');
                });
            });

            $query->orderBy($this->sortColumn, $this->sortDirection);

            // Verifica si se seleccionó 'all' para mostrar todos los registros
            $this->cajas = $this->perPage === 'all' ? $query->get() : $query->paginate(is_numeric($this->perPage) ? $this->perPage : 10);
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
        if ($propertyName === 'buscar' || $propertyName === 'selectedSalon') {
            $this->resetPage();
        }
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }
}
