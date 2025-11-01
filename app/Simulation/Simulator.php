<?php

namespace App\Simulation;

use Illuminate\Console\OutputStyle;

class Simulator
{
    public World $world;
    public Tribe $tribe;
    protected Reporter $reporter;
    protected OutputStyle $output;
    public int $year = 1;

    public function __construct(OutputStyle $output)
    {
        $this->output = $output;
        $this->reporter = new Reporter($output);
        $this->world = new World();
        $this->tribe = new Tribe();
    }

    public function run(int $years = 150): void
    {
        $this->tribe->seed(10);

        for ($i = 0; $i < $years; $i++) {
            $this->simulateYear();
        }

//        $this->reporter->familyTrees($this->tribe);
    }

    protected function simulateYear(): void
    {
        $this->output->info("\n=== Year {$this->year} ===");
        $this->world->update();
        $this->reporter->world($this->world);

        // simple food/hunger/mortality loop for now
        foreach ($this->tribe->people as $person) {
            if (!$person->alive) continue;

            $person->age++;
            $person->hunger += rand(0, 10);
            if ($person->hunger > 100 || $person->age > 80) {
                $person->alive = false;
                $this->output->writeln("💀 {$person->name} died at age {$person->age}.");
            }
        }
        // 2. new births
        $newChildren = $this->tribe->reproduce();
        foreach ($newChildren as $child) {
            $this->output->writeln("👶 {$child->name} was born to the {$child->family} family.");
        }

        $this->reporter->tribeSummary($this->tribe);
        $this->year++;
    }
}
