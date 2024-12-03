<?php

namespace App\Events;

use App\Models\Film;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FilmImported implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public int $filmId
    ) {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>|Channel
     */
    public function broadcastOn(): array|Channel
    {
        return new Channel('films.' . $this->filmId);
    }

    public function broadcastAs(): string
    {
        return 'film.imported';
    }

    // public function broadcastWith(): array
    // {
    //     return ['id' => $this->user->id];
    // }
}
