<?php

namespace App\Http\Controllers\Turnos;

use App\Http\Controllers\Controller;
use App\Models\Salones\Salon;
use App\Models\Turnos\Turno;
use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TurnosController extends Controller
{
    public function index()
    {
        return view('turnos.index');
    }

    public function generarTurnos()
    {
        $lastTurnoDate =Turno::orderBy('fecha', 'desc')->first();

        if(isset($lastTurnoDate)){
            $startDate = Carbon::parse($lastTurnoDate->fecha)->addDay(); // Fecha de inicio
            $endDate = $startDate->copy()->endOfWeek(); // Generar turnos por un mes
        }else{
            $startDate = now()->startOfWeek(); // Fecha de inicio
            $endDate = now()->endOfWeek(); // Generar turnos por un mes
        }
        $salones = Salon::all();

        // Obtener empleados fijos y variables
        $fixedEmployees = User::where('access_level_id', 5)->where('correturno', 0)->get();
        $variableEmployees = User::where('access_level_id', 5)->where('correturno', 1)->get();

        // Mapa de turnos asignados por salón y día
        $salonAssignments = [];
        foreach ($salones as $salon) {
            $salonAssignments[$salon->id] = [];
        }

        // Paso 1: Asignar días libres
        $assignedFreeDays = []; // Días libres asignados globalmente
        foreach ($fixedEmployees as $employee) {
            $this->assignFreeDays($employee, $startDate, $endDate, $assignedFreeDays);
        }
        foreach ($variableEmployees as $employee) {
            $this->assignFreeDays($employee, $startDate, $endDate, $assignedFreeDays);
        }

        // Paso 2: Asignar turnos
        foreach ($fixedEmployees as $employee) {
            $this->assignShifts($employee, $startDate, $endDate, $salonAssignments);
        }
        foreach ($variableEmployees as $employee) {
            $this->assignVariableShifts($employee, $startDate, $endDate, $salonAssignments);
        }

        return redirect()->route('turnos.index')->with('toast', [
            'icon' => 'success',
            'mensaje' => 'Turnos creados'
        ]);
    }

    private function assignFreeDays(User $employee, Carbon $startDate, Carbon $endDate, &$assignedFreeDays)
    {
        $lastFreeDays = $this->getLastFreeDays($employee); // Últimos días libres del empleado

        // Calcular los próximos días libres para el empleado
        $freeDays = $this->calculateNextFreeDays($employee, $startDate, $lastFreeDays, $assignedFreeDays);

        // Asignar los días libres al empleado dentro del rango de fechas
        foreach ($freeDays as $freeDay) {
            if ($freeDay->between($startDate, $endDate)) {
                Turno::create([
                    'user_id' => $employee->id,
                    'salon_id' => $employee->salon_id,
                    'fecha' => $freeDay->format('Y-m-d'),
                    'libre' => true,
                ]);
                $assignedFreeDays[$freeDay->format('Y-m-d')] = true; // Registrar el día libre globalmente
            }
        }
    }

    private function assignShifts(User $employee, Carbon $startDate, Carbon $endDate, &$salonAssignments)
    {
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            // Verificar si el empleado ya tiene un turno o día libre ese día
            $hasShiftOrFreeDay = Turno::where('user_id', $employee->id)
                ->where('fecha', $date->format('Y-m-d'))
                ->exists();

            if ($hasShiftOrFreeDay) {
                continue;
            }

            // Asignar turnos de mañana y tarde
            if (!isset($salonAssignments[$employee->salon_id][$date->format('Y-m-d')])) {
                $salonAssignments[$employee->salon_id][$date->format('Y-m-d')] = ['mañana' => false, 'tarde' => false];
            }

            if (!$salonAssignments[$employee->salon_id][$date->format('Y-m-d')]['mañana']) {
                $this->assignShift($employee, $date, 'mañana');
                $salonAssignments[$employee->salon_id][$date->format('Y-m-d')]['mañana'] = true;
            } elseif (!$salonAssignments[$employee->salon_id][$date->format('Y-m-d')]['tarde']) {
                $this->assignShift($employee, $date, 'tarde');
                $salonAssignments[$employee->salon_id][$date->format('Y-m-d')]['tarde'] = true;
            }
        }
    }

    private function assignVariableShifts(User $employee, Carbon $startDate, Carbon $endDate, &$salonAssignments)
    {
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            foreach ($salonAssignments as $salonId => $dates) {
                if (!isset($dates[$date->format('Y-m-d')])) {
                    $salonAssignments[$salonId][$date->format('Y-m-d')] = ['mañana' => false, 'tarde' => false];
                }

                // Verificar si el empleado ya tiene un turno o día libre ese día
                $hasShiftOrFreeDay = Turno::where('user_id', $employee->id)
                    ->where('fecha', $date->format('Y-m-d'))
                    ->exists();

                if ($hasShiftOrFreeDay) {
                    continue;
                }

                // Asignar turnos disponibles
                if (!$salonAssignments[$salonId][$date->format('Y-m-d')]['mañana']) {
                    $this->assignShift($employee, $date, 'mañana', $salonId);
                    $salonAssignments[$salonId][$date->format('Y-m-d')]['mañana'] = true;
                    break;
                } elseif (!$salonAssignments[$salonId][$date->format('Y-m-d')]['tarde']) {
                    $this->assignShift($employee, $date, 'tarde', $salonId);
                    $salonAssignments[$salonId][$date->format('Y-m-d')]['tarde'] = true;
                    break;
                }
            }
        }
    }

    private function assignShift(User $employee, Carbon $date, $horario, $salonId = null)
    {
        Turno::create([
            'user_id' => $employee->id,
            'salon_id' => $salonId ?? $employee->salon_id,
            'fecha' => $date->format('Y-m-d'),
            'horario' => $horario,
            'libre' => false,
        ]);
    }

    private function getLastFreeDays(User $employee)
    {
        $lastFreeShifts = Turno::where('user_id', $employee->id)
            ->where('libre', true)
            ->orderBy('fecha', 'desc')
            ->take(2) // Tomar los últimos 2 días libres
            ->get();

        if ($lastFreeShifts->count() === 2) {
            $lastFreeDate1 = Carbon::parse($lastFreeShifts[0]->fecha);
            $lastFreeDate2 = Carbon::parse($lastFreeShifts[1]->fecha);
            return [$lastFreeDate1, $lastFreeDate2];
        }

        return [];
    }

    private function calculateNextFreeDays(User $employee, Carbon $currentDate, $lastFreeDays, &$assignedFreeDays)
    {

        // Si no hay días libres previos, empezar con sábado y domingo
        if (empty($lastFreeDays)) {
            $nextFreeDay1 = $currentDate->copy();
            $nextFreeDay2 = $nextFreeDay1->copy()->addDay();
            while (isset($assignedFreeDays[$nextFreeDay1->format('Y-m-d')]) || isset($assignedFreeDays[$nextFreeDay2->format('Y-m-d')])) {
                $nextFreeDay1->addDay();
                $nextFreeDay2->addDay();
            }
        } else {
            if ($lastFreeDays[0]->isSaturday() && $lastFreeDays[1]->isSunday()) {
                $nextFreeDay1 = $lastFreeDays[0]->copy()->addDays(2); // Lunes
                $nextFreeDay2 = $lastFreeDays[1]->copy()->addDays(2); // Martes
            } else {
                $nextFreeDay1 = $lastFreeDays[0]->copy()->addWeek()->addDays(2);
                $nextFreeDay2 = $lastFreeDays[1]->copy()->addWeek()->addDays(2);
            }
        }

        // Asegurarse de que los días libres no coincidan con otros empleados

        return [$nextFreeDay1, $nextFreeDay2];
    }
}
