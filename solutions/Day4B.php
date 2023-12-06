<?php

class Day4B extends BaseSolution
{
    protected function solution(string $input): string
    {
        $originalCards = [];
        foreach (explode("\n", $input) as $line) {
            preg_match('/Card +(?P<id>\d+):/', $line, $match);
            $id = $match['id'];
            $cardContents = explode(':', $line)[1];
            [$myNumbers, $winningNumbers] = explode(' | ', $cardContents);
            $originalCards[$id] = [
                'has' => array_filter(array_map('trim', explode(' ', $myNumbers))),
                'winning' => array_filter(array_map('trim', explode(' ', $winningNumbers))),
            ];
        }

        $cardGains = [];
        foreach ($originalCards as $id => $card) {
            if ($id === count($originalCards)) {
                $cardGains[$id] = [];
                continue;
            }
            $matches = count(array_intersect($card['has'], $card['winning']));
            if ($matches === 0) {
                $cardGains[$id] = [];
                continue;
            }
            $cardGains[$id] = range($id + 1, min(count($originalCards), $id + $matches));
        }

        $cardIds = array_keys($originalCards);
        $cardCounts = array_combine($cardIds, array_fill(1, count($originalCards), 1));

        foreach ($cardIds as $cardId) {
            $thisCardCount = $cardCounts[$cardId];
            foreach ($cardGains[$cardId] as $cardGainId) {
                $cardCounts[$cardGainId] += $thisCardCount;
            }
        }

        return array_sum($cardCounts);
    }
}