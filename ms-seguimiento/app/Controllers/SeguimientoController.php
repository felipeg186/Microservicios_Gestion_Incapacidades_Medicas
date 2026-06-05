<?php
namespace App\Controllers;

use App\Models\Seguimiento;
use Exception;

class SeguimientoController
{
    function getSeguimientos($incapacidadId = null)
    {
        $query = Seguimiento::query();

        if (!empty($incapacidadId)) {
            $query->where('incapacidad_id', $incapacidadId);
        }

        return $query->orderBy('fecha', 'asc')->get();
    }

    function getSeguimiento($id)
    {
        $seguimiento = Seguimiento::find($id);

        if (empty($seguimiento)) {
            throw new Exception("Seguimiento $id no existe", 2);
        }

        return $seguimiento;
    }

    function guardarSeguimiento($data)
    {
        if (
            empty($data['incapacidad_id'])     ||
            empty($data['fecha'])              ||
            empty($data['comentario'])         ||
            empty($data['estado'])             ||
            empty($data['usuario_responsable'])
        ) {
            throw new Exception("Todos los campos son obligatorios", 1);
        }

        if (!strtotime($data['fecha'])) {
            throw new Exception("Fecha inválida", 3);
        }

        $estadosValidos = [
            'registrada',
            'en_revision',
            'aprobada',
            'rechazada',
            'finalizada'
        ];

        if (!in_array($data['estado'], $estadosValidos)) {
            throw new Exception("Estado inválido", 4);
        }

        $seguimiento = new Seguimiento();
        $seguimiento->incapacidad_id      = $data['incapacidad_id'];
        $seguimiento->fecha               = $data['fecha'];
        $seguimiento->comentario          = $data['comentario'];
        $seguimiento->estado              = $data['estado'];
        $seguimiento->usuario_responsable = $data['usuario_responsable'];
        $seguimiento->save();

        return $seguimiento;
    }

    function getHistorial($incapacidadId)
    {
        if (empty($incapacidadId)) {
            throw new Exception("El ID de incapacidad es obligatorio", 1);
        }

        return Seguimiento::where('incapacidad_id', $incapacidadId)
            ->orderBy('fecha', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();
    }
}