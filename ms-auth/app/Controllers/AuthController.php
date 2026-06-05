<?php
namespace App\Controllers;

use App\Models\Usuario;
use Exception;

class AuthController
{
    function login($data)
    {
        if (empty($data['usuario']) || empty($data['contrasena'])) {
            throw new Exception("Usuario y contraseña son obligatorios", 1);
        }

        $usuario = Usuario::where('usuario', $data['usuario'])
            ->orWhere('correo', $data['usuario'])
            ->first();

        if (empty($usuario)) {
            throw new Exception("Credenciales incorrectas", 2);
        }

        if ($usuario->contrasena !== $data['contrasena']) {
            throw new Exception("Credenciales incorrectas", 2);
        }

        if ($usuario->estado !== 'activo') {
            throw new Exception("Usuario inactivo", 3);
        }

        $token = bin2hex(random_bytes(32));

        $usuario->token = $token;
        $usuario->sesion_activa = true;
        $usuario->save();

        return [
            'token'   => $token,
            'usuario' => $usuario->usuario,
            'nombre'  => $usuario->nombre,
            'rol'     => $usuario->rol
        ];
    }

    function logout($token)
    {
        if (empty($token)) {
            throw new Exception("Token requerido", 1);
        }

        $usuario = Usuario::where('token', $token)->first();

        if (empty($usuario)) {
            throw new Exception("Token inválido", 2);
        }

        $usuario->token = null;
        $usuario->sesion_activa = false;
        $usuario->save();

        return true;
    }

    function validarSesion($token)
    {
        if (empty($token)) {
            throw new Exception("Token requerido", 1);
        }

        $usuario = Usuario::where('token', $token)
            ->where('sesion_activa', true)
            ->first();

        if (empty($usuario)) {
            throw new Exception("Sesión inválida o expirada", 2);
        }

        return $usuario;
    }
}