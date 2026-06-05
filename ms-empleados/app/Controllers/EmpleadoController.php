<?php
namespace App\Controllers;

use App\Models\Empleado;
use Exception;

class EmpleadoController
{
    function getEmpleados($filtros = [])
    {
        $query = Empleado::query();

        if (!empty($filtros['documento'])) {
            $query->where('documento', $filtros['documento']);
        }

        if (!empty($filtros['area'])) {
            $query->where('area', $filtros['area']);
        }

        if (!empty($filtros['estado'])) {
            $query->where('estado', $filtros['estado']);
        }

        return $query->get();
    }

    function getEmpleado($id)
    {
        $empleado = Empleado::find($id);

        if (empty($empleado)) {
            throw new Exception("Empleado $id no existe", 2);
        }

        return $empleado;
    }

    function guardarEmpleado($data)
    {
        if (
            empty($data['nombres'])      ||
            empty($data['apellidos'])    ||
            empty($data['documento'])    ||
            empty($data['correo'])       ||
            empty($data['telefono'])     ||
            empty($data['cargo'])        ||
            empty($data['area'])         ||
            empty($data['fecha_ingreso'])
        ) {
            throw new Exception("Todos los campos son obligatorios", 1);
        }

        $documentoExiste = Empleado::where('documento', $data['documento'])->first();
        if (!empty($documentoExiste)) {
            throw new Exception("El documento ya está registrado", 3);
        }

        $correoExiste = Empleado::where('correo', $data['correo'])->first();
        if (!empty($correoExiste)) {
            throw new Exception("El correo ya está registrado", 4);
        }

        if (!strtotime($data['fecha_ingreso'])) {
            throw new Exception("Fecha de ingreso inválida", 5);
        }

        $empleado = new Empleado();
        $empleado->nombres      = $data['nombres'];
        $empleado->apellidos    = $data['apellidos'];
        $empleado->documento    = $data['documento'];
        $empleado->correo       = $data['correo'];
        $empleado->telefono     = $data['telefono'];
        $empleado->cargo        = $data['cargo'];
        $empleado->area         = $data['area'];
        $empleado->fecha_ingreso = $data['fecha_ingreso'];
        $empleado->estado       = 'activo';
        $empleado->save();

        return $empleado;
    }

    function modificarEmpleado($id, $data)
    {
        $empleado = $this->getEmpleado($id);

        $documentoExiste = Empleado::where('documento', $data['documento'])
            ->where('id', '!=', $id)
            ->first();
        if (!empty($documentoExiste)) {
            throw new Exception("El documento ya está registrado", 3);
        }

        $correoExiste = Empleado::where('correo', $data['correo'])
            ->where('id', '!=', $id)
            ->first();
        if (!empty($correoExiste)) {
            throw new Exception("El correo ya está registrado", 4);
        }

        $empleado->nombres      = $data['nombres']      ?? $empleado->nombres;
        $empleado->apellidos    = $data['apellidos']    ?? $empleado->apellidos;
        $empleado->documento    = $data['documento']    ?? $empleado->documento;
        $empleado->correo       = $data['correo']       ?? $empleado->correo;
        $empleado->telefono     = $data['telefono']     ?? $empleado->telefono;
        $empleado->cargo        = $data['cargo']        ?? $empleado->cargo;
        $empleado->area         = $data['area']         ?? $empleado->area;
        $empleado->fecha_ingreso = $data['fecha_ingreso'] ?? $empleado->fecha_ingreso;
        $empleado->save();

        return $empleado;
    }

    function cambiarEstado($id, $estado)
    {
        if (!in_array($estado, ['activo', 'inactivo'])) {
            throw new Exception("Estado inválido", 5);
        }

        $empleado = $this->getEmpleado($id);
        $empleado->estado = $estado;
        $empleado->save();

        return $empleado;
    }
}