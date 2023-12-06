<?php

class Day1B extends BaseSolution {
    private const NUMBER_MAP = [
        1 => 'one',
        2 => 'two',
        3 => 'three',
        4 => 'four',
        5 => 'five',
        6 => 'six',
        7 => 'seven',
        8 => 'eight',
        9 => 'nine',
    ];
    protected function solution(string $input): string
    {
        $lines = explode("\n", $input);
        return array_sum(array_map(
            function ($line) {
                preg_match_all('/(?=(?P<number>[\d]|' . implode('|', self::NUMBER_MAP) . '))/', $line, $matches);
                $firstMatch = $matches['number'][0];
                $first = array_search($firstMatch, self::NUMBER_MAP, true) ?: $firstMatch;
                $lastMatch = array_pop($matches['number']);
                $last = array_search($lastMatch, self::NUMBER_MAP, true) ?: $lastMatch;
                return (int) ($first . $last);
            },
            $lines,
        ));
    }
}