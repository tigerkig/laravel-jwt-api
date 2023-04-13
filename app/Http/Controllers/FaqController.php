<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;
use Validator;

class FaqController extends Controller
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
     * Get all FAQ.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $data = Faq::all();
        return response()->json([
            'data' => $data
        ], 201);
    }

    /**
     * Add new a FAQ.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $data = Faq::create(array_merge(
            $validator->validated()
        ));

        return response()->json([
            'message' => 'New FAQ successfully created',
            'data' => $data
        ], 201);
    }

    /**
     * Update a FAQ.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request) {

        $validator = Validator::make($request->all(), [
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }


        try {
            Faq::where('id', $id)->update(array_merge(
                $validator->validated()
            ));
            return response()->json([
                'status' => true,
                'message' => 'FAQ successfully updated'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => "FAQ can't update"
            ], 400);
        }

    }

    /**
     * Get a FAQ detail
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail($id)
    {
        $data = Faq::find($id);
        return response()->json([
            'data' => $data
        ], 201);
    }

    /**
     * Delete a FAQ
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        try {
            Faq::find($id)->delete();
            return response()->json([
                'status' => true,
                'message' => 'FAQ successfully deleted'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => "FAQ can't remove"
            ], 400);
        }
    }
}
