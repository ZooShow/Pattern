<?php

declare(strict_types=1);

interface DataSource
{
    public function writeData(string $data);
}

class BaseDataSource implements DataSource
{
    public function writeData(string $data): string
    {
        return $data . PHP_EOL;
    }
}

class DataSourceDecorator implements DataSource
{
    protected DataSource $dataSource;

    public function __construct(DataSource $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    public function writeData(string $data): string
    {
        return $this->dataSource->writeData($data);
    }
}

class EncryptDecorator extends DataSourceDecorator
{
    public function writeData(string $data): string
    {
        echo 'encrypt' . PHP_EOL;
        $enctyptedData = sha1($data);
        $data = $this->dataSource->writeData($enctyptedData);

        return 'encrypted data: ' . $data;
    }
}

class CompressionDecorator extends DataSourceDecorator
{
    public function writeData(string $data): string
    {
        echo 'compressed' . PHP_EOL;
        $comressedData = 'compressed data: ' . $data;

        return $this->dataSource->writeData($comressedData);
    }
}

$baseClass = new BaseDataSource();

$encryptedData = new EncryptDecorator($baseClass);
$compressedData = new CompressionDecorator($baseClass);

$compressedAndEncryptedData = new CompressionDecorator($encryptedData);
$encryptedAndCompressedData = new EncryptDecorator($compressedData);

echo 'compressed and encrypted "mom": ' . $compressedAndEncryptedData->writeData('mom') . PHP_EOL;
echo 'base "mom": ' . $baseClass->writeData('mom') . PHP_EOL;
echo 'encrypted "mom": ' . $encryptedData->writeData('mom') . PHP_EOL;
echo 'encrypted and compressed "mom": ' . $encryptedAndCompressedData->writeData('mom') . PHP_EOL;
