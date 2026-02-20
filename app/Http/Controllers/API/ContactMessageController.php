<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Spatie\SlackAlerts\Facades\SlackAlert;

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

            // Send email notification if enabled
            $emailNotificationEnabled = setting('contact.email_notification_enabled', false);
            if ($emailNotificationEnabled) {
                $emailRecipients = setting('contact.notification_email');
                if ($emailRecipients) {
                    try {
                        // Split comma-separated emails and trim whitespace
                        $emailList = array_filter(
                            array_map('trim', explode(',', $emailRecipients)),
                            function($email) {
                                return !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
                            }
                        );

                        if (!empty($emailList)) {
                            Mail::send('emails.contact-message', ['contact' => $contact], function ($message) use ($emailList, $contact) {
                                $message->to($emailList)
                                        ->subject('New Contact Message from ' . $contact->name);
                            });
                        }
                    } catch (\Exception $e) {
                        \Log::error('Failed to send contact message email: ' . $e->getMessage());
                    }
                }
            }

            // Send Slack notification if enabled
            $slackNotificationEnabled = setting('contact.slack_notification_enabled', false);
            if ($slackNotificationEnabled) {
                $slackWebhookUrl = setting('contact.slack_webhook_url');
                if ($slackWebhookUrl) {
                    try {
                        $slackMessage = "*New Contact Message Received*\n\n" .
                                      "*Name:* {$contact->name}\n" .
                                      "*Email:* {$contact->email}\n" .
                                      ($contact->company ? "*Company:* {$contact->company}\n" : "") .
                                      ($contact->phone ? "*Phone:* {$contact->phone}\n" : "") .
                                      "*Message:*\n{$contact->message}\n\n" .
                                      "*Submitted at:* " . $contact->created_at->format('Y-m-d H:i:s');

                        $blocks = [
                            [
                                'type' => 'section',
                                'text' => [
                                    'type' => 'mrkdwn',
                                    'text' => $slackMessage,
                                ],
                            ],
                        ];

                        $this->sendTxtToSlack($blocks, $slackWebhookUrl);
                    } catch (\Exception $e) {
                        \Log::error('Failed to send contact message to Slack: ' . $e->getMessage());
                    }
                }
            }

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

    /**
     * Send message to Slack
     */
    private function sendTxtToSlack($blocks, $slackWebhookUrl)
    {
        SlackAlert::to($slackWebhookUrl)->blocks($blocks);
    }
}
