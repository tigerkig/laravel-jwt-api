<?php

namespace App\Http\Controllers;

use App\Models\Fundraiser;
use App\Models\Organization;
use App\Models\Supporter;
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
                'name' => 'required|string|max:255',
                'avatar' => 'nullable|image|max:2048',
                'amount_donated' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 400);
            }

            $supporter = Supporter::create(
                array_merge(
                    $validator->validated(),
                    ['fundraiser_id' => $fundraiser_id]
                )
            );

            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $avatarPath = $avatar->store('avatars', 'public');
                $supporter->avatar = 'storage/' . $avatarPath;
            }

            $fundraiser = Fundraiser::find($fundraiser_id);

            if ($fundraiser->status !== 0) {
                return response()->json(['message' => 'Fundraiser is not in progress'], 400);
            }

            $fundraiser->amount_raised += $request->input('amount_donated');
            $fundraiser->save();

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
     * @param  \App\Models\Supporter  $supporter
     * @return \Illuminate\Http\Response
     */
    public function show(Supporter $supporter)
    {
        //
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
     * @param  \App\Models\Supporter  $supporter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supporter $supporter)
    {
        //
    }
}
