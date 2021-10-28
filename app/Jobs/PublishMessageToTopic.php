<?php

namespace App\Jobs;

use App\Models\Server;
use App\Models\Topic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class PublishMessageToTopic implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $server;

    public $data;

    public $topic;

    public function __construct(Server $server, Topic $topic, array $data)
    {
        $this->server = $server;
        $this->topic  = $topic;
        $this->data   = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Http::post($this->server->url, [
            'topic' => $this->topic->name,
            'data'  => $this->data,
        ]);
    }
}
