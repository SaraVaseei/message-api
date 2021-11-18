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

    /**
     * @OA\Get(
     *      path="/api/message",
     *      operationId="getMessagesList",
     *      tags={"messges"},
     *      summary="Get list of messages",
     *      security={{"bearerAuth":{}}},
     *      description="Returns list of messages",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *      ),
     *      @OA\Response(
     *          response=405,
     *          description="Method Not Allowed"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Server error"
     *      )
     *     )
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
    /**
     * @OA\Post(
     *      path="/api/message",
     *      operationId="storeMessage",
     *      tags={"message"},
     *      security={{"bearerAuth":{}}},
     *      summary="Store a new message",
     *      description="Returns message data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"content"},
     *                  @OA\Property(
     *                      property="content",
     *                      type="string",
     *                      description="Message content"
     *                  ),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized"
     *      ),
     *      @OA\Response(
     *          response=405,
     *          description="Method Not Allowed"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Server error"
     *      )
     * )
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
    /**
     * @OA\Get(
     *      path="/api/message/{id}",
     *      operationId="getMessageById",
     *      tags={"message"},
     *      security={{"bearerAuth":{}}},
     *      summary="Get message information",
     *      description="Returns message data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Message id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      ),
     *      @OA\Response(
     *          response=405,
     *          description="Method Not Allowed"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Server error"
     *      )
     * )
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
    /**
     * @OA\Put(
     *      path="/api/message/{id}",
     *      operationId="updateMessage",
     *      tags={"message"},
     *      security={{"bearerAuth":{}}},
     *      summary="Update existing message",
     *      description="Returns updated message data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Message id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"content"},
     *                  @OA\Property(
     *                      property="content",
     *                      type="string",
     *                      description="Message content"
     *                  ),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized"
     *      ),
     *      @OA\Response(
     *          response=405,
     *          description="Method Not Allowed"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Server error"
     *      )
     * )
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
    /**
     * @OA\Delete(
     *      path="/api/message/{id}",
     *      operationId="deleteMessage",
     *      tags={"message"},
     *      security={{"bearerAuth":{}}},
     *      summary="Delete existing message",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Message id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=204,
     *          description="Internal error"
     *      ),@OA\Response(
     *          response=404,
     *          description="Not Found"
     *      )
     * )
     */
    public function destroy($id)
    {
        $message = Message::find($id);
        if ($message) {
            $deleted = $message->delete();
            if ($deleted) {
                return response()->success([], 'Message was deleted successfully!', 202);
            } else {
                return response()->error(204, 'While deleting massage, an error occurred. Please again try later.');
            }
        } else {
            return response()->error(404, 'The message is not available. Please check ID and try again.');
        }
    }
}
