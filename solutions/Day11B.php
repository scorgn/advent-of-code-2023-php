<?php

class Day11B extends BaseSolution
{
    protected function solution(string $input): string
    {
        $map = array_map(fn (string $line) => str_split($line), explode("\n", $input));
        $emptyHorizontalLines = array_keys(array_filter($map, fn ($line) => array_values(array_unique($line)) === ['.']));
        $transposedMap = array_map(null, ...$map);
        $emptyVerticalLines = array_keys(array_filter($transposedMap, fn ($line) => array_values(array_unique($line)) === ['.']));

        $newMap = [];
        $yMarker = 0;
        foreach ($map as $y => $line) {
            $xMarker = 0;
            foreach ($line as $x => $value) {
                $newMap[$yMarker][$xMarker] = $value;
                $xMarker += in_array($x, $emptyVerticalLines) ? 1000000 : 1;
            }
            $yMarker += in_array($y, $emptyHorizontalLines) ? 1000000 : 1;
        }

        $galaxies = $this->findGalaxies($newMap);
        $galaxyPairs = $this->getGalaxyPairs($galaxies);

        return array_sum(array_map(function ($pair) {
            [$galaxyA, $galaxyB] = $pair;
            return abs($galaxyA['x'] - $galaxyB['x']) + abs($galaxyA['y'] - $galaxyB['y']);
        }, $galaxyPairs));
    }

    private function getGalaxyPairs(array $galaxies): array
    {
        $galaxyPairs = [];
        while (!empty($galaxies)) {
            $thisGalaxy = array_shift($galaxies);
            foreach ($galaxies as $anotherGalaxy) {
                $galaxyPairs[] = [$thisGalaxy, $anotherGalaxy];
            }
        }

        return $galaxyPairs;
    }

    private function findGalaxies(array $map): array
    {
        $galaxies = [];

        foreach ($map as $y => $line) {
            foreach ($line as $x => $char) {
                if ($char === '#') {
                    $galaxies[] = ['x' => $x, 'y' => $y];
                }
            }
        }

        return $galaxies;
    }
}
