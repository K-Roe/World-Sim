<?php

namespace App\Simulation;

use Illuminate\Console\OutputStyle;

class HunterGather
{
    protected $world;
    protected OutputStyle $output;


    protected array $animals = [
        ['name' => 'Fish',  'danger' => 1, 'food' => 1],
        ['name' => 'Rabbit', 'danger' => 2, 'food' => 2],
        ['name' => 'Deer',  'danger' => 3, 'food' => 3],
        ['name' => 'Dog',   'danger' => 4, 'food' => 4],
        ['name' => 'Boar',  'danger' => 5, 'food' => 5],
        ['name' => 'Wolf',  'danger' => 6, 'food' => 6],
        ['name' => 'Bear',  'danger' => 7, 'food' => 7],
        ['name' => 'Tiger', 'danger' => 8, 'food' => 8],
        ['name' => 'Crocodile', 'danger' => 9, 'food' => 9],
    ];
    public function __construct(World $world, OutputStyle $output)
    {
        $this->world = $world;
        $this->output = $output;

    }

    public function hunter(Person $person): void
    {
        if($person->age >= 60 || $person->age < 14) {
            return;
        }

        $hunting = $this->animals[array_rand($this->animals)];

        if ($person->hunterGather >= $hunting['danger']) {
            $this->world->food += $hunting['food'];
            $person->hunterGather = min(10, $person->hunterGather + 1);
            $this->output->writeln("👶 {$person->name} killed a  {$hunting['name']}.");

        } else {
            // todo injury or fail penalty to add later
            $person->mood -= rand(1, 5);
        }
    }
}

