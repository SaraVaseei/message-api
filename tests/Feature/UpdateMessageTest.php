<?php

namespace Tests\Feature;

use App\Models\Message;
use Tests\TestCase;

class UpdateMessageTest extends TestCase
{

    /**
     * Update number product test.
     *
     * @return void
     */
    public function test_update_message()
    {
        $message = Message::factory()->create();
        $message->content = "Updated message";
        $this->put(route('message.update', $message->id), $message->toArray());
        $this->assertDatabaseHas('messages', ['id' => $message->id, 'content' => 'Updated message']);

    }
}
