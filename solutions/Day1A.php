<?php

class Day1A extends BaseSolution {
    protected function solution(string $input): string
    {
        $lines = explode("\n", $input);
        return array_sum(array_map(
            function ($line) {
                preg_match('/^[^\d]*(?P<number>[\d])/', $line, $first);
                preg_match('/(?P<number>[\d])[^\d]*$/', $line, $last);
                return $first['number'] . $last['number'];
            },
            $lines,
        ));
    }
}