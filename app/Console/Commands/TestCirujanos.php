<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cirujano;

class TestCirujanos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:cirujanos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test cirujanos data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verificando cirujanos...');
        
        $total = Cirujano::count();
        $this->info("Total cirujanos en BD: {$total}");
        
        $cirujanos = Cirujano::all();
        foreach($cirujanos as $cirujano) {
            $this->info("ID: {$cirujano->id} - {$cirujano->nombre} {$cirujano->apellido} (Baja: {$cirujano->fechabaja})");
        }
        
        $sinBaja = Cirujano::whereNull('fechabaja')->count();
        $this->info("Cirujanos sin fecha de baja: {$sinBaja}");
        
        // Probar nueva lógica
        $activos = Cirujano::where(function($query) {
            $query->whereNull('fechabaja')
                  ->orWhere('fechabaja', '<=', '1900-01-02');
        })->count();
        $this->info("Cirujanos activos (nueva lógica): {$activos}");
        
        if ($total == 0) {
            $this->info('Creando cirujano de prueba...');
            Cirujano::create([
                'nombre' => 'Dr. Juan',
                'apellido' => 'Pérez',
                'matricula' => '12345'
            ]);
            $this->info('Cirujano de prueba creado.');
        }
    }
}
