<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Calls Gemini's free-tier API to pre-review a student's project submission
 * against its stage rubric (A3). API key lives in settings, never .env or
 * hardcoded - see academy.gemini_api_key.
 */
class GeminiReviewService
{
    public function review(string $stageTitle, array $skills, string $submissionLink, ?string $notes): array
    {
        $apiKey = setting('academy.gemini_api_key');

        if (!$apiKey) {
            return [
                'score' => null,
                'verdict' => null,
                'feedback' => 'AI review unavailable - no Gemini API key configured yet (Settings -> Academy -> Gemini API Key). A human reviewer can still approve/send back manually.',
            ];
        }

        $model = setting('academy.gemini_model', 'gemini-2.0-flash');
        $skillList = implode(', ', $skills);

        $prompt = "You are reviewing a student's project submission for a software development training academy.\n"
            . "Stage: {$stageTitle}\n"
            . "Skills this stage should demonstrate: {$skillList}\n"
            . "Submission link: {$submissionLink}\n"
            . ($notes ? "Student's notes: {$notes}\n" : '')
            . "\nBased on the submission link and notes alone (you cannot browse the link), give a short, honest assessment. "
            . "Reply ONLY with valid JSON in this exact shape, no other text: "
            . '{"score": <0-10 number>, "verdict": "approve"|"needs_work"|"reject", "feedback": "<2-3 sentence feedback for the reviewer>"}';

        try {
            $response = Http::timeout(20)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}",
                [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]],
                    ],
                ]
            );

            if (!$response->successful()) {
                Log::error('GeminiReviewService: request failed', ['status' => $response->status(), 'body' => $response->body()]);
                return $this->fallback('AI review request failed - a human reviewer should assess this submission directly.');
            }

            $text = data_get($response->json(), 'candidates.0.content.parts.0.text', '');
            $json = $this->extractJson($text);

            if (!$json) {
                Log::error('GeminiReviewService: could not parse response', ['text' => $text]);
                return $this->fallback('AI review returned an unexpected format - a human reviewer should assess this submission directly.');
            }

            return [
                'score' => isset($json['score']) ? (float) $json['score'] : null,
                'verdict' => in_array($json['verdict'] ?? null, ['approve', 'needs_work', 'reject'], true) ? $json['verdict'] : null,
                'feedback' => $json['feedback'] ?? '',
            ];
        } catch (\Throwable $e) {
            Log::error('GeminiReviewService: exception', ['message' => $e->getMessage()]);
            return $this->fallback('AI review failed unexpectedly - a human reviewer should assess this submission directly.');
        }
    }

    protected function fallback(string $message): array
    {
        return ['score' => null, 'verdict' => null, 'feedback' => $message];
    }

    protected function extractJson(string $text): ?array
    {
        // Gemini sometimes wraps JSON in ```json ... ``` fences.
        $text = trim(preg_replace('/^```json|```$/m', '', $text));
        $decoded = json_decode($text, true);

        return is_array($decoded) ? $decoded : null;
    }
}
