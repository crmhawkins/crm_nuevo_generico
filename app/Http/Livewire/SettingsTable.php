<?php

namespace App\Http\Livewire;

use App\Models\Company\CompanyDetails;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class SettingsTable extends Component
{
    use WithPagination;


    public $buscar;
    public $perPage = 10;
    public $sortColumn = 'created_at'; // Columna por defecto
    public $sortDirection = 'desc'; // Dirección por defecto
    protected $tasks;

    public function mount(){

    }

    public function render()
    {
        $this->actualizartareas(); // Ahora se llama directamente en render para refrescar los clientes.
        return view('livewire.settings-table', ['companies' => $this->tasks, ]);
    }


    protected function actualizartareas(){
        $query = CompanyDetails::when($this->buscar, function ($query) {
                    $query->where('title', 'like', '%' . $this->buscar . '%')
                          ->orWhere('description', 'like', '%' . $this->buscar . '%')
                          ->orWhereHas('presupuesto', function($q) {
                            $q->where('budgets.reference', 'like', '%' . $this->buscar . '%');
                        });
                });

        $query->orderBy($this->sortColumn, $this->sortDirection);

        // Verifica si se seleccionó 'all' para mostrar todos los registros
        $this->tasks = $this->perPage === 'all' ? $query->get() : $query->paginate(is_numeric($this->perPage) ? $this->perPage : 10);
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
        if (in_array($propertyName, ['buscar','selectedEstado', 'selectedCategoria', 'selectedCliente', 'selectedGestor','selectedEmpleado','selectedDepartamento'])) {
            $this->resetPage();
        }
    }

    public function updatingPerPage(){
        $this->resetPage();
    }




}
