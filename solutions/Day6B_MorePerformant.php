<?php

/**
 * Implement a binary search algorithm to find the lowest and highest times in the least amount of checks
 */
class Day6B_MorePerformant extends BaseSolution
{
    protected function solution(string $input): string
    {
        [$timeString, $recordsString] = explode("\n", $input);
        preg_match_all('/[0-9]+/', $timeString, $matches);
        $time = (int) implode($matches[0]);
        preg_match_all('/[0-9]+/', $recordsString, $matches);
        $record = (int) implode($matches[0]);
        $lowest = ['below' => $time, 'above' => 0];
        $highest = ['below' => $time, 'above' => 0];
        $beatsRecord = static fn ($number) => ($time - $number) * $number > $record;

        while (abs($lowest['above'] - $lowest['below']) > 1) {
            $i = (int) ($lowest['above'] + floor(($lowest['below'] - $lowest['above']) / 2));
            if ($beatsRecord($i)) {
                $lowest['below'] = $i;
                if ($i > $highest['above']) {
                    $highest['above'] = $i;
                }
            } else {
                $lowest['above'] = $i;
                if ($highest['below'] < $i) {
                    $highest['below'] = $i;
                }
            }
        }

        while (abs($highest['above'] - $highest['below']) > 1) {
            $i = (int) ($highest['above'] + floor(($highest['below'] - $highest['above']) / 2));
            if ($beatsRecord($i)) {
                $highest['above'] = $i;
            } else {
                $highest['below'] = $i;
            }
        }

        return $highest['above'] - $lowest['below'] + 1;
    }
}