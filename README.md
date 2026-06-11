# Microservisios_Gestion_Incapacidades_Medicas

composer install

# Terminal 1
ms-auth
php -S 127.0.0.1:8001 -t public

# Terminal 2
ms-empleados
php -S 127.0.0.1:8002 -t public

# Terminal 3
ms-incapacidades
php -S 127.0.0.1:8003 -t public

# Terminal 4
ms-seguimiento
php -S 127.0.0.1:8004 -t public

# Terminal 5
frontend-incapacidades
php -S 127.0.0.1:5500