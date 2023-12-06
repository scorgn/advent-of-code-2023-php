<?php

class Day3B extends BaseSolution
{
    protected function solution(string $input): string
    {
        $symbolPositions = [];
        $numberPositions = [];
        $lines = explode(PHP_EOL, $input);
        foreach ($lines as $lineNo => $line) {
            preg_match_all('/(?P<symbol>[*])/', $line, $matches, PREG_OFFSET_CAPTURE);
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
        $gearRatios = [];
        foreach ($symbolPositions as ['x' => $symbolX, 'y' => $symbolY]) {
            $validNumberPositions = [];
            foreach ($numberPositions as $numberPosition) {
                $minNumberY = $numberPosition['position']['y'] - 1;
                $minNumberX = $numberPosition['position']['x'] - 1;
                $maxNumberY = $numberPosition['position']['y'] + 1;
                $maxNumberX = $numberPosition['position']['x'] + strlen($numberPosition['number']);
                if ($symbolX >= $minNumberX && $symbolX <= $maxNumberX && $symbolY >= $minNumberY && $symbolY <= $maxNumberY) {
                    $validNumberPositions[] = $numberPosition['number'];
                }
            }
            if (count($validNumberPositions) === 2) {
                $gearRatios[] = array_product($validNumberPositions);
            }
        }

        return array_sum($gearRatios);
    }
}