<?php

class Day15A extends BaseSolution
{
    protected function solution(string $input): string
    {
        $strings = explode(",", $input);
        return array_reduce(
            $strings,
            fn($carry, $string) => $carry + array_reduce(
                str_split($string),
                fn($carry, $char) => (($carry + ord($char)) * 17) % 256,
                0
            ), 0);
    }
}
