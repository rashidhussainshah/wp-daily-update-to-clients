<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Spatie\SlackAlerts\Jobs\SendToSlackChannelJob;

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
            'subject' => 'nullable|string|max:255',
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
            $ip = $request->ip();

            // Geo lookup is best-effort only: it must never stop the lead from
            // being saved/notified, so any failure (timeout, bad response,
            // rate limit) is swallowed here and just leaves country/region/city empty.
            $geo = $this->resolveGeo($ip);

            // Any field the request sends beyond the known set (e.g. scriptandtools.com's
            // form has fields webpenter.com's doesn't) is kept as-is rather than dropped,
            // so new sites/fields don't need a schema change to show up.
            $extraFields = $this->resolveExtraFields($request);

            $contact = ContactMessage::create([
                'name' => $request->name,
                'email' => $request->email,
                'company' => $request->company,
                'subject' => $request->subject,
                'phone' => $request->phone,
                'message' => $request->message,
                'status' => 'new',
                'source_domain' => $this->resolveSourceDomain($request),
                'referer_url' => $request->header('referer'),
                'ip_address' => $ip,
                'user_agent' => $request->userAgent(),
                'country' => $geo['country'] ?? null,
                'region' => $geo['region'] ?? null,
                'city' => $geo['city'] ?? null,
                'extra_fields' => !empty($extraFields) ? $extraFields : null,
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
                        $location = implode(', ', array_filter([$contact->city, $contact->region, $contact->country]));

                        $slackMessage = "*New Contact Message Received*\n\n" .
                                      "*Name:* {$contact->name}\n" .
                                      "*Email:* {$contact->email}\n" .
                                      ($contact->company ? "*Company:* {$contact->company}\n" : "") .
                                      ($contact->subject ? "*Subject:* {$contact->subject}\n" : "") .
                                      ($contact->phone ? "*Phone:* {$contact->phone}\n" : "") .
                                      $this->formatExtraFieldsForSlack($contact->extra_fields) .
                                      "*Message:*\n{$contact->message}\n\n" .
                                      ($contact->source_domain ? "*From site:* {$contact->source_domain}\n" : "") .
                                      ($location ? "*Location:* {$location}\n" : "") .
                                      ($contact->ip_address ? "*IP:* {$contact->ip_address}\n" : "") .
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
        // dispatchSync (not the SlackAlert facade's ->blocks(), which always
        // queues) so the lead notification lands immediately instead of
        // waiting for the next queue:work sweep.
        SendToSlackChannelJob::dispatchSync($slackWebhookUrl, null, $blocks);
    }

    /**
     * Best-effort IP -> country/region/city lookup. Any failure (timeout,
     * bad response, rate limit) is swallowed so it can never block saving
     * the lead or notifying Slack about it.
     */
    private function resolveGeo(?string $ip): array
    {
        if (!$ip || !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return [];
        }

        try {
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}", [
                'fields' => 'status,country,regionName,city',
            ]);

            if ($response->successful() && $response->json('status') === 'success') {
                return [
                    'country' => $response->json('country'),
                    'region' => $response->json('regionName'),
                    'city' => $response->json('city'),
                ];
            }
        } catch (\Throwable $e) {
            \Log::warning('Contact lead geo lookup failed: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Which site the submission came from, taken from the Origin header
     * (present on cross-origin fetch/XHR) and falling back to the Referer host.
     */
    private function resolveSourceDomain(Request $request): ?string
    {
        $origin = $request->header('origin') ?: $request->header('referer');

        if (!$origin) {
            return null;
        }

        return parse_url($origin, PHP_URL_HOST) ?: null;
    }

    /**
     * Request fields outside the known contact-form set, so forms with extra
     * fields (e.g. scriptandtools.com) don't need this API/schema changed for
     * every field a given site happens to send.
     */
    private function resolveExtraFields(Request $request): array
    {
        $knownFields = ['name', 'email', 'company', 'subject', 'phone', 'message', '_token', '_method'];

        return collect($request->all())
            ->except($knownFields)
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->toArray();
    }

    /**
     * Renders extra_fields as extra Slack lines, e.g. "budget" -> "*Budget:* ...".
     */
    private function formatExtraFieldsForSlack(?array $extraFields): string
    {
        if (empty($extraFields)) {
            return '';
        }

        $lines = '';
        foreach ($extraFields as $key => $value) {
            $label = ucwords(str_replace(['_', '-'], ' ', $key));
            $displayValue = is_array($value) ? json_encode($value) : $value;
            $lines .= "*{$label}:* {$displayValue}\n";
        }

        return $lines;
    }
}
