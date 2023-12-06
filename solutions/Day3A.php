<?php

class Day3A extends BaseSolution
{
    protected function solution(string $input): string
    {
        $symbolPositions = [];
        $numberPositions = [];
        $lines = explode(PHP_EOL, $input);
        foreach ($lines as $lineNo => $line) {
            preg_match_all('/(?P<symbol>[^.\d])/', $line, $matches, PREG_OFFSET_CAPTURE);
            foreach ($matches['symbol'] as $symbolMatch) {
                $symbolPositions[] = ['y' => $lineNo, 'x' => $symbolMatch[1]];
            }
            preg_match_all('/(?P<number>\d+)/', $line, $matches, PREG_OFFSET_CAPTURE);
            foreach ($matches['number'] as $numberMatch) {
                $numberPositions[] = [
                    'number' => $numberMatch[0],
                    'position' => ['y' => $lineNo, 'x' => $numberMatch[1]],
                ];
            }
        }
        $validNumbers = [];
        foreach ($numberPositions as $numberPosition) {
            $minNumberY = $numberPosition['position']['y'] - 1;
            $minNumberX = $numberPosition['position']['x'] - 1;
            $maxNumberY = $numberPosition['position']['y'] + 1;
            $maxNumberX = $numberPosition['position']['x'] + strlen($numberPosition['number']);
            foreach ($symbolPositions as ['x' => $symbolX, 'y' => $symbolY]) {
                if ($symbolX >= $minNumberX && $symbolX <= $maxNumberX && $symbolY >= $minNumberY && $symbolY <= $maxNumberY) {
                    $validNumbers[] = $numberPosition['number'];
                    continue 2;
                }
            }
        }

        return array_sum($validNumbers);
    }
}