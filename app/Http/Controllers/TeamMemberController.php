<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeamMember;
use Validator;

class TeamMemberController extends Controller
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
     * Get all team members.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $team_members = TeamMember::all();
        return response()->json([
            'members' => $team_members
        ], 201);
    }

    /**
     * Add new a team member.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'job_role' => 'required|string',
            'photo' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
            'description' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $image_path = $request->file('photo')->store('image', 'public');
        $team_members = TeamMember::create(array_merge(
            $validator->validated(),
            ['photo' => 'storage/'.$image_path]
        ));

        return response()->json([
            'message' => 'Team member successfully created',
            'member' => $team_members
        ], 201);
    }

    /**
     * Add new a team member.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'job_role' => 'required|string',
            'photo' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
            'description' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $image_path = $request->file('photo')->store('image', 'public');

        try {
            TeamMember::where('id', $id)->update(array_merge(
                $validator->validated(),
                ['photo' => 'storage/'.$image_path]
            ));
            return response()->json([
                'message' => 'Team member successfully updated'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Team member can't update"
            ], 400);
        }

    }

    /**
     * Get a team member detail
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail($id)
    {
        $team_member_detail = TeamMember::find($id);
        return response()->json([
            'data' => $team_member_detail
        ], 201);
    }

    /**
     * Delete a team member
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        try {
            TeamMember::find($id)->delete();
            return response()->json([
                'message' => 'Team member successfully deleted'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Team member can't remove"
            ], 400);
        }
    }
}
