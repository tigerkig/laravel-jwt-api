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
                'name' => 'required|string',
                'about' => 'required',
                'mission' => 'required',
                'plans' => 'required',
                'history' => 'required',
                'details' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $organization = Organization::create($validator->validated());

            $photos = $request->file('photos');
            $registrations = $request->file('registrations');
            if (!empty($photos) || !empty($registrations)) {
                $organizationFiles = [];
                foreach ($photos as $photo) {
                    $path = $photo->store('image', 'public');
                    $type = $photo->getClientOriginalExtension() === 'pdf' ? 'Registration' : 'Photo';
                    $organizationFiles[] = new OrganizationFiles([
                        'file_name' => 'storage/'.$path,
                        'type' => $type,
                        'organization_id' => $organization->id
                    ]);
                }

                foreach ($registrations as $registration) {
                    $path = $registration->store('registration', 'public');
                    $type = $registration->getClientOriginalExtension() === 'pdf' ? 'Registration' : 'Photo';
                    $organizationFiles[] = new OrganizationFiles([
                        'file_name' => 'storage/'.$path,
                        'type' => $type,
                        'organization_id' => $organization->id
                    ]);
                }
                try {
                    $organization->organizationFiles()->saveMany($organizationFiles);
                    $organizationFiles[] = $organizationFiles;

                    return response()->json([
                        'success' => true,
                        'message' => 'Organization successfully created with photos',
                        'data ' => $organization,
                    ], 201);
                } catch (\Exception $e) {
                    return response()->json([
                        'message' => 'Failed to save organization file: ' . $e->getMessage()
                    ], 500);
                }

            }

            return response()->json([
                'success' => true,
                'message' => 'Organization successfully created',
                'data ' => $organization,
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
                'about' => 'required',
                'mission' => 'required',
                'plans' => 'required',
                'history' => 'required',
                'details' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $organization->update($validator->validated());

            $photos = $request->file('photos');
            $registrations = $request->file('registrations');
            if (!empty($photos) || !empty($registrations)) {
                $organizationFiles = [];
                foreach ($photos as $photo) {
                    $path = $photo->store('image', 'public');
                    $type = $photo->getClientOriginalExtension() === 'pdf' ? 'Registration' : 'Photo';
                    $organizationFiles[] = new OrganizationFiles([
                        'file_name' => 'storage/'.$path,
                        'type' => $type,
                        'organization_id' => $organization->id
                    ]);
                }

                foreach ($registrations as $registration) {
                    $path = $registration->store('registration', 'public');
                    $type = $registration->getClientOriginalExtension() === 'pdf' ? 'Registration' : 'Photo';
                    $organizationFiles[] = new OrganizationFiles([
                        'file_name' => 'storage/'.$path,
                        'type' => $type,
                        'organization_id' => $organization->id
                    ]);
                }
                try {
                    $organization->organizationFiles()->saveMany($organizationFiles);
                    $organizationFiles[] = $organizationFiles;

                    return response()->json([
                        'success' => true,
                        'message' => 'Organization successfully updated with photos',
                        'data ' => $organization,
                    ], 201);
                } catch (\Exception $e) {
                    return response()->json([
                        'message' => 'Failed to save organization file: ' . $e->getMessage()
                    ], 500);
                }
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update organization: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Organization successfully updated',
            'data ' => $organization,
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
