<?php

interface Sorter
{
    /**
     * @param int[] $data
     * @return int[]
     */
    public function sort(array $data): array;
}

class AscSorter implements Sorter
{
    public function sort(array $data): array
    {
        sort($data);

        return $data;
    }
}

class DescSorter implements Sorter
{
    public function sort(array $data): array
    {
        rsort($data);

        return $data;
    }
}

class Service
{
    private Sorter $sorter;

    public function __construct(Sorter $sorter)
    {
        $this->sorter = $sorter;
    }

    public function setSorter(Sorter $sorter): void
    {
        $this->sorter = $sorter;
    }

    /**
     * @param int[] $data
     * @return int[]
     */
    public function doSort(array $data): array
    {
        return $this->sorter->sort($data);
    }
}

function client(Service $service)
{
    print_r($service->doSort([10, 12, 0, 1, 9, -3, 1]));
}

$asc = new AscSorter();
$desc = new DescSorter();
$service = new Service($asc);

echo 'Asc:' . PHP_EOL;
client($service);

echo 'Desc:' . PHP_EOL;
$service->setSorter($desc);
client($service);


