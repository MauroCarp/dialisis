<?php

return [

    'title' => 'Iniciar Sesión',

    'heading' => 'Centro de Hemodiálisis - Acceso al Sistema',

    'actions' => [

        'register' => [
            'before' => 'o',
            'label' => 'Crear una cuenta',
        ],

        'request_password_reset' => [
            'label' => '¿Olvidó su contraseña?',
        ],

    ],

    'form' => [

        'username' => [
            'label' => 'Nombre de Usuario',
        ],

        'email' => [
            'label' => 'Correo electrónico',
        ],

        'password' => [
            'label' => 'Contraseña',
        ],

        'remember' => [
            'label' => 'Recordarme',
        ],

        'actions' => [

            'authenticate' => [
                'label' => 'Iniciar sesión',
            ],

        ],

    ],

    'messages' => [

        'failed' => 'Las credenciales no coinciden con nuestros registros.',

    ],

    'notifications' => [

        'throttled' => [
            'title' => 'Demasiados intentos de acceso',
            'body' => 'Por favor, intente nuevamente en :seconds segundos.',
        ],

    ],

];
