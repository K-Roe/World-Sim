<?php

namespace App\Simulation;

class Need
{
    protected $world;

    public function __construct(World $world)
    {
        $this->world = $world;
    }

    public function process(Person $person): void
    {
        $person->age++;

        if ($this->world->food > 0) {
            $person->hunger = max(0, $person->hunger - 1);
            $this->world->food--;
        } else {
            $person->hunger = min(100, $person->hunger + 1);
        }
    }
}

