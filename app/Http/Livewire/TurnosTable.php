<?php

namespace App\Http\Livewire;

use App\Models\Trunos\Turno;
use App\Models\Users\User;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class TurnosTable extends Component
{
    use WithPagination;

    public $currentWeekStart;
    public $currentWeekEnd;

    public function mount()
    {
        $this->currentWeekStart = Carbon::now()->startOfWeek(); // Inicio de la semana actual (Lunes)
        $this->currentWeekEnd = Carbon::now()->endOfWeek(); // Fin de la semana actual (Domingo)
    }

    public function render()
    {
        $empleados = User::whereHas('turnos')->with(['turnos' => function ($query) {
            $query->whereBetween('fecha', [$this->currentWeekStart, $this->currentWeekEnd])
                  ->orderBy('fecha');
        }])->get();

        return view('livewire.turnos-table', [
            'empleados' => $empleados,
            'weekDays' => $this->getWeekDays()
        ]);
    }

    public function previousWeek()
    {
        $this->currentWeekStart->subWeek();
        $this->currentWeekEnd->subWeek();
    }

    public function nextWeek()
    {
        $this->currentWeekStart->addWeek();
        $this->currentWeekEnd->addWeek();
    }

    private function getWeekDays()
    {
        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $days[] = $this->currentWeekStart->copy()->addDays($i)->format('Y-m-d');
        }
        return $days;
    }
}

