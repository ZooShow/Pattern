<?php

declare(strict_types=1);

class Facade
{
    private Subsystem1 $subsystem1;
    private Subsystem2 $subsystem2;

    public function __construct(Subsystem1 $subsystem1, Subsystem2 $subsystem2)
    {
        $this->subsystem1 = $subsystem1;
        $this->subsystem2 = $subsystem2;
    }

    public function doSomething(): void
    {
        $data = $this->subsystem1->operation1();

        if ($data !== 'something') {
            $data = $this->subsystem1->operation2($data);
        }

        echo $this->subsystem2->operation2($data);
    }
}

class Subsystem1
{
    public function operation1(): string
    {
        return 'Subsystem1 operation 1';
    }

    public function operation2(string $data): string
    {
        return 'Subsystem1 operation 2 with ' . $data;
    }
}

class Subsystem2
{
    public function operation1(): string
    {
        return 'Subsystem2 operation 1';
    }

    public function operation2(string $data): string
    {
        return 'Subsystem2 operation 2 ' . $data;
    }
}

$sub1 = new Subsystem1();
$sub2 = new Subsystem2();
$facade = new Facade($sub1, $sub2);

$facade->doSomething();