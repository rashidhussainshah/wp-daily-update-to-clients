<?php

namespace App\Services;

use Carbon\Carbon;
use GuzzleHttp\Client;

class ClockifyService
{
    protected $client;
    protected $apiKey;
    protected $workspaceId;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://api.clockify.me/api/v1/']);
        $this->apiKey = config('services.clockify.api_key', 'OGM3NDY0MGQtNTY1My00MzM3LTg1MTUtNjdmZGI0ZDliMmQ0');
        $this->workspaceId = config('services.clockify.workspace_id', '61e3f3051f00bd0c04ccad31');
    }

    public function createUser($email, $name)
    {
        $response = $this->client->post("workspaces/{$this->workspaceId}/users", [
            'headers' => [
                'X-Api-Key' => $this->apiKey,
            ],
            'json' => [
                'email' => $email,
                'name' => $name,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
    public function getAllUsers()
    {
        $response = $this->client->get("workspaces/{$this->workspaceId}/users", [
            'headers' => [
                'X-Api-Key' => $this->apiKey,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
    /**
     * Get time entries for a user from Clockify
     *
     * @param string $clockifyUserId
     * @param \Carbon\Carbon $startDate
     * @param \Carbon\Carbon $endDate
     * @return array
     */
    public function getUserTimeEntries($clockifyUserId, $startDate, $endDate)
    {
        $response = $this->client->get("workspaces/{$this->workspaceId}/user/{$clockifyUserId}/time-entries", [
            'headers' => [
                'X-Api-Key' => $this->apiKey,
            ],
            'query' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
    /**
     * Format start and end dates to UTC format.
     *
     * @param Carbon $date
     * @return array
     */
    public function formatDateRange($date)
    {
        $startDate = $date->startOfDay()->setTimezone('UTC')->format('Y-m-d') . 'T00:00:00Z';
        $endDate = $date->endOfDay()->setTimezone('UTC')->format('Y-m-d') . 'T23:59:59Z';

        return [
            'start' => $startDate,
            'end' => $endDate,
        ];
    }
}

