<?php

namespace App\Services;

use App\Models\AcademyCertificate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * AI-suggested social media post text for a certificate - Marketing still
 * does the actual posting (no LinkedIn/Instagram/X/Facebook API
 * credentials are configured), this just gives them a title, description,
 * and hashtags to copy instead of writing from scratch every time. Same
 * Gemini key/model as GeminiReviewService (academy.gemini_api_key /
 * academy.gemini_model), same graceful fallback when it's not configured.
 */
class AcademySocialPostService
{
    public function suggest(AcademyCertificate $certificate): array
    {
        $apiKey = setting('academy.gemini_api_key');

        if (!$apiKey) {
            return $this->fallback($certificate);
        }

        $model = setting('academy.gemini_model', 'gemini-flash-latest');
        $standardHashtags = setting(
            'academy.social_hashtags',
            '#WebPenter #WebPenterAcademy #WebDevelopment #MobileAppDevelopment #SoftwareDevelopment #BookingAndRental #RealEstateSoftware'
        );

        $achievement = $certificate->type === 'track'
            ? "completed the full \"{$certificate->title}\" track"
            : "earned the \"{$certificate->title}\" course certificate";

        $prompt = "You are writing a short social media announcement for a software company (WebPenter) celebrating a"
            . " student's achievement at their training academy (WebPenter IT Academy).\n"
            . "Student name: {$certificate->recipient_name}\n"
            . "Achievement: {$achievement}\n"
            . "Write ONE title (under 12 words, celebratory but not cringey), ONE description (2-3 sentences, works"
            . " reasonably well across LinkedIn, Instagram, Facebook, and X - the marketing person will trim it for X's"
            . " length if needed), and 5-8 relevant hashtags specific to this achievement (not the standard company"
            . " ones, those are added separately).\n"
            . 'Reply ONLY with valid JSON in this exact shape, no other text: {"title": "...", "description": "...", "hashtags": ["...", "..."]}';

        try {
            $response = $this->callGemini($model, $apiKey, $prompt);

            // Google's Gemini models occasionally return 503 "high demand" -
            // it explicitly says spikes are usually momentary, so one quick
            // retry avoids bouncing the marketing resource to the fallback
            // template for a purely transient blip.
            if ($response->status() === 503) {
                $response = $this->callGemini($model, $apiKey, $prompt);
            }

            if (!$response->successful()) {
                Log::error('AcademySocialPostService: request failed', ['status' => $response->status(), 'body' => $response->body()]);
                $reason = $response->status() === 503
                    ? 'busy'
                    : 'error';
                return $this->fallback($certificate, $standardHashtags, $reason);
            }

            $text = data_get($response->json(), 'candidates.0.content.parts.0.text', '');
            $json = $this->extractJson($text);

            if (!$json) {
                Log::error('AcademySocialPostService: could not parse response', ['text' => $text]);
                return $this->fallback($certificate, $standardHashtags, 'error');
            }

            return [
                'title' => $json['title'] ?? $this->fallback($certificate)['title'],
                'description' => $json['description'] ?? $this->fallback($certificate)['description'],
                'hashtags' => trim(implode(' ', $json['hashtags'] ?? []) . ' ' . $standardHashtags),
                'ai_generated' => true,
            ];
        } catch (\Throwable $e) {
            Log::error('AcademySocialPostService: exception', ['message' => $e->getMessage()]);
            return $this->fallback($certificate, $standardHashtags, 'error');
        }
    }

    protected function callGemini(string $model, string $apiKey, string $prompt)
    {
        return Http::timeout(20)->post(
            "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}",
            ['contents' => [['parts' => [['text' => $prompt]]]]]
        );
    }

    /**
     * $reason distinguishes WHY the AI text didn't come through, so the UI
     * doesn't tell a marketing resource "no API key configured" when the key
     * is fine and Gemini itself was just temporarily overloaded (503).
     */
    protected function fallback(AcademyCertificate $certificate, ?string $standardHashtags = null, string $reason = 'not_configured'): array
    {
        $standardHashtags ??= setting(
            'academy.social_hashtags',
            '#WebPenter #WebPenterAcademy #WebDevelopment #MobileAppDevelopment #SoftwareDevelopment #BookingAndRental #RealEstateSoftware'
        );

        return [
            'title' => "🎓 {$certificate->recipient_name} just earned their {$certificate->title} certificate!",
            'description' => "We're proud to celebrate {$certificate->recipient_name}, who just completed {$certificate->title} at WebPenter IT Academy. "
                . 'Real skills, real projects, real results.',
            'hashtags' => $standardHashtags,
            'ai_generated' => false,
            'fallback_reason' => $reason,
        ];
    }

    protected function extractJson(string $text): ?array
    {
        $text = trim(preg_replace('/^```json|```$/m', '', $text));
        $decoded = json_decode($text, true);

        return is_array($decoded) ? $decoded : null;
    }
}
