<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessengerTest extends TestCase
{
    // Note: Not using RefreshDatabase here as we are on a running dev environment with data we want to keep.
    // We will clean up our test data manually or use transactions if possible, but for now we create temp users.

    public function test_can_access_messenger_index()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('messenger.index'));

        $response->assertStatus(200);
        $response->assertViewIs('messenger.index');

        $user->forceDelete();
    }

    public function test_can_start_conversation()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $this->actingAs($user1);

        $response = $this->get(route('messenger.start', $user2));

        // Should redirect to messenger index with conversation_id or just show the page
        $response->assertStatus(302);

        $conversation = Conversation::whereHas('users', function ($q) use ($user1) {
            $q->where('users.id', $user1->id);
        })->whereHas('users', function ($q) use ($user2) {
            $q->where('users.id', $user2->id);
        })->first();

        $this->assertNotNull($conversation);
        $this->assertEquals('direct', $conversation->type);

        $user1->forceDelete();
        $user2->forceDelete();
        if ($conversation)
            $conversation->delete();
    }

    public function test_can_send_message()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create conversation manually
        $conversation = Conversation::create(['type' => 'direct']);
        $conversation->users()->attach([$user1->id, $user2->id]);

        $this->actingAs($user1);

        $response = $this->postJson(route('messenger.messages.store', $conversation), [
            'body' => 'Hello World'
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'body' => 'Hello World',
            'sender_id' => $user1->id
        ]);

        // Clean up
        $conversation->messages()->delete();
        $conversation->users()->detach();
        $conversation->delete();
        $user1->forceDelete();
        $user2->forceDelete();
    }
}
