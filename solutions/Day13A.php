<?php

class Day13A extends BaseSolution
{
    protected function solution(string $input): string
    {
        $maps = array_map(
            fn ($line) => explode("\n", $line),
            explode("\n\n", $input),
        );

        return array_sum(
            array_map(
                fn ($map) => $this->findMirrorForMap($map) * 100
                    ?: $this->findMirrorForMap($this->transposeMap($map)),
                $maps,
            )
        );
    }

    private function transposeMap(array $map): array
    {
        return array_map(
            fn (...$a) => implode('', $a),
            ...array_map('str_split', $map)
        );
    }

    private function findMirrorForMap(array $map): int
    {
        foreach ($map as $key => $line) {
            $n = 0;
            while (isset($map[$key + 1 + $n]) && isset($map[$key - $n])) {
                if ($map[$key + 1 + $n] !== $map[$key - $n]) {
                    continue 2;
                }
                $n++;
            }
            if ($n > 0) {
                return $key + 1;
            }
        }

        return 0;
    }
}
