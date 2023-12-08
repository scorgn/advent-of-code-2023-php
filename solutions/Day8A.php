<?php
class Day8A extends BaseSolution
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
            preg_match('/(?P<index>[A-Z]{3}) = \((?P<left>[A-Z]{3}), (?P<right>[A-Z]{3})\)/', $line, $matches);
            $map[$matches['index']] = [$matches['left'], $matches['right']];
        }

        $step = 'AAA';
        $i = 0;
        while ($step !== 'ZZZ') {
            $direction = array_shift($directions);
            $directions[] = $direction;
            $step = $map[$step][$direction];
            $i++;
        }

        return $i;
    }
}