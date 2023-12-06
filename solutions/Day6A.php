<?php

class Day6A extends BaseSolution
{
    protected function solution(string $input): string
    {
        [$timeString, $recordsString] = explode("\n", $input);
        preg_match_all('/(?P<ms>[0-9]+)/', $timeString, $matches);
        $times = $matches['ms'];
        preg_match_all('/(?P<mm>[0-9]+)/', $recordsString, $matches);
        $records = $matches['mm'];
        $races = array_map(fn ($time, $record) => ['time' => $time, 'record' => $record], $times, $records);
        $allWaysToWin = [];
        foreach ($races as $race) {
            $waysToWin = 0;
            for ($i = 1; $i <= $race['time']; $i++) {
                if (($race['time'] - $i) * $i > $race['record']) {
                    $waysToWin++;
                }
            }
            $allWaysToWin[] = $waysToWin;
        }

        return array_product($allWaysToWin);
    }
}