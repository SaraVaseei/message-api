<?php

namespace Tests\Feature;

use App\Models\Message;
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
        $this->post(route('message.store'), [])->assertSessionHasErrors(['content']);
    }
}
