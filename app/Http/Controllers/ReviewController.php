<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Validator;
use Exception;


class ReviewController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        try {
            $review = Review::with('user')->get();
            if ($review->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No reviews found',
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => $review,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve reviews: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'review' => 'required|string',
                'user_id' => 'required|exists:users,id',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $review = Review::create($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Review successfully created',
                'data ' => $review,
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create review: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $review
     * @return \Illuminate\Http\Response
     */
    public function show($review)
    {
        try {
            $result = Review::with('user')->findOrFail($review);

            return response()->json([
                'success' => true,
                'data' => $result
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => "Review not found"
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Failed to fetch review data: " . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $review)
    {
        $review = Review::find($review);

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found'
            ], 404);
        }

        try {
            $validator = Validator::make($request->all(), [
                'review' => 'required|string|max:255',
                'user_id' => 'required|exists:users,id',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $review->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Review successfully updated',
                'data ' => $review,
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update review: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $review
     * @return \Illuminate\Http\Response
     */
    public function delete($review)
    {
        try {
            $review = Review::findOrFail($review);
            $review->delete();

            return response()->json([
                'success' => true,
                'message' => 'Review successfully deleted',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete review: ' . $e->getMessage(),
            ], 500);
        }
    }
}
