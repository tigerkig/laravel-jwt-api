<?php

namespace App\Http\Controllers;

use App\Models\FundraiserComment;
use Illuminate\Http\Request;
use Exception;
use Validator;

class FundraiserCommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($fundraiser_id)
    {
        try {
            $fundraiserComments = FundraiserComment::where('fundraiser_id', $fundraiser_id)->get();
            if ($fundraiserComments->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No comments found',
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => $fundraiserComments,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve comments: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($fundraiserId, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'content' => 'required|string|min:3',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $user = auth()->user();

            if ($user) {
                $fundraiserComment = FundraiserComment::create(array_merge(
                    $validator->validated(),
                    ['user_id' => $user->id],
                    ['fundraiser_id' => $fundraiserId]
                ));

                return response()->json([
                    'success' => true,
                    'message' => 'Comment successfully created',
                    'data ' => $fundraiserComment,
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 404);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create comment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function update($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'content' => 'required|string|min:3',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $user = auth()->user();

            $comment = FundraiserComment::where('id', $id)->firstOrFail();
            if ($user) {
                if ($comment->user_id !== $user->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized',
                    ], 403);
                }

                $comment->content = $request->input('content');
                $comment->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Comment successfully updated',
                    'data ' => $comment,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 404);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update comment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($fundraiserComment)
    {
        try {
            $fundraiserComment = FundraiserComment::findOrFail($fundraiserComment);

            $user = auth()->user();
            if ($user) {
                if ($fundraiserComment->user_id !== $user->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized',
                    ], 403);
                } else {
                    $fundraiserComment->delete();

                    return response()->json([
                        'success' => true,
                        'message' => 'Comment successfully deleted',
                    ], 200);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete comment: ' . $e->getMessage(),
            ], 500);
        }
    }
}
