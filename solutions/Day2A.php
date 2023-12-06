<?php

class Day2A extends BaseSolution
{
    private const TOTAL = [
        'red' => 12,
        'green' => 13,
        'blue' => 14,
    ];

    protected function solution(string $input): string
    {
        $ids = [];
        $lines = explode("\n", $input);
        foreach ($lines as $line) {
            preg_match('/Game (?P<id>[\d]+):/', $line, $match);
            $id = $match['id'];
            $results = explode(':', $line)[1];
            if ($this->isGamePossible($results)) {
                $ids[] = $id;
            }
        }

        return array_sum($ids);
    }

    private function isGamePossible(string $results): bool
    {
        $sets = explode(';', $results);
        foreach ($sets as $set) {
            preg_match_all('/(?P<number>[\d]+) (?P<color>red|blue|green)/', $set, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                if ($match['number'] > self::TOTAL[$match['color']]) {
                    return false;
                }
            }
        }

        return true;
    }
}