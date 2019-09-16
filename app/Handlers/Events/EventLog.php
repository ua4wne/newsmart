<?php

namespace App\Handlers\Events;

use App\Events\AddEventLogs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventLog
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AddEventLogs  $event
     * @return void
     */
    public function handle(AddEventLogs $event)
    {
        $data = date('Y-m-d H:i:s');
        \App\Models\Eventlog::create([
            'type' => $event->type,
            'msg' => $event->text,
            'status' => 1,
            'created_at' => $data,
            'updated_at' => $data,
        ]);
    }
}
