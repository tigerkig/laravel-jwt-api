<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Validator;
use Exception;
use Mail;
use App\Mail\ContactMail;

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
            Mail::to(env('MAIL_RECEIVER_ADDRESS'))->send(new ContactMail($details));
            $data = Contact::create($details);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 403);
        }
        
        return response()->json([
            'message' => 'New Contact successfully created',
            'data' => $data
        ], 201);
    }
}
