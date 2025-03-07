<?php

namespace App\Exports;

use App\Models\Jornada\Jornada;
use App\Models\Users\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class JornadasExport implements FromCollection, WithHeadings
{
    protected $fechaInicio;
    protected $fechaFin;

    public function __construct($fechaInicio, $fechaFin)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }

    public function collection()
    {
        $periodo = Carbon::parse($this->fechaInicio)->daysUntil($this->fechaFin);
        $users = User::where('inactive', 0)->get();
        $data = [];

        foreach ($users as $usuario) {
            $userRow = [
                'Usuario' => $usuario->name . ' ' . $usuario->surname,
                'Total Horas Trabajadas' => 0
            ];
            $totalHoras = 0;

            foreach ($periodo as $dia) {
                if ($dia->isWeekday()) {
                    $horasTrabajadas = $this->horasTrabajadasDia($dia, $usuario->id);
                    $userRow[$dia->format('Y-m-d')] = $horasTrabajadas;
                    $totalHoras += $horasTrabajadas;
                }
            }

            $userRow['Total Horas Trabajadas'] = $totalHoras;
            $data[] = $userRow;
        }

        return collect($data);
    }

    public function headings(): array
    {
        $headings = ['Usuario', 'Total Horas Trabajadas'];
        $periodo = Carbon::parse($this->fechaInicio)->daysUntil($this->fechaFin);

        foreach ($periodo as $dia) {
            if ($dia->isWeekday()) {
                $headings[] = $dia->format('Y-m-d');
            }
        }

        return $headings;
    }

    protected function horasTrabajadasDia($dia, $id)
    {
        $jornadas = Jornada::where('admin_user_id', $id)
                           ->whereDate('start_time', $dia)
                           ->whereNotNull('end_time')
                           ->get();
        $totalMinutes = 0;

        foreach ($jornadas as $jornada) {
            $startTime = Carbon::parse($jornada->start_time);
            $endTime = $jornada->end_time ? Carbon::parse($jornada->end_time) : now();
            $totalMinutes += $startTime->diffInMinutes($endTime);
        }

        return $totalMinutes / 60; // Convert minutes to hours
    }
}
