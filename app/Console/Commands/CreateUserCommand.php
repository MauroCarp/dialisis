<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:user {name} {username} {email?} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user with username';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $username = $this->argument('username');
        $email = $this->argument('email') ?? $username . '@hemodialisis.local';
        $password = $this->option('password') ?? $this->secret('¿Cuál es la contraseña?');

        $user = User::create([
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info("Usuario creado exitosamente:");
        $this->line("Nombre: {$user->name}");
        $this->line("Username: {$user->username}");
        $this->line("Email: {$user->email}");
    }
}
