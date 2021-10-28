<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Models\Server;
use App\Models\Topic;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function subscribe(SubscriptionRequest $request, $topic_name)
    {
        $server = Server::firstOrCreate([
            'url' => $request->url,
        ]);

        $topic = Topic::firstOrCreate([
            'name' => $topic_name,
        ]);

        $server->topics()->attach($topic);

        return response()->json([
            'url'   => $server->url,
            'topic' => $topic->name,
        ], 201);
    }
}
