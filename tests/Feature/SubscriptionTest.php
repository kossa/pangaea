<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function add_validation_to_subscription_creation()
    {
        $topic = $this->faker->word();

        $this->postJson('/api/subscribe/', [])
                ->assertNotFound();

        $this->postJson('/api/subscribe/' . $topic, [])
                ->assertJsonValidationErrors(['url']);
    }

    /** @test */
    public function create_subscription()
    {
        $this->withoutExceptionHandling();

        $topic = $this->faker->word();
        $url = $this->faker->url();

        $this->postJson('/api/subscribe/' . $topic, [
            'url' => $url
        ])->assertStatus(201);

        $this->assertDatabaseHas('servers', [
            'url' => $url
        ]);

        $this->assertDatabaseHas('server_topic', [
            'server_id' => 1,   // first server
            'topic_id'  => 1,   // first topic
        ]);
    }
}
