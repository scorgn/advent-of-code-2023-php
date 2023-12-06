<?php

class Day5B extends BaseSolution
{
    protected function solution(string $input): string
    {
        $sections = preg_split('/(^.+ map:)/m', $input, flags: PREG_SPLIT_DELIM_CAPTURE);
        $seedNumbers = $this->parseSeedRangeChunks($sections);
        $categoryMaps = $this->parseMaps($sections);

        $seedNumberChunks = [];
        while (count($seedNumbers) > 0) {
            $start = array_shift($seedNumbers);
            $length = array_shift($seedNumbers);
            $seedNumberChunks[] = ['start' => $start, 'end' => $start + $length - 1, 'type' => 'seed'];
        }
        $locationChunks = $this->getLocationChunks($seedNumberChunks, $categoryMaps);
        return min(array_column($locationChunks, 'start'));
    }

    private function parseSeedRangeChunks(array $sections): array
    {
        preg_match('/seeds: (?P<seedNumbers>[0-9 ]+)/', $sections[0], $match);
        $seedNumbers = explode(' ', $match['seedNumbers']);

        return array_map('intval', $seedNumbers);
    }

    private function getLocationChunks(array $numberChunks, array $categoryMaps): array
    {
        $locationChunks = [];

        while (count($numberChunks) > 0) {
            ['type' => $type, 'start' => $start, 'end' => $end] = array_shift($numberChunks);

            $nextType = $categoryMaps[$type]['to'];
            $nextTypeChunkArray = &$numberChunks;
            if ($nextType === 'location') {
                $nextTypeChunkArray = &$locationChunks;
            }

            foreach ($categoryMaps[$type]['map'] as $row) {
                $difference = ($row['source_start'] - $row['destination_start']);
                if ($end < $row['source_start'] || $start > $row['source_end']) {
                    continue;
                }
                if ($start < $row['source_start'] && $end <= $row['source_end']) {
                    $numberChunks[] = [
                        'start' => $start,
                        'end' => $row['source_start'] - 1,
                        'type' => $type,
                    ];
                    $nextTypeChunkArray[] = [
                        'start' => $row['source_start'] - $difference,
                        'end' => $end - $difference,
                        'type' => $nextType,
                    ];
                    continue 2;
                }
                if ($start >= $row['source_start'] && $end <= $row['source_end']) {
                    $nextTypeChunkArray[] = [
                        'start' => $start - $difference,
                        'end' => $end - $difference,
                        'type' => $nextType,
                    ];
                    continue 2;
                }
                if ($start >= $row['source_start'] && $end > $row['source_end']) {
                    $nextTypeChunkArray[] = [
                        'start' => $start - $difference,
                        'end' => $row['source_end'] - $difference,
                        'type' => $nextType,
                    ];
                    $numberChunks[] = [
                        'start' => $row['source_end'] + 1,
                        'end' => $end,
                        'type' => $type,
                    ];
                    continue 2;
                }
                if ($start < $row['source_start'] && $end > $row['source_end']) {
                    $numberChunks[] = [
                        'start' => $start,
                        'end' => $row['source_start'] - 1,
                        'type' => $type,
                    ];
                    $numberChunks[] = [
                        'start' => $row['source_end'] + 1,
                        'end' => $end,
                        'type' => $type,
                    ];
                    $nextTypeChunkArray[] = [
                        'start' => $row['source_start'],
                        'end' => $row['source_end'],
                        'type' => $nextType,
                    ];
                    continue 2;
                }
            }

            $nextTypeChunkArray[] = [
                'start' => $start,
                'end' => $end,
                'type' => $nextType,
            ];
        }

        return $locationChunks;
    }

    /**
     * @return Array<int, array{from: string, to: string, map: Array<int, array{destination_start: int, source_start: int}>}>
     */
    private function parseMaps(array $sections): array
    {
        array_shift($sections); // Remove seeds line
        $sections = array_chunk($sections, 2); // Chunk sections into tuple array [title, map]
        $parsed = array_map([$this, 'parseSectionIntoMap'], $sections);
        return array_combine(array_column($parsed, 'from'), $parsed);
    }

    /**
     * @return array{from: string, to: string, map: Array<int, array{destination_start: int, source_start: int}>}
     */
    private function parseSectionIntoMap(array $section): array
    {
        preg_match('/(?P<from>[a-z]+)-to-(?P<to>[a-z]+) map:/', $section[0], $matches);
        ['to' => $to, 'from' => $from] = $matches;
        $sectionRows = explode("\n", trim($section[1]));

        $fullMap = array_map(static function ($mapLine) {
            [$destinationRangeStart, $sourceRangeStart, $rangeLength] = explode(' ', $mapLine);
            return [
                'destination_start' => (int) $destinationRangeStart,
                'source_start' => (int) $sourceRangeStart,
                'source_end' => (int) $sourceRangeStart + (int) $rangeLength - 1,
            ];
        }, $sectionRows);

        return [
            'from' => $from,
            'to' => $to,
            'map' => $fullMap,
        ];
    }
}
