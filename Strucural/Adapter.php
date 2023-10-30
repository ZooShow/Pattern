<?php

declare(strict_types=1);

interface Notificator
{
    public function notify(string $message): void;
}

class StandardNotificator implements Notificator
{
    public function notify(string $message): void
    {
        echo $message . PHP_EOL;
    }
}

class MattermostApi
{
    public function __construct(private string $userName, private string $password)
    {
    }

    public function auth(): string
    {
        return  md5($this->userName . $this->password);
    }

    public function sendMessage(string $message, string $token): void
    {
        echo sprintf('sending message "%s" with auth token "%s"' . PHP_EOL, $message, $token);
    }
}

class MattermostNotificator implements Notificator
{
    private MattermostApi $api;

    public function __construct(string $userName, string $password)
    {
        $this->api = new MattermostApi($userName, $password);
    }

    public function notify(string $message): void
    {
        $token = $this->api->auth();
        $this->api->sendMessage($message, $token);
    }
}

function client(Notificator $notificator): void
{
    $notificator->notify('Message');
}

echo 'Standard notifier:' . PHP_EOL;
$stdNotifier = new StandardNotificator();
client($stdNotifier);

echo 'Mattermost notifier:' . PHP_EOL;
$mtmNotifier = new MattermostNotificator('username', 'password');
client($mtmNotifier);
