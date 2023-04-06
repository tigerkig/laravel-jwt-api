<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VolunteerRequest;
use App\Models\VolunteerDescription;
use Validator;

class VolunteerController extends Controller
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
     * Get all volunteer requets.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $volunteer_request = VolunteerRequest::all();
        return response()->json([
            'volunteer' => $volunteer_request
        ], 201);
    }

    /**
     * Get a volunteer request detail
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail($id)
    {
        $volunteer_request_detail = VolunteerRequest::find($id);
        return response()->json([
            'data' => $volunteer_request_detail
        ], 201);
    }

    /**
     * Add new a volunteer request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'phone' => 'required|string|max:13',
            'country' => 'required|string',
            'description' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $volunteer_request = VolunteerRequest::create(array_merge(
            $validator->validated()
        ));

        return response()->json([
            'message' => 'Volunteer request successfully created',
            'volunteer' => $volunteer_request
        ], 201);
    }

    /**
     * update a volunteer request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request) {

        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'phone' => 'required|string|max:13',
            'country' => 'required|string',
            'description' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        try {
            VolunteerRequest::where('id', $id)->update(array_merge(
                $validator->validated()
            ));
            return response()->json([
                'message' => 'Volunteer Request successfully updated'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Volunteer Request can't update"
            ], 400);
        }

    }

    /**
     * Delete a volunteer request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        try {
            VolunteerRequest::find($id)->delete();
            return response()->json([
                'message' => 'Volunteer Request successfully deleted'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Volunteer Request can't remove"
            ], 400);
        }
    }

// ---------------------------------------------------------------------------------------------------------

    /**
     * Get all volunteer descriptions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allDescription()
    {
        $volunteer_description = VolunteerDescription::all();
        return response()->json([
            'volunteer' => $volunteer_description
        ], 201);
    }

    /**
     * Get a volunteer description detail
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detailDescription($id)
    {
        $volunteer_description_detail = VolunteerDescription::find($id);
        return response()->json([
            'data' => $volunteer_description_detail
        ], 201);
    }

    /**
     * Add new a volunteer description.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeDescription(Request $request) {

        $validator = Validator::make($request->all(), [
            'slug' => 'required|string',
            'content' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $volunteer_description = VolunteerDescription::create(array_merge(
            $validator->validated()
        ));

        return response()->json([
            'message' => 'Volunteer Description successfully created',
            'volunteer' => $volunteer_description
        ], 201);
    }

    /**
     * update a volunteer description.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateDescription($id, Request $request) {

        $validator = Validator::make($request->all(), [
            'slug' => 'required|string',
            'content' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        try {
            VolunteerDescription::where('id', $id)->update(array_merge(
                $validator->validated()
            ));
            return response()->json([
                'message' => 'Volunteer Description successfully updated'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Volunteer Description can't update"
            ], 400);
        }

    }

    /**
     * Delete a volunteer description
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteDescription($id)
    {
        try {
            VolunteerDescription::find($id)->delete();
            return response()->json([
                'message' => 'Volunteer Description successfully deleted'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Volunteer Description can't remove"
            ], 400);
        }
    }
}
