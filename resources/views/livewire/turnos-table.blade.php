<div>
    <div class="d-flex justify-content-between mb-3">
        <button wire:click="previousWeek" class="btn btn-primary">← Semana Anterior</button>
        <h4 class="text-center">Semana del {{ \Carbon\Carbon::parse($currentWeekStart)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($currentWeekEnd)->format('d/m/Y') }}</h4>
        <button wire:click="nextWeek" class="btn btn-primary">Semana Siguiente →</button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Empleado</th>
                    @foreach ($weekDays as $day)
                        <th>{{ \Carbon\Carbon::parse($day)->format('D d/m') }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($empleados as $empleado)
                    <tr>
                        <td>{{ $empleado->name }}</td>
                        @foreach ($weekDays as $day)
                            @php
                                $turno = $empleado->turnos->where('fecha', $day)->first();
                            @endphp
                            <td>
                                @if ($turno)
                                    @if ($turno->libre)
                                        <span class="badge bg-success">Libre</span>
                                    @else
                                        <span class="badge bg-primary">{{optional($turno->salon)->nombre}}</span>
                                        <br>
                                        <small>{{ $turno->horario }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
