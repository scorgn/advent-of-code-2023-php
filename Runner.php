<?php

require_once __DIR__ . '/SolutionFailedException.php';
require_once __DIR__ . '/BaseSolution.php';
require_once __DIR__ . '/MakeNextSolution.php';

try {
    array_shift($argv);
    $command = array_shift($argv);
    if (!in_array($command, ['test', 'solve', 'make', 'input'])) {
        throw new InvalidArgumentException('Command does not exist: ' . $command);
    }

    if (empty($argv)) {
        $echoChallengeFirst = true;
        $challengeNames = array_map(
            function (string $fileName) {
                $fileName = pathinfo($fileName, PATHINFO_FILENAME);
                preg_match('/Day(?P<challenge>[0-9]+[AB])/', $fileName, $matches);
                if (!($matches['challenge'] ?? false)) {
                    return false;
                }
                return strtolower($matches['challenge']);
            },
            array_diff(scandir(__DIR__ . '/solutions/'), ['.', '..']),
        );
        $challengeNames = array_filter(array_unique($challengeNames), fn ($name) => $name !== false);
        usort($challengeNames, function (string $a, string $b) {
            preg_match('/(?P<day>[0-9]+)(?P<part>[ab])/', $a, $matches);
            $aDay = (int) $matches['day'];
            $aPart = $matches['part'];
            preg_match('/(?P<day>[0-9]+)(?P<part>[ab])/', $b, $matches);
            $bDay = (int) $matches['day'];
            $bPart = $matches['part'];
            if ($aDay !== $bDay) {
                return $aDay <=> $bDay;
            }
            return $aPart === 'a' ? -1 : 1;
        });

        $challenges = array_map(
            fn ($challengeName) => sprintf('%s/solutions/Day%s.php', __DIR__, strtoupper($challengeName)),
            array_combine($challengeNames, $challengeNames),
        );
    } else {
        $challengeNames = array_combine($argv, $argv);
        $challenges = array_map(function ($arg) {
            $file = sprintf('%s/solutions/Day%s.php', __DIR__, $arg);
            if (!file_exists($file)) {
                throw new InvalidArgumentException('There was no file found for the challenge: ' . $arg);
            }
            return $file;
        }, $challengeNames);
    }

    if ($command === 'make') {
        $file = (new MakeNextSolution($challengeNames))->makeNext();
        echo 'Made solution for ' . basename($file) . "\n";
        return;
    } elseif ($command === 'input') {
        $file = (new MakeNextSolution($challengeNames))->makeNext(true);
        echo 'Got input for ' . basename($file) . "\n";
        return;
    }

    $echoChallengeFirst = $echoChallengeFirst ?? count($challenges) > 1;

    foreach ($challenges as $challengeName => $challengeFile) {
        include_once($challengeFile);
        $className = pathinfo($challengeFile, PATHINFO_FILENAME);
        if (!class_exists($className)) {
            throw new InvalidArgumentException('Class does not exist for challenge: ' . $className);
        }
        /** @var BaseSolution $solution */
        $solution = new $className();
        if ($echoChallengeFirst) {
            echo $challengeName . ': ' . PHP_EOL;
        }
        if ($command === 'solve') {
            echo $solution->solve() . PHP_EOL;
        }
        if ($command === 'test') {
            try {
                $solution->test();
                echo 'Pass' . PHP_EOL;
            } catch (SolutionFailedException $e) {
                echo 'Fail' . PHP_EOL . 'Expected: ' . $e->getExpected() . PHP_EOL . 'Actual: ' . $e->getActual() . PHP_EOL;
            }
        }
        echo PHP_EOL;
    }
} catch (InvalidArgumentException $e) {
    echo 'Error - ' . $e->getMessage() . PHP_EOL;
}