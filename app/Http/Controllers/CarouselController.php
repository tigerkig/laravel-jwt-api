<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carousel;
use Validator;
use Exception;

class CarouselController extends Controller
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
     * Add new a Carousel.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'cover' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $image_path = $request->file('cover')->store('image', 'public');
        $data = Carousel::create(array_merge(
            $validator->validated(),
            ['cover' => 'storage/' . $image_path]
        ));

        return response()->json([
            'message' => 'Carousel successfully created',
            'data' => $data
        ], 201);
    }

    /**
     * Get all Carousels.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $data = Carousel::all();
        return response()->json([
            'data' => $data
        ], 201);
    }

    /**
     * Delete a Carousel
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        try {
            $carousel = Carousel::findOrFail($id);
            $carousel->delete();

            return response()->json([
                'success' => true,
                'message' => 'Carousel successfully deleted',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete carousel: ' . $e->getMessage(),
            ], 500);
        }

    }

    /**
     * Update new a Carousel.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request) {

        $carousel_id = Carousel::find($id);

        if (!$carousel_id) {
            return response()->json([
                'success' => false,
                'message' => 'Carousel not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'cover' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        try {
            $image_path = $request->file('cover')->store('image', 'public');
            Carousel::where('id', $id)->update(array_merge(
                $validator->validated(),
                ['cover' => 'storage/' . $image_path]
            ));
            return response()->json([
                'status' => true,
                'message' => 'Carousel successfully updated'
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update carousel: ' . $e->getMessage()
            ], 400);
        }

    }
}
