<?php

/**
 * SweetProcessAPI class provides a wrapper for interacting with the SweetProcess API.
 */
class SweetProcessAPI {
    private $baseUrl = 'https://www.sweetprocess.com/api/v1/';
    private $token;

    /**
     * Initializes the SweetProcessAPI instance with the provided API token.
     *
     * @param string $token The API token for authentication.
     */
    public function __construct($token) {
        $this->token = $token;
    }

    /**
     * Sends an API request to the specified endpoint with the given HTTP method and data.
     *
     * @param string $method   The HTTP method (GET, POST, PATCH, DELETE).
     * @param string $endpoint The API endpoint to send the request to.
     * @param array  $data     The data to send with the request (optional).
     *
     * @return array|null The response data as an associative array, or null on error.
     */
    private function sendRequest($method, $endpoint, $data = null) {
        $url = $this->baseUrl . $endpoint;
        $headers = [
            'Authorization: Token ' . $this->token,
            'Content-Type: application/json'
        ];

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers
        ];

        if ($method === 'POST' || $method === 'PATCH') {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = json_encode($data);
        } elseif ($method === 'DELETE') {
            $options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
        }

        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            $this->logError("API request failed: {$error}");
            return null;
        }

        $data = json_decode($response, true);

        if ($httpCode >= 400) {
            $errorMessage = $data['detail'] ?? 'Unknown API error';
            $this->logError("API request failed with status code {$httpCode}: {$errorMessage}");
            return null;
        }

        return $data;
    }

    /**
     * Retrieves a paginated list of procedures.
     *
     * @param array $filters Optional filters to apply to the request.
     *
     * @return array|null The list of procedures, or null on error.
     */
    public function getProcedures($filters = []) {
        $endpoint = 'procedures/';
        if (!empty($filters)) {
            $endpoint .= '?' . http_build_query($filters);
        }
        return $this->sendRequest('GET', $endpoint);
    }

    /**
     * Retrieves a paginated list of task instances.
     *
     * @param array $filters Optional filters to apply to the request.
     *
     * @return array|null The list of task instances, or null on error.
     */
    public function getTaskInstances($filters = []) {
        $endpoint = 'taskinstances/';
        if (!empty($filters)) {
            $endpoint .= '?' . http_build_query($filters);
        }
        return $this->sendRequest('GET', $endpoint);
    }

    /**
     * Retrieves a list of users.
     *
     * @param array $filters Optional filters to apply to the request.
     *
     * @return array|null The list of users, or null on error.
     */
    public function getUsers($filters = []) {
        $endpoint = 'users/';
        if (!empty($filters)) {
            $endpoint .= '?' . http_build_query($filters);
        }
        return $this->sendRequest('GET', $endpoint);
    }

    /**
     * Invites a new user to the account.
     *
     * @param array $data The user data for the invitation.
     *
     * @return array|null The created user data, or null on error.
     */
    public function inviteUser($data) {
        return $this->sendRequest('POST', 'users/', $data);
    }

    /**
     * Updates a user's information.
     *
     * @param string $userId The ID of the user to update.
     * @param array  $data   The updated user data.
     *
     * @return array|null The updated user data, or null on error.
     */
    public function updateUser($userId, $data) {
        return $this->sendRequest('PATCH', "users/{$userId}/", $data);
    }

    /**
     * Removes a user from the account.
     *
     * @param string $userId The ID of the user to remove.
     *
     * @return bool Whether the user was successfully removed.
     */
    public function deleteUser($userId) {
        $response = $this->sendRequest('DELETE', "users/{$userId}/");
        return $response !== null;
    }

    /**
     * Invites a user to a team.
     *
     * @param array $data The invitation data.
     *
     * @return array|null The created invitation data, or null on error.
     */
    public function inviteToTeam($data) {
        return $this->sendRequest('POST', 'invitations/', $data);
    }

    /**
     * Removes a user from a team.
     *
     * @param string $teamUserId The ID of the team user to remove.
     *
     * @return bool Whether the user was successfully removed from the team.
     */
    public function removeFromTeam($teamUserId) {
        $response = $this->sendRequest('DELETE', "teamusers/{$teamUserId}/");
        return $response !== null;
    }

    /**
     * Logs an error message to the browser's console.
     *
     * @param string $message The error message to log.
     */
    private function logError($message) {
        echo "<script>console.error(" . json_encode($message) . ");</script>";
    }
}
