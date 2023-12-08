<?php

class Day8B extends BaseSolution
{
    protected function solution(string $input): string
    {
        $lines = explode("\n", $input);
        $directions = array_map(
            fn(string $letter) => $letter === 'L' ? 0 : 1,
            str_split(array_shift($lines)),
        );
        array_shift($lines);

        $map = [];
        foreach ($lines as $line) {
            preg_match('/(?P<index>[A-Z0-9]{3}) = \((?P<left>[A-Z0-9]{3}), (?P<right>[A-Z0-9]{3})\)/', $line, $matches);
            $map[$matches['index']] = [$matches['left'], $matches['right']];
        }

        $nodes = array_filter(array_keys($map), fn ($node) => str_ends_with($node, 'A'));

        $passesUntilZ = [];
        foreach ($nodes as $node) {
            $directionsCopy = $directions;
            $i = 0;
            while (!str_ends_with($node, 'Z')) {
                $direction = array_shift($directionsCopy);
                $directionsCopy[] = $direction;
                $node = $map[$node][$direction];
                $i++;
            }
            $passesUntilZ[$node] = $i;
        }

        return array_reduce(array_unique($passesUntilZ), 'gmp_lcm', 1);
    }
}