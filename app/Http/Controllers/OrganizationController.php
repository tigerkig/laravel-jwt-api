<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\OrganizationFiles;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Validator;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $organizations = Organization::with('organizationFiles')->get();
            if ($organizations->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No organizations found',
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => $organizations,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve organizations: ' . $e->getMessage(),
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
                'name' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $organization = Organization::create($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Organization successfully created',
                'data' => $organization,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create organization: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $organization
     * @return \Illuminate\Http\Response
     */
    public function show($organization)
    {
        try {
            $result = Organization::with('organizationFiles')->findOrFail($organization);

            return response()->json([
                'success' => true,
                'data' => $result
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => "Organization not found"
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Failed to fetch organization data: " . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $organization)
    {
        $organization = Organization::find($organization);

        if (!$organization) {
            return response()->json([
                'success' => false,
                'message' => 'Organization not found'
            ], 404);
        }
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'about' => 'nullable|string',
                'mission' => 'nullable|string',
                'plans' => 'nullable|string',
                'history' => 'nullable|string',
                'founder_details' => 'nullable|string',
                'goals' => 'nullable|string',
                'information' => 'nullable|string',
                'location' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $organization->update($validator->validated());

            $photos = $request->file('photos');
            $registrations = $request->file('registrations');
            $video = $request->file('video');
            $organizationFiles = [];

            if (!empty($photos)) {
                $path = $photos->store('image', 'public');
                $type = $photos->getClientOriginalExtension() === 'pdf' ? 'Registration' : 'Photo';
                $organizationFiles[] = new OrganizationFiles([
                    'file_name' => 'storage/organizations/'.$path,
                    'type' => $type,
                    'organization_id' => $organization->id
                ]);
            }

            if (!empty($registrations)) {
                $path = $registrations->store('registration', 'public');
                $type = $registrations->getClientOriginalExtension() === 'pdf' ? 'Registration' : 'Photo';
                $organizationFiles[] = new OrganizationFiles([
                    'file_name' => 'storage/organizations/'.$path,
                    'type' => $type,
                    'organization_id' => $organization->id
                ]);
            }

            if (!empty($video)) {
                $extension = $video->getClientOriginalExtension();
                if ($extension === 'mov' || $extension === 'mp4' || $extension === 'avi') {
                    $path = $video->store('video', 'public');
                    $organizationFiles[] = new OrganizationFiles([
                        'file_name' => 'storage/organizations/'.$path,
                        'type' => 'Video',
                        'organization_id' => $organization->id
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid video file type. Allowed extensions are .mov, .mp4, and .avi.'
                    ], 400);
                }
            }

            try {
                $organization->organizationFiles()->saveMany($organizationFiles);
                $organizationFiles[] = $organizationFiles;

                return response()->json([
                    'success' => true,
                    'message' => 'Organization successfully updated with photos',
                    'data' => $organization,
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Failed to save organization file: ' . $e->getMessage()
                ], 500);
            }
        }
        catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update organization: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Organization successfully updated',
            'data' => $organization,
        ], 201);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($organization)
    {
        try {
            $organization = Organization::findOrFail($organization);
            $organization->delete();

            return response()->json([
                'success' => true,
                'message' => 'Organization successfully deleted',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete organization: ' . $e->getMessage(),
            ], 500);
        }
    }
}
