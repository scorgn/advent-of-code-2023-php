<?php

class SolutionFailedException extends RuntimeException
{
    public function __construct(private string $expected, private string $actual)
    {
        parent::__construct();
    }

    public function getExpected(): string
    {
        return $this->expected;
    }

    public function getActual(): string
    {
        return $this->actual;
    }
}