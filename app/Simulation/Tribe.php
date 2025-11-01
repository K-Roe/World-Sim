<?php

namespace App\Simulation;

class Tribe
{
    /** @var Person[] */
    public array $people = [];
    /** @var Family[] */
    public array $families = [];

    protected array $sexes = ['Male', 'Female'];
    protected array $surnames = ['Stone', 'Flint', 'River', 'Ash', 'Wolf', 'Sky', 'Oak', 'Hill'];

    public function seed(int $count = 100): void
    {
        for ($i = 0; $i < $count; $i++) {
            $familyName = $this->surnames[array_rand($this->surnames)];
            $sex = $this->sexes[array_rand($this->sexes)];

            $person = new Person([
                'id' => uniqid(),
                'name' => $this->generateName() . ' ' . $familyName,
                'family' => $familyName,
                'sex' => $sex,
                'age' => rand(16, 40),
                'mood' => rand(40, 90),
                'hunger' => rand(0, 40),
                'knowledge' => ['foraging'],
            ]);

            $this->people[] = $person;
            $this->families[$familyName] ??= new Family($familyName);
            $this->families[$familyName]->addMember($person);
        }
    }

    // -------------------
    // SOCIAL BEHAVIOUR
    // -------------------

    public function reproduce(): array
    {
        $couples = $this->findCouples();
        $newChildren = [];

        foreach ($couples as [$a, $b]) {
            if ($a->canHaveChildren() && $b->canHaveChildren() && rand(1,100) <= 25) {
                $child = $this->addChild($a, $b);
                $newChildren[] = $child;
            }
        }

        return $newChildren;
    }

    protected function findCouples(): array
    {
        $adults = array_filter($this->people, fn($p) => $p->isAdult() && $p->alive);
        $males = array_values(array_filter($adults, fn($p) => $p->sex === 'Male'));
        $females = array_values(array_filter($adults, fn($p) => $p->sex === 'Female'));

        shuffle($males);
        shuffle($females);

        $pairs = [];
        $count = min(count($males), count($females));

        for ($i = 0; $i < $count; $i++) {
            if ($males[$i]->family === $females[$i]->family) continue; // avoid same-family
            $pairs[] = [$males[$i], $females[$i]];
        }

        return $pairs;
    }

    protected function addChild(Person $a, Person $b): Person
    {
        $sexes = ['Male', 'Female'];
        $surname = rand(0,1) ? $a->family : $b->family;

        $child = new Person([
            'id' => uniqid(),
            'name' => $this->generateName() . ' ' . $surname,
            'family' => $surname,
            'sex' => $sexes[array_rand($sexes)],
            'age' => 0,
            'mood' => rand(50, 90),
            'hunger' => rand(10, 20),
            'knowledge' => array_slice($a->knowledge, 0, rand(1, count($a->knowledge))),
            'parents' => [$a->id, $b->id],
        ]);

        $a->children[] = $child->id;
        $b->children[] = $child->id;

        $this->people[] = $child;
        $this->families[$surname]->addMember($child);

        return $child;
    }

    // -------------------
    // UTIL
    // -------------------

    protected function generateName(): string
    {
        $syllables = ['ka','lo','mi','ta','ra','su','ze','nu','vi','po','chi','do','la','mo','ri','sa','te','vo','wi'];
        return ucfirst($syllables[array_rand($syllables)] . $syllables[array_rand($syllables)]);
    }
}
