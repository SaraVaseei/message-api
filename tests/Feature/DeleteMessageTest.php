<?php

namespace Tests\Feature;

use App\Models\Message;
use Tests\TestCase;

class DeleteMessageTest extends TestCase
{

    /**
     * Delete message test.
     *
     * @return void
     */
    public function test_delete_message()
    {
        $message = Message::factory()->create();
        $this->delete(route('message.destroy', $message->id));
        $this->assertDatabaseMissing('messages', ['id' => $message->id]);
    }
}
