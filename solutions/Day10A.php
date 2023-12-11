<?php

class Day10A extends BaseSolution
{
    private array $serializedLoop;

    /**
     * @var Array<Int, Array<Int, string>>
     */
    private array $map;

    private const DIRECTIONS_FROM_TO_MAP = [
        'north' => 'south',
        'east' => 'west',
        'south' => 'north',
        'west' => 'east',
    ];

    private const DIRECTIONS_MAP = [
        'north' => ['y' => -1, 'x' => 0],
        'south' => ['y' => 1, 'x' => 0],
        'east' => ['y' => 0, 'x' => 1],
        'west' => ['y' => 0, 'x' => -1],
    ];

    private const POINT_TYPES = [
        '|' => ['north', 'south'],  // north and south
        '-' => ['east', 'west'], // east and west.
        'L' => ['north', 'east'], // north and east.
        'J' => ['north', 'west'],  // north and west.
        '7' => ['south', 'west'], // south and west.
        'F' => ['south', 'east'], // south and east.
    ];

    protected function solution(string $input): string
    {
        $mapArray = array_map(fn (string $line) => str_split($line), explode("\n", $input));
        $this->map = $mapArray;
        $startPoint = $this->getStartPoint($mapArray);
        $startPointType = $this->getStartingPointType($startPoint);
        $this->map[$startPoint['y']][$startPoint['x']] = $startPointType;
        $loop = [['point' => $startPoint, 'distance' => 0]];
        $newPointsToVisit = [['point' => $startPoint, 'distance' => 0]];
        $this->serializedLoop = [$this->serializePoint($startPoint)];
        while (!empty($newPointsToVisit)) {
            $point = array_shift($newPointsToVisit);
            $connectingPoints = $this->findConnectingPoints($point);
            $this->serializedLoop = array_merge(
                $this->serializedLoop,
                array_map(fn (array $point) => $this->serializePoint($point['point']), $connectingPoints),
            );
            $loop = array_merge($loop, $connectingPoints);
            $newPointsToVisit = array_merge($newPointsToVisit, $connectingPoints);
        }
        return max(array_column($loop, 'distance'));
    }

    private function findConnectingPoints(array $pointArr): array
    {
        $point = $pointArr['point'];
        $newDistance = $pointArr['distance'] + 1;
        $newPoints = array_map(function (string $direction) use ($point, $newDistance) {
            $difference = self::DIRECTIONS_MAP[$direction];
            return [
                'point' => ['x' => $point['x'] + $difference['x'], 'y' => $point['y'] + $difference['y']],
                'distance' => $newDistance,
            ];
        }, self::POINT_TYPES[$this->getPointType($point)]);

        return array_filter(
            $newPoints,
            fn (array $point) => !in_array(
                $this->serializePoint($point['point']),
                $this->serializedLoop,
            ),
        );
    }

    /**
     * @param array{x: int, y: int} $point
     */
    private function getStartingPointType(array $point): string
    {
        $adjacentPoints = array_filter([
            'north' => $point['y'] !== 0 ? ['x' => $point['x'], 'y' => $point['y'] - 1] : null,
            'east' => $point['x'] !== (count($this->map[0]) - 1) ? ['x' => $point['x'] + 1, 'y' => $point['y']] : null,
            'south' => $point['y'] !== (count($this->map) - 1) ? ['x' => $point['x'], 'y' => $point['y'] + 1] : null,
            'west' => $point['x'] !== 0 ? ['x' => $point['x'] - 1, 'y' => $point['y']] : null,
        ], fn ($point) => $point !== null);
        $adjacentTypes = array_map(
            fn (array $point) => self::POINT_TYPES[$this->getPointType($point)] ?? null,
            $adjacentPoints,
        );

        $connectingPoints = [];
        foreach (self::DIRECTIONS_FROM_TO_MAP as $fromDirection => $toDirection) {
            if (in_array($toDirection, $adjacentTypes[$fromDirection] ?? [])) {
                $connectingPoints[] = $fromDirection;
            }
        }

        foreach (self::POINT_TYPES as $type => $directions) {
            if (empty(array_diff($connectingPoints, $directions))) {
                return $type;
            }
        }

        throw new InvalidArgumentException('Couldn\'t find starting point type');
    }

    /**
     * @return array{x: int, y: int}
     */
    private function getStartPoint(array $map): array
    {
        $mapHeight = count($map);
        for ($y = 0; $y < $mapHeight - 1; $y++) {
            $searchResults = array_search('S', $map[$y]);
            if ($searchResults !== false) {
                return ['x' => $searchResults, 'y' => $y];
            }
        }
    }

    /**
     * @param array{x: int, y: int} $point
     */
    private function serializePoint(array $point): string
    {
        return sprintf('%sx%s', $point['y'], $point['x']);
    }

    /**
     * @param array{x: int, y: int} $point
     */
    private function getPointType(array $point): string
    {
        return $this->map[$point['y']][$point['x']];
    }
}
