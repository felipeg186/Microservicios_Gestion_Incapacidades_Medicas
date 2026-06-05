<?php
namespace App\Controllers;

use App\Models\Incapacidad;
use Exception;

class IncapacidadController
{
    function getIncapacidades($filtros = [])
    {
        $query = Incapacidad::query();

        if (!empty($filtros['empleado_id'])) {
            $query->where('empleado_id', $filtros['empleado_id']);
        }

        if (!empty($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        }

        if (!empty($filtros['tipo'])) {
            $query->where('tipo', $filtros['tipo']);
        }

        if (!empty($filtros['fecha_inicio'])) {
            $query->where('fecha_inicio', '>=', $filtros['fecha_inicio']);
        }

        if (!empty($filtros['fecha_fin'])) {
            $query->where('fecha_fin', '<=', $filtros['fecha_fin']);
        }

        return $query->get();
    }

    function getIncapacidad($id)
    {
        $incapacidad = Incapacidad::find($id);

        if (empty($incapacidad)) {
            throw new Exception("Incapacidad $id no existe", 2);
        }

        return $incapacidad;
    }

    function guardarIncapacidad($data)
    {
        if (
            empty($data['empleado_id'])        ||
            empty($data['fecha_inicio'])        ||
            empty($data['fecha_fin'])           ||
            empty($data['tipo'])                ||
            empty($data['diagnostico_general']) ||
            empty($data['entidad_medica'])
        ) {
            throw new Exception("Todos los campos obligatorios son requeridos", 1);
        }

        if (!strtotime($data['fecha_inicio']) || !strtotime($data['fecha_fin'])) {
            throw new Exception("Fechas inválidas", 3);
        }

        if (strtotime($data['fecha_fin']) < strtotime($data['fecha_inicio'])) {
            throw new Exception("La fecha fin no puede ser menor a la fecha inicio", 4);
        }

        $duplicado = Incapacidad::where('empleado_id', $data['empleado_id'])
            ->where(function ($query) use ($data) {
                $query->whereBetween('fecha_inicio', [$data['fecha_inicio'], $data['fecha_fin']])
                      ->orWhereBetween('fecha_fin', [$data['fecha_inicio'], $data['fecha_fin']]);
            })->first();

        if (!empty($duplicado)) {
            throw new Exception("Ya existe una incapacidad en ese rango de fechas", 5);
        }

        $diasIncapacidad = (int) ceil(
            (strtotime($data['fecha_fin']) - strtotime($data['fecha_inicio'])) / 86400
        ) + 1;

        $incapacidad = new Incapacidad();
        $incapacidad->empleado_id        = $data['empleado_id'];
        $incapacidad->fecha_inicio       = $data['fecha_inicio'];
        $incapacidad->fecha_fin          = $data['fecha_fin'];
        $incapacidad->tipo               = $data['tipo'];
        $incapacidad->diagnostico_general = $data['diagnostico_general'];
        $incapacidad->entidad_medica     = $data['entidad_medica'];
        $incapacidad->observaciones      = $data['observaciones'] ?? null;
        $incapacidad->dias_incapacidad   = $diasIncapacidad;
        $incapacidad->estado             = 'registrada';
        $incapacidad->save();

        return $incapacidad;
    }

    function modificarIncapacidad($id, $data)
    {
        $incapacidad = $this->getIncapacidad($id);

        $fechaInicio = $data['fecha_inicio'] ?? $incapacidad->fecha_inicio;
        $fechaFin    = $data['fecha_fin']    ?? $incapacidad->fecha_fin;

        if (strtotime($fechaFin) < strtotime($fechaInicio)) {
            throw new Exception("La fecha fin no puede ser menor a la fecha inicio", 4);
        }

        $diasIncapacidad = (int) ceil(
            (strtotime($fechaFin) - strtotime($fechaInicio)) / 86400
        ) + 1;

        $incapacidad->fecha_inicio       = $fechaInicio;
        $incapacidad->fecha_fin          = $fechaFin;
        $incapacidad->observaciones      = $data['observaciones'] ?? $incapacidad->observaciones;
        $incapacidad->estado             = $data['estado']        ?? $incapacidad->estado;
        $incapacidad->dias_incapacidad   = $diasIncapacidad;
        $incapacidad->save();

        return $incapacidad;
    }

    function finalizarIncapacidad($id)
    {
        $incapacidad = $this->getIncapacidad($id);
        $incapacidad->estado = 'finalizada';
        $incapacidad->save();

        return $incapacidad;
    }
}