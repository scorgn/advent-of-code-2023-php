<?php

require_once __DIR__ . '/SolutionFailedException.php';
require_once __DIR__ . '/BaseSolution.php';

try {
    array_shift($argv);
    $command = array_shift($argv);
    if (!in_array($command, ['test', 'solve'])) {
        throw new InvalidArgumentException('Command does not exist: ' . $command);
    }

    if (empty($argv)) {
        $echoChallengeFirst = true;
        $challengeNames = array_map(
            fn ($fileName) => strtolower(substr(pathinfo($fileName, PATHINFO_FILENAME), -2)),
            array_diff(scandir(__DIR__ . '/solutions/'), ['.', '..']),
        );
        $challenges = array_map(
            fn ($challengeName) => sprintf('%s/solutions/Day%s.php', __DIR__, strtoupper($challengeName)),
            array_combine($challengeNames, $challengeNames),
        );
    } else {
        $challenges = array_map(function ($arg) {
            $file = sprintf('%s/solutions/Day%s.php', __DIR__, $arg);
            if (!file_exists($file)) {
                throw new InvalidArgumentException('There was no file found for the challenge: ' . $arg);
            }
            return $file;
        }, array_combine($argv, $argv));
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