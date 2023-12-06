<?php

class Day2B extends BaseSolution
{
    protected function solution(string $input): string
    {
        $powers = [];
        $lines = explode("\n", $input);
        foreach ($lines as $line) {
            $minRequired = ['red' => 0, 'green' => 0, 'blue' => 0];
            $results = explode(':', $line)[1];
            $sets = explode(';', $results);
            foreach ($sets as $set) {
                preg_match_all('/(?P<number>[\d]+) (?P<color>red|blue|green)/', $set, $matches, PREG_SET_ORDER);
                foreach ($matches as $match) {
                    if ($match['number'] > $minRequired[$match['color']]) {
                        $minRequired[$match['color']] = $match['number'];
                    }
                }
            }
            $powers[] = array_product($minRequired);
        }

        return array_sum($powers);
    }
}