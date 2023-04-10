<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Validator;

class ContactController extends Controller
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
     * Get all contacts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $data = Contact::all();
        return response()->json([
            'data' => $data
        ], 201);
    }

    /**
     * Add new a contact.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:17',
            'email' => 'required|string|email',
            'phone' => 'required|string|max:13',
            'country' => 'required',
            'message' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $details = array_merge($validator->validated());
        try {
            \Mail::to(env('MAIL_RECEIVER_ADDRESS'))->send(new \App\Mail\ContactMail($details));
            $data = Contact::create($details);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => "Mail can't send. Please contact with admin or check the email address.",
            ], 201);
        }
        
        return response()->json([
            'message' => 'New Contact successfully created',
            'data' => $data
        ], 201);
    }
}
