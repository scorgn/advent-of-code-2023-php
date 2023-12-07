<?php
class Day7B extends BaseSolution
{
    private array $cardStrengthMap = [];

    protected function solution(string $input): string
    {
        $this->cardStrengthMap = array_flip(array_reverse(['A', 'K', 'Q', 'T', '9', '8', '7', '6', '5', '4', '3', '2', 'J']));
        $hands = array_map(
            function (string $line) {
                [$handString, $bid] = explode(" " , $line);
                $hand = str_split($handString);
                return [
                    'handStrength' => $this->getHandStrength($hand),
                    'cardsStrength' => $this->getHandsCardsStrength($hand),
                    'bid' => (int) $bid,
                ];
            },
            explode("\n", $input),
        );

        $sortFn = fn ($a, $b) => $a['handStrength'] <=> $b['handStrength']
            ?: $a['cardsStrength'] <=> $b['cardsStrength'];
        usort($hands, $sortFn);

        return array_sum(array_map(
            fn ($hand, $key) => ($key + 1) * $hand['bid'],
            $hands,
            array_keys($hands),
        ));
    }

    private function getHandsCardsStrength(array $hand): int
    {
        $base13CardStrengths = array_map(function ($card) {
            $strength = $this->cardStrengthMap[$card];
            if ($strength > 9) {
                $strength = base_convert($strength, 10, 13);
            }
            return $strength;
        }, $hand);
        return base_convert(implode("", $base13CardStrengths), 13, 10);
    }
    private function getHandStrength(array $hand): int
    {
        $counts = array_count_values($hand);
        $jokerCounts = $counts['J'] ?? 0;
        $counts = array_count_values(array_filter($hand, fn ($card) => $card != 'J'));

        return match (true) {
            in_array(5 - $jokerCounts, $counts), // 5 - number of jokers of a kind
            $jokerCounts >= 5, // 5 jokers
                => 6, // Five of a kind
            in_array(4 - $jokerCounts, $counts), // 4 - number of jokers of a kind
            $jokerCounts >= 4, // 4 jokers
                => 5, // Four of a kind
            in_array(3, $counts) && in_array(2, $counts), // 3 of a kind, 2 of a kind
            $jokerCounts >= 1 && (array_count_values($counts)[2] ?? 0) === 2, // 2 of a kind, 2 of a kind, 1 joker
            $jokerCounts >= 1 && in_array(3, $counts), // 3 of a kind, 1 joker
            $jokerCounts >= 2 && in_array(2, $counts), // 2 of a kind, 2 jokers
                => 4, // Full house
            in_array(3, $counts), // 3 of a kind
            $jokerCounts >= 2, // 2 jokers
            $jokerCounts >= 1 && in_array(2, $counts), // 1 joker, 2 of kind
                => 3, // 2 of a kind and one joker
            (array_count_values($counts)[2] ?? 0) === 2, // 2 of a kind, 2 of a kind
                => 2, // Two pair
            $jokerCounts >= 1, // 1 joker,
            in_array(2, $counts), // 2 of a kind
                => 1, // One pair
            default => 0, // High card
        };
    }
}