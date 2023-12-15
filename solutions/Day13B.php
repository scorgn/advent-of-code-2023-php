<?php

class Day13B extends BaseSolution
{
    protected function solution(string $input): string
    {
        $maps = array_map(
            fn ($line) => explode("\n", $line),
            explode("\n\n", $input),
        );

        return array_sum(
            array_map(
                fn ($map) => $this->findMirrorForMap($this->getHexMap($map)) * 100
                    ?: $this->findMirrorForMap($this->getTransposedHexMap($map)),
                $maps,
            )
        );
    }

    private function getTransposedHexMap(array $map): array
    {
        return $this->getHexMap(
            array_map(
                fn (...$a) => implode('', $a),
                ...array_map('str_split', $map)
            ),
        );
    }

    private function getHexMap(array $map): array
    {
        return array_map(
            fn ($line) => str_replace(['.', '#'], [1,0], $line),
            $map
        );
    }

    private function findMirrorForMap(array $map): ?int
    {
        foreach ($map as $key => $line) {
            $smudgeFound = false;
            $n = 0;
            while (isset($map[$key + 1 + $n]) && isset($map[$key - $n])) {
                if ($map[$key + 1 + $n] === $map[$key - $n]) {
                    $n++;
                    continue;
                }

                if (!$smudgeFound && $this->isOffByOneBinaryDigit($map[$key + 1 + $n], $map[$key - $n])) {
                    $smudgeFound = true;
                    $n++;
                    continue;
                }

                continue 2;
            }

            if ($n > 0 && $smudgeFound) {
                return $key + 1;
            }
        }

        return 0;
    }

    private function isOffByOneBinaryDigit(string $a, string $b): bool
    {
        $diff = bindec($a) ^ bindec($b);
        return ($diff & ($diff - 1)) === 0;
    }
}
