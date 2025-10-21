<?php

namespace App\Http\Livewire;

use App\Models\Fichaje;
use App\Models\Users\User;
use Livewire\Component;
use Livewire\WithPagination;

class JornadasTable extends Component
{
    use WithPagination;

    public $buscar;
    public $selectedUser;
    public $selectedAnio;
    public $selectedMes;
    public $selectedDepartamento;
    public $fechaInicio;
    public $fechaFin;
    public $usuarios;
    public $departamentos;
    public $perPage = 10;
    public $sortColumn = 'fecha'; // Columna por defecto
    public $sortDirection = 'desc'; // Dirección por defecto
    protected $jornadas; // Propiedad protegida para las jornadas

    public function mount(){
        $this->usuarios = User::where('inactive', 0)->get();
        $this->departamentos = \App\Models\Users\UserDepartament::all();
        $this->fechaInicio = now()->startOfMonth()->format('Y-m-d');
        $this->fechaFin = now()->endOfMonth()->format('Y-m-d');
    }

    public function render()
    {
        $this->actualizarJornadas();
        return view('livewire.jornadas-table', [
            'jornadas' => $this->jornadas
        ]);
    }

    protected function actualizarJornadas()
    {
        $query = Fichaje::with('user')
            ->when($this->fechaInicio && $this->fechaFin, function ($query) {
                $query->whereBetween('fecha', [$this->fechaInicio, $this->fechaFin]);
            })
            ->when($this->selectedUser, function ($query) {
                $query->where('user_id', $this->selectedUser);
            })
            ->when($this->selectedDepartamento, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('admin_user_department_id', $this->selectedDepartamento);
                });
            })
            ->when($this->selectedAnio, function ($query) {
                $query->whereYear('fecha', $this->selectedAnio);
            })
            ->when($this->selectedMes, function ($query) {
                $query->whereMonth('fecha', $this->selectedMes);
            })
            ->when($this->buscar, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->buscar . '%')
                      ->orWhere('surname', 'like', '%' . $this->buscar . '%');
                });
            });

        $query->orderBy($this->sortColumn, $this->sortDirection);

        $this->jornadas = $this->perPage === 'all' ? $query->get() : $query->paginate(is_numeric($this->perPage) ? $this->perPage : 10);
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
        if (in_array($propertyName, ['buscar', 'selectedUser', 'selectedDepartamento', 'selectedAnio', 'selectedMes', 'fechaInicio', 'fechaFin'])) {
            $this->resetPage(); // Resetear la paginación solo cuando estos filtros cambien.
        }
    }
    
    public function exportarExcel()
    {
        $filtros = [
            'fecha_inicio' => $this->fechaInicio,
            'fecha_fin' => $this->fechaFin,
            'usuario_id' => $this->selectedUser,
            'departamento_id' => $this->selectedDepartamento,
            'año' => $this->selectedAnio,
            'mes' => $this->selectedMes,
            'buscar' => $this->buscar
        ];
        
        return redirect()->route('horas.export', $filtros);
    }
}