<?php

class MakeNextSolution
{
    private const NEW_SOLUTION_CONTENTS = <<<'FILE'
<?php

class Day%s extends BaseSolution
{
    protected function solution(string $input): string
    {
        $lines = explode("\n", $input);
    }
}

FILE;

    public function __construct(private readonly array $challenges)
    {
    }

    public function makeNext(bool $justGetInput = false): string
    {
        $challenges = $this->challenges;
        $lastChallenge = end($challenges);
        preg_match('/(?P<day>[0-9]{1,2})(?P<part>[ab])/', $lastChallenge, $matches);
        $lastDay = $matches['day'];
        $lastPart = $matches['part'];
        if ($lastPart === 'a') {
            $nextDay = $lastDay;
            $nextPart = 'b';
        } else {
            $nextDay = $lastDay + 1;
            $nextPart = 'a';
        }

        $dayPart = $nextDay . strtoupper($nextPart);

        if (!$justGetInput) {
            $nextSolutionFileName = sprintf('%s/solutions/Day%s.php', __DIR__, $dayPart);

            if ($nextPart === 'a') {
                file_put_contents(
                    $nextSolutionFileName,
                    sprintf(self::NEW_SOLUTION_CONTENTS, $dayPart),
                );
            } else {
                $lastSolutionFileName = sprintf('%s/solutions/Day%s.php', __DIR__, strtoupper($lastChallenge));
                $lastDayFileContents = file_get_contents($lastSolutionFileName);
                $nextDayFileContents = str_replace('class Day' . strtoupper($lastChallenge), 'class Day' . $dayPart, $lastDayFileContents);
                file_put_contents($nextSolutionFileName, $nextDayFileContents);
            }
        }

        $nextDayInputFile = sprintf('%s/inputs/%s.txt', __DIR__, $nextDay);
        if (!file_exists($nextDayInputFile)) {
            require_once(__DIR__ . '/InputDownloader.php');
            $inputDownloader = new InputDownloader();
            $inputDownloader->downloadInputToFile($nextDay, $nextDayInputFile);
        } elseif ($justGetInput) {
            echo "\nError: Input file already exists - " . $nextDayInputFile . "\n";
        }
        return $nextSolutionFileName ?? $nextDay;
    }
}