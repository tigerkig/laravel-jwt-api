<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insights;
use Validator;

class InsightsController extends Controller
{

    /**
     * Add new an insight.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'author' => 'required|string',
            'cover' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
            'content' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $image_path = $request->file('cover')->store('image', 'public');
        $insights = Insights::create(array_merge(
            $validator->validated(),
            ['cover' => 'storage/'.$image_path]
        ));

        return response()->json([
            'message' => 'Insight successfully created',
            'insight' => $insights
        ], 201);
    }

    /**
     * Get all insights.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $insights = Insights::paginate(10)->withQueryString();
        return response()->json([
            'insight' => $insights
        ], 201);
    }

    /**
     * Get an insight detail
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail($id)
    {
        $insight_detail = Insights::find($id);
        return response()->json([
            'data' => $insight_detail
        ], 201);
    }

    /**
     * Delete a insight
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        try {
            Insights::find($id)->delete();
            return response()->json([
                'status' => true,
                'message' => 'Insight successfully deleted'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => "Insight can't remove"
            ], 400);
        }
    }

    /**
     * Update new a insight.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request) {

        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'author' => 'required|string',
            'cover' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
            'content' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        try {
            $image_path = $request->file('cover')->store('image', 'public');
            Insights::where('id', $id)->update(array_merge(
                $validator->validated(),
                ['cover' => 'storage/' . $image_path]
            ));
            return response()->json([
                'status' => true,
                'message' => 'Insight successfully updated'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => "Insight can't update"
            ], 400);
        }

    }
}
