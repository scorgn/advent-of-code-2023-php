<?php

class Day14A extends BaseSolution
{
    protected function solution(string $input): string
    {
        $map = array_map('str_split', explode("\n", $input));
        $mapTransposed = array_map(null, ...$map);
        $rounds = [];
        $cubes = [];
        foreach ($mapTransposed as $y => $line) {
            foreach ($line as $x => $space) {
                if ($space === '#') {
                    $cubes[$y][] = $x;
                } elseif ($space === 'O') {
                    $rounds[$y][] = $x;
                }
            }
        }

        $movedRounds = [];
        $allBlockedTiles = $cubes;
        foreach ($rounds as $roundY => $line) {
            foreach ($line as $roundX) {
                $firstCubeCandidates = array_filter($allBlockedTiles[$roundY] ?? [], fn ($x) => $x < $roundX);
                $newRoundSpace = empty($firstCubeCandidates) ? 0 : (max($firstCubeCandidates) + 1);

                $allBlockedTiles[$roundY][] = $newRoundSpace;
                $movedRounds[$roundY][] = $newRoundSpace;
            }
        }

        $rockLoads = [];
        $totalLines = count($map[0]);
        foreach ($movedRounds as $line) {
            foreach ($line as $xKey) {
                $rockLoads[] = $totalLines - ($xKey);
            }
        }

        return array_sum($rockLoads);
    }
}
