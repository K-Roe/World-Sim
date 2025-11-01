<?php

namespace App\Simulation;

class Family
{
    public string $name;
    /** @var Person[] */
    public array $members = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addMember(Person $person): void
    {
        $this->members[] = $person;
    }

    public function stats(): array
    {
        $alive = count(array_filter($this->members, fn($m) => $m->alive));
        $dead = count($this->members) - $alive;
        $avgAge = round(array_sum(array_map(fn($m) => $m->age, $this->members)) / count($this->members), 1);
        return compact('alive', 'dead', 'avgAge');
    }
}
