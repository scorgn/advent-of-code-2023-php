<?php

class Day12A extends BaseSolution
{
    protected function solution(string $input): string
    {
        $lines = array_map(
            function (string $l) {
                [$row, $sizes] = explode(' ', $l);
                $sizes = array_map('intval', explode(',', $sizes));
                return ['row' => str_split($row), 'group_sizes' => $sizes];
            },
            explode("\n", $input),
        );

        return array_sum(array_map(
            fn ($line) => $this->countPossibleCombinations(
                $line['row'],
                $line['group_sizes'],
                false,
                false,
            ),
            $lines,
        ));
    }
    
    private function countPossibleCombinations(
        array $restOfString,
        array $groupSizes,
        bool $inBrokenGroup,
        bool $brokenGroupJustEnded,
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
                return $this->countPossibleCombinations(
                    array_merge(['#'], $restOfString),
                    $groupSizes,
                    $inBrokenGroup,
                    $brokenGroupJustEnded,
                )
                + $this->countPossibleCombinations(
                    array_merge(['.'], $restOfString),
                    $groupSizes,
                    $inBrokenGroup,
                    $brokenGroupJustEnded,
                );
            }
        }

        return empty($groupSizes) ? 1 : 0;
    }
}
