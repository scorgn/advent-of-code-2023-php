<?php

class Day6B extends BaseSolution
{
    protected function solution(string $input): string
    {
        [$timeString, $recordsString] = explode("\n", $input);
        preg_match_all('/[0-9]+/', $timeString, $matches);
        $time = (int) implode($matches[0]);
        preg_match_all('/[0-9]+/', $recordsString, $matches);
        $record = (int) implode($matches[0]);
        $firstWin = null;
        $i = 1;
        while ($i < $time) {
            if (($time - $i) * $i > $record) {
                $firstWin = $i;
                break;
            }
            $i++;
        }
        $lastWin = null;
        $i = $time;
        while ($i > 0) {
            if (($time - $i) * $i > $record) {
                $lastWin = $i;
                break;
            }
            $i--;
        }

        return ($lastWin - $firstWin) + 1;
    }
}