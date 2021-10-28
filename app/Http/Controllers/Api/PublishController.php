<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\PublishMessageToTopic;
use App\Models\Topic;

class PublishController extends Controller
{
    public function publish($topic_name)
    {
        $topic = Topic::where('name', $topic_name)->firstOrFail();

        foreach ($topic->servers as $server) {
            PublishMessageToTopic::dispatch(
                $server,
                $topic,
                request()->all()
            );
        }

        return response()->json([
            'topic' => $topic->name,
            'data' => request()->all(),
        ]);
    }
}
