<?php

namespace App\Http\Controllers;

use App\Models\Fundraiser;
use App\Models\Supporter;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Validator;

class SupporterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($fundraiser_id)
    {
        try {
            $supporters = Supporter::where('fundraiser_id', $fundraiser_id)->get();
            if ($supporters->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No Supporters found',
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => $supporters,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve supporters: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $fundraiser_id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'string|max:255',
                'avatar' => 'nullable|image|max:2048',
                'amount_donated' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 400);
            }

            $fundraiser = Fundraiser::find($fundraiser_id);

            if ($fundraiser->status !== 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'This fundraiser is not active'
                ], 400);
            }

            $fundraiser->amount_raised += $request->input('amount_donated');

            if ($fundraiser->amount_raised >= $fundraiser->target) {
                $fundraiser->status = 1;
            }

            $fundraiser->save();
            $supporter = Supporter::create(array_merge(
                $validator->validated(),
                ['fundraiser_id' => $fundraiser_id]
            ));

            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $avatarPath = $avatar->store('avatars', 'public');
                $supporter->avatar = 'storage/' . $avatarPath;
            }

            return response()->json([
                'success' => true,
                'message' => 'Supporter successfully donated',
                'data ' => $supporter,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to donate: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $supporter
     * @return \Illuminate\Http\Response
     */
    public function show($supporter)
    {
        try {
            $result = Supporter::where('id', $supporter)
                ->firstOrFail();
            return response()->json([
                'success' => true,
                'data' => $result
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => "Supporter not found"
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Failed to fetch supporter data: " . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supporter  $supporter
     * @return \Illuminate\Http\Response
     */
    public function edit(Supporter $supporter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supporter  $supporter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supporter $supporter)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $supporter
     * @return \Illuminate\Http\Response
     */
    public function destroy($supporter)
    {
        try {
            $supporter = Supporter::findOrFail($supporter);
            $supporter->delete();

            return response()->json([
                'success' => true,
                'message' => 'Supporter successfully deleted',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete supporter: ' . $e->getMessage(),
            ], 500);
        }
    }
}
