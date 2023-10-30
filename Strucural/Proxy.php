<?php

declare(strict_types=1);

interface Downloader
{
    public function download(string $url);
}

class SimpleDownloader implements Downloader
{
    public function download(string $url)
    {
        echo 'simple download' . PHP_EOL;
    }
}

class LoggerDownloader implements Downloader
{
    private Downloader $downloader;

    public function __construct()
    {
        $this->downloader = new SimpleDownloader();
    }

    public function download(string $url)
    {
        $startTime = time();
        echo 'download from url ' . PHP_EOL;
        $this->downloader->download($url);
        echo 'downloading time = ' . ($startTime - time());
    }
}

function client(Downloader $downloader)
{
    return $downloader->download('asd');
}


$loggerDownloader = new LoggerDownloader();
client($loggerDownloader);

