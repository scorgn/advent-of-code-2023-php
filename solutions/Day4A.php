<?php

class Day4A extends BaseSolution
{
    protected function solution(string $input): string
    {
        $cards = array_map(
            function ($line) {
                $cardContents = explode(':', $line)[1];
                [$myNumbers, $winningNumbers] = explode(' | ', $cardContents);
                return [
                    'has' => array_filter(array_map('trim', explode(' ', $myNumbers))),
                    'winning' => array_filter(array_map('trim', explode(' ', $winningNumbers))),
                ];
            },
            explode("\n", $input),
        );

        $totalWorth = 0;
        foreach ($cards as $card) {
            $matches = count(array_intersect($card['has'], $card['winning']));
            if ($matches === 0) {
                continue;
            }
            $totalWorth += pow(
                2,
                count(array_intersect($card['has'], $card['winning'])) - 1
            );
        }

        return $totalWorth;
    }
}