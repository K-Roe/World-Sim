<?php

namespace App\Simulation;

class Person
{
    public string $id;
    public string $name;
    public string $family;
    public string $sex;
    public int $age;
    public bool $alive = true;
    public ?string $partner = null;
    public array $children = [];
    public array $parents = [];
    public array $knowledge = [];
    public int $mood;
    public int $hunger;
    public array $memories = [];
    public int $hunterGather;

    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function isAdult(): bool
    {
        return $this->age >= 18;
    }

    public function canHaveChildren(): bool
    {
        return $this->alive && $this->age < 45 && $this->hunger < 70;
    }

    public function summary(): string
    {
        return "{$this->name} ({$this->sex}, age {$this->age}, " . ($this->alive ? 'alive' : 'dead') . ")";
    }
}
