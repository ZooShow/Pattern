<?php

declare(strict_types=1);

interface Graphic
{
    public function move(int $x, int $y);
    public function draw();
}

class Point implements Graphic
{
    protected int $x;
    protected int $y;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function move(int $x, int $y)
    {
        $this->x += $x;
        $this->y += $y;
    }

    public function draw()
    {
        echo sprintf("X = %s, Y = %s ", $this->x, $this->y);
    }
}

class Circle extends Point
{
    private int $radius;

    public function __construct(int $x, int $y, int $radius)
    {
        parent::__construct($x, $y);
        $this->radius = $radius;
    }

    public function draw()
    {
        parent::draw();

        echo sprintf(" with radius = %s ", $this->radius);
    }
}

class CompositeGraphic implements Graphic
{
    /**
     * @var Graphic[]
     */
    private array $graphics;

    public function add(Graphic $child)
    {
        $this->graphics[] = $child;
    }

    public function move(int $x, int $y)
    {
        foreach ($this->graphics as $graphic) {
            $graphic->move($x, $y);
        }
    }

    public function draw()
    {
        foreach ($this->graphics as $graphic) {
            $graphic->draw();
            echo PHP_EOL;
        }
    }
}

$compositeGraphic = new CompositeGraphic();

$compositeGraphic->add(new Circle(0, 0, 10));
$compositeGraphic->add(new Point(10, 15));
$compositeGraphic->add(new Point(11, 1));
$compositeGraphic->add(new Point(11, 10));

$compositeGraphic->draw();

$compositeGraphic->move(1, 2);
echo PHP_EOL;
$compositeGraphic->draw();