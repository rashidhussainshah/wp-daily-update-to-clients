<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactMessageController extends Controller
{
    /**
     * Store a new contact message
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'message' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $contact = ContactMessage::create([
                'name' => $request->name,
                'email' => $request->email,
                'company' => $request->company,
                'phone' => $request->phone,
                'message' => $request->message,
                'status' => 'new'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your message has been sent successfully. We\'ll get back to you soon.',
                'data' => $contact
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all contact messages (for admin)
     */
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    /**
     * Get a specific contact message
     */
    public function show($id)
    {
        $message = ContactMessage::find($id);

        if (!$message) {
            return response()->json([
                'success' => false,
                'message' => 'Contact message not found'
            ], 404);
        }

        // Mark as read
        if ($message->status === 'new') {
            $message->update(['status' => 'read']);
        }

        return response()->json([
            'success' => true,
            'data' => $message
        ]);
    }

    /**
     * Update contact message status
     */
    public function updateStatus(Request $request, $id)
    {
        $message = ContactMessage::find($id);

        if (!$message) {
            return response()->json([
                'success' => false,
                'message' => 'Contact message not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:new,read,replied'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $message->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $message
        ]);
    }

    /**
     * Delete a contact message
     */
    public function destroy($id)
    {
        $message = ContactMessage::find($id);

        if (!$message) {
            return response()->json([
                'success' => false,
                'message' => 'Contact message not found'
            ], 404);
        }

        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact message deleted successfully'
        ]);
    }
}
