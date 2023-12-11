<?php

class Day9A extends BaseSolution
{
    protected function solution(string $input): string
    {
        $histories = array_map(
            static fn (string $line) => explode(" ", $line),
            explode("\n", $input),
        );

        return array_sum(array_map([$this, 'getNextFromHistory'], $histories));
    }

    private function getNextFromHistory(array $sequence): int
    {
        $historySequences = [$sequence];
        while (array_values(array_unique(end($historySequences))) !== [0]) {
            $historySequences[] = $this->getNextSequence(end($historySequences));
        }

        $historySequences[count($historySequences) - 1][] = 0;
        for ($i = count($historySequences) - 2; $i >= 0; $i--) {
            $lastOfNext = end($historySequences[$i + 1]);
            $historySequences[$i][] = end($historySequences[$i]) + $lastOfNext;
        }

        return end($historySequences[0]);
    }

    private function getNextSequence(array $sequence): array
    {
        $newSequence = [];
        $sequenceLength = count($sequence);
        for ($i = 0; $i < $sequenceLength - 1; $i++) {
            [$a, $b] = array_slice($sequence, $i, 2);
            $newSequence[] = $b - $a;
        }

        return $newSequence;
    }
}