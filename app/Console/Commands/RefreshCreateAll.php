<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\controllerStarship;
use App\Http\Controllers\controllerPilot;

/**
 * Comando para crear las naves en la base de datos php artisan all:create
 */
class RefreshCreateAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'all:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Ejecutar migrate:refresh
        Artisan::call('migrate:refresh');

        //Creamos las naves
        $pilotController = new controllerStarship();
        $pilotController->createStarship();
        $this->info('Starhips created successfully!');
        
        //Creamos los pilotos
        $pilotController = new controllerPilot();
        $pilotController->createPilot();
        $this->info('Pilots created successfully!');
    }
}
