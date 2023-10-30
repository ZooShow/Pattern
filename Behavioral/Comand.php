<?php

interface Command
{
    public function execute(): void;
}

class SimpleCommand implements Command
{
    public function __construct(private string $payload)
    {
    }

    public function execute(): void
    {
        echo "SimpleCommand: See, I can do simple things like printing ($this->payload)" . PHP_EOL;
    }
}

class ComplexCommand implements Command
{
    public function __construct(private Receiver $receiver, private string $a, private string $b)
    {
    }

    public function execute(): void
    {
        echo "ComplexCommand: Complex stuff should be done by a receiver object." . PHP_EOL;
        $this->receiver->doSomething($this->a);
        $this->receiver->doSomethingElse($this->b);
    }
}

class Receiver
{
    public function doSomething(string $a): void
    {
        echo "Receiver: Working on ($a.)" . PHP_EOL;
    }

    public function doSomethingElse(string $b): void
    {
        echo "Receiver: Also working on ($b.)" . PHP_EOL;
    }
}

class Invoker
{
    private Command $onStart;
    private Command $onFinish;

    public function setOnStart(Command $command): void
    {
        $this->onStart = $command;
    }

    public function setOnFinish(Command $command): void
    {
        $this->onFinish = $command;
    }

    public function doSomethingImportant(): void
    {
        echo "Invoker: Does anybody want something done before I begin?" . PHP_EOL;
        if ($this->onStart instanceof Command) {
            $this->onStart->execute();
        }

        echo "Invoker: ...doing something really important..." . PHP_EOL;

        echo "Invoker: Does anybody want something done after I finish?" . PHP_EOL;
        if ($this->onFinish instanceof Command) {
            $this->onFinish->execute();
        }
    }
}

$invoker = new Invoker();
$invoker->setOnStart(new SimpleCommand("Say Hi!"));
$receiver = new Receiver();
$invoker->setOnFinish(new ComplexCommand($receiver, "Send email", "Save report"));

$invoker->doSomethingImportant();