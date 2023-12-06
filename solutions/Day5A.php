<?php

class Day5A extends BaseSolution {
    protected function solution(string $input): string
    {
        $sections = preg_split('/(^.+ map:)/m', $input, flags: PREG_SPLIT_DELIM_CAPTURE);
        $sections = array_map('trim', $sections);
        preg_match('/seeds: (?P<seedNumbers>[0-9 ]+)/', $sections[0], $match);
        $seedNumbers = explode(' ', $match['seedNumbers']);
        array_shift($sections);
        $maps = array_chunk($sections, 2);
        $parsed = array_map(function ($map) {
            preg_match('/(?P<from>[a-z]+)-to-(?P<to>[a-z]+) map:/', $map[0], $matches);
            $from = $matches['from'];
            $to = $matches['to'];
            $fullMap = array_map(function ($mapLine) {
                [$destinationRangeStart, $sourceRangeStart, $rangeLength] = explode(' ', $mapLine);
                return [
                    'destination_range_start' => $destinationRangeStart,
                    'source_range_start' => $sourceRangeStart,
                    'range_length' => $rangeLength,
                ];
            }, explode("\n", $map[1]));
            return [
                'from' => $from,
                'to' => $to,
                'map' => $fullMap,
            ];
        }, $maps);

        $data = array_combine(array_map(fn ($map) => $map['from'], $parsed), $parsed);

        $currentNumbers = array_map(fn ($seedNumber) => ['type' => 'seed', 'value' => $seedNumber], $seedNumbers);
        $results = array_map(fn ($currentNumber) => $this->getLocation($currentNumber, $data), $currentNumbers);
        return min(array_column($results, 'value'));
    }

    /**
     * @param array{type: string, value: int} $number
     * @param array{from: string, to: string, map: Array<int, array{destination_range_start: int, source_range_start: int, range_length: int}>}  $data
     */
    function getLocation(array $number, array $data) {
        ['type' => $type, 'value' => $value] = $number;
        $map = $data[$type]['map'];
        $newNumber = null;
        foreach ($map as $row) {
            if ($value >= $row['source_range_start'] && $value <= $row['source_range_start'] + $row['range_length']) {
                $newNumber = [
                    'type' => $data[$type]['to'],
                    'value' => $value - ($row['source_range_start'] - $row['destination_range_start']),
                ];
                break;
            }
        }
        if (!$newNumber) {
            $newNumber = ['type' => $data[$type]['to'], 'value' => $value];
        }

        return $newNumber['type'] === 'location' ? $newNumber : $this->getLocation($newNumber, $data);
    }
}