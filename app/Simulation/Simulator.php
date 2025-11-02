<?php

namespace App\Simulation;

use Illuminate\Console\OutputStyle;

class Simulator
{
    public World $world;
    public Tribe $tribe;
    protected Reporter $reporter;
    protected OutputStyle $output;
    public Need $need;
    public HunterGather $hunterGather;
    public int $year = 1;

    public function __construct(OutputStyle $output)
    {
        $this->output = $output;
        $this->reporter = new Reporter($output);
        $this->world = new World();
        $this->tribe = new Tribe();
        $this->need = new Need($this->world);
        $this->hunterGather = new HunterGather($this->world, $output);

    }

    public function run(int $years): void
    {
        $this->tribe->seed(50);

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

            if(!$person->alive){
                continue;
            }

            if($person->sex == 'Male') {
                $this->hunterGather->hunter($person);
            }

            $this->need->process($person);

            if ($person->hunger >= 100) {
                $person->alive = false;
                $this->output->writeln("💀 {$person->name} died of hunger at age {$person->age}.");
            } elseif ($person->age > 65) {
                // starts at 25 % at 66, grows roughly 5 % per extra year
                $chance = min(25 + (($person->age - 65) * 5), 100);

                if (rand(1, 100) <= $chance) {
                    $person->alive = false;
                    $this->output->writeln("💀 {$person->name} died of old age at age {$person->age} (chance {$chance}%).");
                }
            }
        }
        // 2. new births
        if($this->world->food > count($this->tribe->people)) {
            $newChildren = $this->tribe->reproduce();
            foreach ($newChildren as $child) {
                $this->output->writeln("👶 {$child->name} was born to the {$child->family} family.");
            }
        }

        $this->reporter->tribeSummary($this->tribe, $this->world);
        $this->year++;
    }
}
