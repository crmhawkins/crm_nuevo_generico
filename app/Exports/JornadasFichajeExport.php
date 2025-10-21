<?php

namespace App\Exports;

use App\Models\Fichaje;
use App\Models\Users\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JornadasFichajeExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $fechaInicio;
    protected $fechaFin;
    protected $usuarioFiltro;
    protected $departamentoFiltro;
    protected $añoFiltro;
    protected $mesFiltro;
    protected $buscarFiltro;

    public function __construct($fechaInicio, $fechaFin, $usuarioFiltro = null, $departamentoFiltro = null, $añoFiltro = null, $mesFiltro = null, $buscarFiltro = null)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->usuarioFiltro = $usuarioFiltro;
        $this->departamentoFiltro = $departamentoFiltro;
        $this->añoFiltro = $añoFiltro;
        $this->mesFiltro = $mesFiltro;
        $this->buscarFiltro = $buscarFiltro;
    }

    public function collection()
    {
        $query = Fichaje::with('user')
            ->whereBetween('fecha', [$this->fechaInicio, $this->fechaFin]);
        
        if ($this->usuarioFiltro) {
            $query->where('user_id', $this->usuarioFiltro);
        }
        
        if ($this->departamentoFiltro) {
            $query->whereHas('user', function ($q) {
                $q->where('admin_user_department_id', $this->departamentoFiltro);
            });
        }
        
        if ($this->añoFiltro) {
            $query->whereYear('fecha', $this->añoFiltro);
        }
        
        if ($this->mesFiltro) {
            $query->whereMonth('fecha', $this->mesFiltro);
        }
        
        if ($this->buscarFiltro) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->buscarFiltro . '%')
                  ->orWhere('surname', 'like', '%' . $this->buscarFiltro . '%');
            });
        }
        
        return $query->orderBy('fecha', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get();
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Usuario',
            'Departamento',
            'Hora Entrada',
            'Hora Salida',
            'Tiempo Trabajado (min)',
            'Tiempo Trabajado (h:m)',
            'Tiempo Pausa (min)',
            'Tiempo Pausa (h:m)',
            'Pausas Realizadas',
            'Estado',
            'Observaciones'
        ];
    }

    public function map($jornada): array
    {
        $tiempoTrabajadoHoras = floor($jornada->tiempo_trabajado / 60);
        $tiempoTrabajadoMinutos = $jornada->tiempo_trabajado % 60;
        $tiempoPausaHoras = floor($jornada->tiempo_pausa / 60);
        $tiempoPausaMinutos = $jornada->tiempo_pausa % 60;
        
        $pausasRealizadas = '';
        if ($jornada->hora_pausa_inicio) {
            if ($jornada->hora_pausa_fin) {
                $pausasRealizadas = $jornada->hora_pausa_inicio->format('H:i') . ' - ' . $jornada->hora_pausa_fin->format('H:i');
            } else {
                $pausasRealizadas = $jornada->hora_pausa_inicio->format('H:i') . ' - En curso';
            }
        } else {
            $pausasRealizadas = 'Sin pausas';
        }
        
        return [
            $jornada->fecha->format('d/m/Y'),
            $jornada->user ? ($jornada->user->name . ' ' . $jornada->user->surname) : 'Usuario no encontrado',
            $jornada->user ? $jornada->user->departamento->name ?? 'Sin departamento' : 'Sin departamento',
            $jornada->hora_entrada ? $jornada->hora_entrada->format('H:i:s') : '-',
            $jornada->hora_salida ? $jornada->hora_salida->format('H:i:s') : '-',
            $jornada->tiempo_trabajado,
            sprintf('%02d:%02d', $tiempoTrabajadoHoras, $tiempoTrabajadoMinutos),
            $jornada->tiempo_pausa,
            sprintf('%02d:%02d', $tiempoPausaHoras, $tiempoPausaMinutos),
            $pausasRealizadas,
            ucfirst($jornada->estado),
            $jornada->observaciones ?? ''
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
