<?php
class Day7A extends BaseSolution
{
    private array $cardStrengthMap = [];

    protected function solution(string $input): string
    {
        $this->cardStrengthMap = array_flip(array_reverse(['A', 'K', 'Q', 'J', 'T', '9', '8', '7', '6', '5', '4', '3', '2']));
        $hands = array_map(
            function (string $line) {
                [$handString, $bid] = explode(" " , $line);
                $hand = str_split($handString);
                return [
                    'handStrength' => $this->getHandStrength($hand),
                    'cardStrengths' => $this->getHandsCardsStrength($hand),
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
        return match (true) {
            in_array(5, $counts) => 6, // Five of a kind
            in_array(4, $counts) => 5, // Four of a kind
            in_array(3, $counts) && in_array(2, $counts) => 4, // Full house
            in_array(3, $counts) => 3, // Three of a kind
            (array_count_values($counts)[2] ?? 0) === 2 => 2, // Two pair
            in_array(2, $counts) => 1, // One pair
            default => 0, // High card
        };
    }
}