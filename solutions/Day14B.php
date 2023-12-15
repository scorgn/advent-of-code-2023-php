<?php

class Day14B extends BaseSolution
{
    protected function solution(string $input): string
    {
        $map = array_map('str_split', explode("\n", trim($input)));
        $firstTime = true;
        $matchingMaps = [];
        $firstFoundMatchingMap = null;
        $lastIterationInLoop = null;
        $necessaryLoops = 4000000000;
        for ($i = 1; $i <= $necessaryLoops; $i++) {
            ['rounds' => $rounds, 'cubes' => $cubes, 'map' => $map] = $this->rotateMap($map, $firstTime);
            $firstTime = false;

            $allBlockedTiles = $cubes;
            foreach ($rounds as $roundY => $line) {
                $newLine = [];
                while (!empty($line)) {
                    $roundX = array_shift($line);
                    $firstCubeCandidates = array_filter($allBlockedTiles[$roundY] ?? [], fn($x) => $x < $roundX);
                    $newRoundSpace = empty($firstCubeCandidates) ? 0 : (max($firstCubeCandidates) + 1);
                    $allBlockedTiles[$roundY][] = $newRoundSpace;
                    $newLine[] = $newRoundSpace;
                }
                $rounds[$roundY] = $newLine;
            }

            $newMap = [];
            foreach ($map as $yKey => $y) {
                foreach ($y as $xKey => $x) {
                    $newMap[$yKey][$xKey] = match (true) {
                        $x === '#' => '#',
                        in_array($xKey, $rounds[$yKey] ?? []) => 'O',
                        default => '.',
                    };
                }
            }
            $map = $newMap;

            $arraySearchResult = array_search($map, $matchingMaps);
            if ($arraySearchResult !== false) {
                if (!isset($firstFoundMatchingMap)) {
                    $firstFoundMatchingMap = ['iteration' => $i, 'matches' => $arraySearchResult];
                } elseif ($arraySearchResult === $firstFoundMatchingMap['matches']) {
                    $lastIterationInLoop = $i - 1;
                    break;
                }
            } else {
                $matchingMaps[$i] = $map;
            }
        }
        $loopLength = $lastIterationInLoop - $firstFoundMatchingMap['iteration'] + 1;
        $numberNeededToKeepGoing = $necessaryLoops - $firstFoundMatchingMap['iteration'];
        $endNumberInSequence = $numberNeededToKeepGoing % $loopLength;
        $sequenceEndsAt = $firstFoundMatchingMap['matches'] + $endNumberInSequence;
        $map = $matchingMaps[$sequenceEndsAt];
        ['rounds' => $rounds, 'map' => $map] = $this->rotateMap($map, false);

        $rockLoads = [];
        $totalLines = count($map[0]);
        foreach ($rounds as $line) {
            foreach ($line as $xKey) {
                $rockLoads[] = $totalLines - ($xKey);
            }
        }

        return array_sum($rockLoads);
    }

    private function rotateMap(array $map, bool $firstTime): array
    {
        if (!$firstTime) {
            $map = array_map(fn($line) => array_reverse($line), $map);
        }
        $map = array_map(null, ...$map);
        $rounds = [];
        $cubes = [];
        foreach ($map as $y => $line) {
            foreach ($line as $x => $space) {
                if ($space === '#') {
                    $cubes[$y][] = $x;
                } elseif ($space === 'O') {
                    $rounds[$y][] = $x;
                }
            }
        }

        return ['rounds' => $rounds, 'cubes' => $cubes, 'map' => $map];
    }
}
