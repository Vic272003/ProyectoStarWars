<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\controllerStarship;

/**
 * Comando para crear las naves en la base de datos php artisan starship:create
 */
class CreateStarshipCommand extends Command
{
    protected $signature = 'starship:create';

    protected $description = 'Create starships from swapi.dev';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $pilotController = new controllerStarship();
        $pilotController->createStarship();
        $this->info('Starhips created successfully!');
    }
}
