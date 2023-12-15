<?php

class Day15B extends BaseSolution
{
    protected function solution(string $input): string
    {
        $strings = explode(",", $input);
        $boxes = [];
        $labelsToFocalLengths = [];
        foreach ($strings as $string) {
            preg_match('/(?P<label>[a-z]+)(?P<operation>[=-])(?P<focalLength>[1-9]?)/', $string, $matches);
            $label = $matches['label'];
            $boxNumber = $this->getBoxNumber($label);
            $operation = $matches['operation'];
            $focalLength = $matches['focalLength'];

            if ($operation === '-') {
                $position = array_search($label, $boxes[$boxNumber] ?? []);
                if ($position !== false) {
                    unset($labelsToFocalLengths[$label]);
                    array_splice($boxes[$boxNumber], $position, 1);
                }
            } else {
                $labelsToFocalLengths[$label] = $focalLength;
                $boxes[$boxNumber] ??= [];
                if (!in_array($label, $boxes[$boxNumber])) {
                    $boxes[$boxNumber][] = $label;
                }
            }
        }

        $total = 0;
        foreach ($boxes as $boxNumber => $box) {
            foreach ($box as $order => $lensLabel) {
                $total += (1 + $boxNumber) * (1 + $order) * ($labelsToFocalLengths[$lensLabel]);
            }
        }

        return $total;
    }

    private function getBoxNumber(string $label): int
    {
        return array_reduce(
            str_split($label),
            fn($carry, $char) => (($carry + ord($char)) * 17) % 256,
            0
        );
    }
}
