<?php

namespace App\Http\Controllers;

use App\Models\Fundraiser;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Validator;

class FundraiserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $fundraisers = Fundraiser::with('organization')->get();
            if ($fundraisers->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No fundraisers found',
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => $fundraisers,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve fundraisers: ' . $e->getMessage(),
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
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'target' => 'required|numeric|min:0',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'organization_id' => 'required|exists:organizations,id',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $fundraiser = Fundraiser::create($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'fundraiser successfully created',
                'data ' => $fundraiser,
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create fundraiser: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $fundraiser
     * @return \Illuminate\Http\Response
     */
    public function show($fundraiser)
    {
        try {
            $result = Fundraiser::with('organization')->findOrFail($fundraiser);

            return response()->json([
                'success' => true,
                'data' => $result
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => "Fundraiser not found"
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Failed to fetch fundraiser data: " . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $fundraiser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $fundraiser)
    {
        $fundraiser = Fundraiser::find($fundraiser);

        if (!$fundraiser) {
            return response()->json([
                'success' => false,
                'message' => 'Fundraiser not found'
            ], 404);
        }

        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'target' => 'required|numeric|min:0',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'organization_id' => 'required|exists:organizations,id',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $fundraiser->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'fundraiser successfully updated',
                'data ' => $fundraiser,
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update fundraiser: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $fundraiser
     * @return \Illuminate\Http\Response
     */
    public function destroy($fundraiser)
    {
        try {
            $fundraiser = Fundraiser::findOrFail($fundraiser);
            $fundraiser->delete();

            return response()->json([
                'success' => true,
                'message' => 'Fundraiser successfully deleted',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete fundraiser: ' . $e->getMessage(),
            ], 500);
        }
    }
}
