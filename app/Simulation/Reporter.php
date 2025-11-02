<?php

namespace App\Simulation;

use Illuminate\Console\OutputStyle;

class Reporter
{
    protected OutputStyle $output;

    public function __construct(OutputStyle $output)
    {
        $this->output = $output;
    }

    public function world(World $world): void
    {
        $this->output->writeln($world->describe());
    }

    public function tribeSummary(Tribe $tribe, World $world): void
    {
        $alive = count(array_filter($tribe->people, fn($p) => $p->alive));
        $total = count($tribe->people);
        $food = $world->food;
        $this->output->writeln("📊 Population — Alive: {$alive} / Total: {$total} Food: {$food}");
    }

    public function familyTrees(Tribe $tribe): void
    {
        $this->output->writeln("\n--- Family Trees ---");

        $alive = count(array_filter($tribe->people, fn($p) => $p->alive));
        $total = count($tribe->people);
        $this->output->writeln("📊 Population — Alive: {$alive} / Total: {$total}");

        foreach ($tribe->families as $family) {
            $stats = $family->stats();
            $this->output->writeln("\n🌳 {$family->name} Family — " .
                count($family->members) . " members ({$stats['alive']} alive, {$stats['dead']} dead, avg age {$stats['avgAge']})");

            $roots = collect($family->members)->sortByDesc('age')->take(20);
            foreach ($roots as $root) {
                $this->output->writeln("  👤 {$root->summary()}");
            }
        }
    }
}
