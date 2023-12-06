<?php

abstract class BaseSolution {
    public function getInput(): string
    {
        $inputFileName = sprintf(
            '%s/inputs/%s.txt',
            __DIR__,
            substr(strtolower(static::class), 3, -1),
        );

        if (!file_exists($inputFileName)) {
            throw new InvalidArgumentException('Input file does not exist: ' . $inputFileName);
        }

        return file_get_contents($inputFileName);
    }

    public function getExampleInput(): string
    {
        $inputFileName = sprintf(
            '%s/examples/inputs/%s.txt',
            __DIR__,
            substr(strtolower(static::class), 3),
        );

        if (!file_exists($inputFileName)) {
            $inputFileName = sprintf(
                '%s/examples/inputs/%s.txt',
                __DIR__,
                substr(strtolower(static::class), 3, -1),
            );
        }

        if (!file_exists($inputFileName)) {
            throw new InvalidArgumentException('Example input file does not exist: ' . static::class);
        }

        return file_get_contents($inputFileName);
    }

    public function getExampleAnswer(): string
    {
        $answerFileName = sprintf(
            '%s/examples/answers/%s.txt',
            __DIR__,
            substr(strtolower(static::class), 3),
        );

        if (!file_exists($answerFileName)) {
            throw new InvalidArgumentException('Example answer file does not exist: ' . $answerFileName);
        }

        return file_get_contents($answerFileName);
    }

    protected abstract function solution(string $input): string;

    public function solve(): string
    {
        return $this->solution($this->getInput());
    }

    public function test(): true
    {
        $expected = $this->getExampleAnswer();
        $actual = $this->solution($this->getExampleInput());

        if ($expected !== $actual) {
            throw new SolutionFailedException($expected, $actual);
        }

        return true;
    }
}