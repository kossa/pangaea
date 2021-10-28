<?php

namespace Tests\Feature;

use App\Models\Server;
use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PublishTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function publish_message()
    {
        $this->withoutExceptionHandling();
        Http::fake();

        // create topics
        $topic1 = Topic::factory()->create(['name' => $this->faker->word]);
        $topic2 = Topic::factory()->create(['name' => $this->faker->word]);

        // create servers
        $server1 = Server::factory()->create();
        $server2 = Server::factory()->create();

        // attach topics to servers
        $server1->topics()->attach($topic1);
        $server2->topics()->attach($topic2);

        // publish to first topic
        $this->postJson('/api/publish/' . $topic1->name, [
            'message' => 'Hello World'
        ])->assertStatus(200);

        // it should send a message to the server1
        Http::assertSent(function ($request) use ($server1, $topic1) {
            return $request->url() == $server1->url &&
                    $request['topic'] == $topic1->name &&
                    $request['data']['message'] == 'Hello World';
        });

        // and not to the server2
        Http::assertNotSent(function ($request) use ($server2) {
            return $request->url() == $server2->url;
        });
    }
}
