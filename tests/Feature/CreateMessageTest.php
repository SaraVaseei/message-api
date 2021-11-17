<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateMessageTest extends TestCase
{

    /**
     * Create message test.
     *
     * @return void
     */
    public function test_create_message()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['create-message']
        );
        $message = Message::factory()->make();
        $this->post(route('message.store'), $message->toArray())->assertStatus(200);
    }

    /**
     * Show number product test.
     *
     * @return void
     */
    public function test_show_message()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['view-message']
        );

        $message = Message::factory()->create();
        $response = $this->get(route('message.show', $message['id']));
        $response->assertStatus(200);
    }

    /**
     * Show messages list test.
     *
     * @return void
     */
    public function test_index_message()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['view-messages']
        );
        $response = $this->get(route('message.index'));
        $response->assertStatus(200);
    }

    /**
     * Create message validation test.
     *
     * @return void
     */
    public function test_message_required_values()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['message-requirements']
        );
        $this->post(route('message.store'), [])->assertSessionHasErrors(['content']);
    }
}
