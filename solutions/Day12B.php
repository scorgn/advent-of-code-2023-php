<?php

class Day12B extends BaseSolution
{
    private array $cachedTriesFrom;

    protected function solution(string $input): string
    {
        $lines = array_map(
            function (string $l) {
                [$row, $sizes] = explode(' ', $l);
                $sizes = implode(",", array_fill(0, 5, $sizes));
                $row = implode("?", array_fill(0, 5, $row));
                $sizes = array_map('intval', explode(',', $sizes));
                return ['row' => str_split($row), 'group_sizes' => $sizes];
            },
            explode("\n", $input),
        );

        return array_sum(array_map(
            function ($line) {
                $this->cachedTriesFrom = [];
                return $this->countPossibleCombinationsWrapper(
                    $line['row'],
                    $line['group_sizes'],
                    false,
                    false,
                );
            },
            $lines,
        ));
    }

    private function countPossibleCombinationsWrapper(
        array $restOfString,
        array $groupSizes,
        bool $inBrokenGroup,
        bool $brokenGroupJustEnded,
        int $brokenGuessed = 0,
    ): int {
        $key = implode('_', [
            count($restOfString),
            $brokenGuessed,
            $restOfString[0],
            (int) $brokenGroupJustEnded,
        ]);
        return $this->cachedTriesFrom[$key] ??= $this->countPossibleCombinations(
            $restOfString,
            $groupSizes,
            $inBrokenGroup,
            $brokenGroupJustEnded,
            $brokenGuessed,
        );
    }

    private function countPossibleCombinations(
        array $restOfString,
        array $groupSizes,
        bool $inBrokenGroup,
        bool $brokenGroupJustEnded,
        int $brokenGuessed = 0,
    ): int {
        while (count($restOfString) > 0) {
            $thisLetter = array_shift($restOfString);
            if ($thisLetter === '#') {
                if ($brokenGroupJustEnded || empty($groupSizes)) {
                    return 0;
                }
                $inBrokenGroup = true;
                $groupSizes[0]--;
                if ($groupSizes[0] === 0) {
                    $brokenGroupJustEnded = true;
                    $inBrokenGroup = false;
                    array_shift($groupSizes);

                    if (empty($groupSizes)) {
                        return in_array('#', $restOfString) ? 0 : 1;
                    }
                }
                continue;
            }

            if ($thisLetter === '.') {
                if ($inBrokenGroup) {
                    return 0;
                }
                $brokenGroupJustEnded = false;
                continue;
            }

            if ($thisLetter === '?') {
                return $this->countPossibleCombinationsWrapper(
                    array_merge(['#'], $restOfString),
                    $groupSizes,
                    $inBrokenGroup,
                    $brokenGroupJustEnded,
                    $brokenGuessed + 1
                )
                + $this->countPossibleCombinationsWrapper(
                    array_merge(['.'], $restOfString),
                    $groupSizes,
                    $inBrokenGroup,
                    $brokenGroupJustEnded,
                    $brokenGuessed
                );
            }
        }

        return empty($groupSizes) ? 1 : 0;
    }
}
