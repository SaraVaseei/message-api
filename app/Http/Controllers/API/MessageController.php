<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\Message;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    public $message;

    public function __construct()
    {
        $this->message = new Message;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $messages = Message::all();
        return $messages ? response()->success($messages) : response()->error(404, 'The message list is empty.');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreMessageRequest $request
     * @return void
     */
    public function store(StoreMessageRequest $request)
    {

        // Retrieve the validated input data...
        $validated = $request->validated();

        if ($validated) {
            $message = Message::create([
                'id' => (string)Str::uuid(),
                'content' => $this->message->purifyHTMLTags($validated['content'])
            ]);

            if ($message) {
                return response()->success($message, 'Message is created successfully!', 201);
            } else {
                return response()->error(500, 'While creating massage, an error occurred. Please again try later.');
            }
        } else {
            return response()->error(400, 'Data input validation has some errors.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $message = Message::find($id);
        if ($message) {
            $message->counter = $message->counter + 1;
            $message->save();
            return response()->success($message);
        } else {
            return response()->error(404, 'The message is not available. Please check ID and try again.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateMessageRequest $request
     * @param int $id
     * @return int
     */
    public function update(UpdateMessageRequest $request, $id)
    {
        $message = Message::find($id);
        if ($message) {

            // Retrieve the validated input data...
            $validated = $request->validated();

            if ($validated) {
                $message->content = $this->message->purifyHTMLTags($validated['content']);
                $message->save();
                return response()->success($message, 'Message is updated successfully!');
            } else {
                return response()->error(400, 'Data input validation has some errors.');
            }
        } else {
            return response()->error(404, 'The message is not available. Please check ID and try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $message = Message::find($id);
        if ($message) {
            $deleted = $message->delete();
            if ($deleted) {
                return response()->success([], 'Number product was deleted successfully!', 202);
            } else {
                return response()->error(204, 'While deleting massage, an error occurred. Please again try later.');
            }
        } else {
            return response()->error(404, 'The message is not available. Please check ID and try again.');
        }
    }
}
