<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PublishController extends Controller
{
    public function publish($topic_name)
    {
        $topic = Topic::where('name', $topic_name)->firstOrFail();

        foreach ($topic->servers as $server) {
            Http::post($server->url, [
                'topic' => $topic_name,
                'data' => request()->all(),
            ]);
        }
    }
}
