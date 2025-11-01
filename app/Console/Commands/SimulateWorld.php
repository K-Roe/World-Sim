<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Simulation\Simulator;

class SimulateWorld extends Command
{
    protected $signature = 'world:simulate {--years=150}';
    protected $description = 'Run the evolving AI Life Simulation';

    public function handle(): int
    {
        $sim = new Simulator($this->output);
        $sim->run((int) $this->option('years'));

        $this->info("\nSimulation complete after {$sim->year} years.");
        return self::SUCCESS;
    }
}
