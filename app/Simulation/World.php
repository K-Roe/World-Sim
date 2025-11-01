<?php

namespace App\Simulation;

class World
{
    public int $temperature;
    public int $food;
    public int $danger;
    public string $season;

    public function __construct()
    {
        $this->temperature = rand(-5, 30);
        $this->food = 150;
        $this->danger = rand(0, 5);
        $this->season = 'spring';
    }

    public function update(): void
    {
        $this->temperature = max(-10, min(40, $this->temperature + rand(-2, 2)));
        $this->danger = rand(0, 8);
        $this->food = max(0, min(400, $this->food + rand(-15, 20)));
        $seasons = ['spring', 'summer', 'autumn', 'winter'];
        $this->season = $seasons[array_rand($seasons)];
    }

    public function describe(): string
    {
        return "🌍 World — {$this->season}, Temp {$this->temperature}°C, Food {$this->food}, Danger {$this->danger}";
    }
}
