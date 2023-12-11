<?php

class InputDownloader
{
    private const SESSION_COOKIE = '';

    public function downloadInputToFile(int $day, string $file): void
    {
        $sessionCookie = file_get_contents(__DIR__ . '/.aoc_session');
        $options = ['http' => ['header' => sprintf("Cookie: session=%s\r\n", $sessionCookie)]];
        $context = stream_context_create($options);
        $url = sprintf('https://adventofcode.com/2023/day/%d/input', $day);
        $response = file_get_contents($url, false, $context);
        if (!$response) {
            echo "\nError getting input\n";
            return;
        }
        file_put_contents($file, $response);
        echo "\nGot input from AoC\n";
    }
}