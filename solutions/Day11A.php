<?php

class Day11A extends BaseSolution
{
    protected function solution(string $input): string
    {
        $map = array_map(fn (string $line) => str_split($line), explode("\n", $input));
        $map = $this->expandMap($map);
        $galaxies = $this->findGalaxies($map);
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

    private function expandMap(array $map): array
    {

        $originalHeight = count($map);
        $originalWidth = count($map[0]);
        $newMap = [];
        for ($v = 0; $v < $originalHeight; $v++) {
            $newMap[] = $map[$v];
            if (array_values(array_unique($map[$v])) === ['.']) {
                $newMap[] = $map[$v];
            }
        }
        $map = array_map(null, ...$newMap);
        $newMap = [];
        for ($h = 0; $h < $originalWidth; $h++) {
            $newMap[] = $map[$h];
            if (array_values(array_unique($map[$h])) === ['.']) {
                $newMap[] = $map[$h];
            }
        }
        return array_map(null, ...$newMap);
    }
}
