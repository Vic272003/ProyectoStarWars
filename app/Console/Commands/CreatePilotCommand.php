<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\controllerPilot;
/**
 * Comando para crear a los pilotos con php artisan pilot:create
 */
class CreatePilotCommand extends Command
{
    protected $signature = 'pilot:create';

    protected $description = 'Create pilots from swapi.dev';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $pilotController = new controllerPilot();
        $pilotController->createPilot();
        $this->info('Pilots created successfully!');
    }
}
