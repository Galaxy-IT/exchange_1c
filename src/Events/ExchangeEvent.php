<?php

namespace GalaxyIT\LaravelExchange1C\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ExchangeEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $type;
    public string $mode;
    public ?string $response;

    public function __construct(string $type, string $mode, string $response)
    {
        $this->type = $type;
        $this->mode = $mode;
        $this->response = $response;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('exchange-1c');
    }
}
