<?php

class FACTPlus {
    private $authToken;
    private $apiUrl;
    private $refreshToken;

    public function setAuthToken($authToken) {
        $this->authToken = $authToken;
    }

    public function setApiUrl($apiUrl) {
        $this->apiUrl = $apiUrl;
    }

    public function setRefreshToken($refreshToken) {
        $this->refreshToken = $refreshToken;
    }

    public function registerDevice($deviceId, $latitude, $longitude) {
        if ($this->authToken && $this->apiUrl && $deviceId && $latitude && $longitude) {
            return $this->sendDeviceRegistrationRequest($deviceId, $latitude, $longitude);
        } else {
            throw new Exception("Authentication token, API URL, device ID, and geo-location must be set before attempting device registration.");
        }
    }

    public function unregisterDevice($deviceId) {
        if ($this->authToken && $this->apiUrl && $deviceId) {
            return $this->sendDeviceUnregistrationRequest($deviceId);
        } else {
            throw new Exception("Authentication token, API URL, and device ID must be set before attempting device unregistration.");
        }
    }

    public function updateDeviceLocation($deviceId, $latitude, $longitude) {
        if ($this->authToken && $this->apiUrl && $deviceId && $latitude && $longitude) {
            return $this->sendDeviceLocationUpdateRequest($deviceId, $latitude, $longitude);
        } else {
            throw new Exception("Authentication token, API URL, device ID, and new geo-location must be set before attempting device location update.");
        }
    }

    public function queryDevicesInRegion($lat0, $lng0, $lat1, $lng1) {
        if ($this->authToken && $this->apiUrl && $lat0 && $lng0 && $lat1 && $lng1) {
            return $this->sendDeviceQueryRequest($lat0, $lng0, $lat1, $lng1);
        } else {
            throw new Exception("Authentication token, API URL, and region boundaries must be set before attempting device query.");
        }
    }

    public function addUser($userId, $userData) {
        if ($this->authToken && $this->apiUrl && $userId && $userData) {
            return $this->sendAddUserRequest($userId, $userData);
        } else {
            throw new Exception("Authentication token, API URL, user ID, and user data must be set before attempting to add a user.");
        }
    }

    public function removeUser($userId) {
        if ($this->authToken && $this->apiUrl && $userId) {
            return $this->sendRemoveUserRequest($userId);
        } else {
            throw new Exception("Authentication token, API URL, and user ID must be set before attempting to remove a user.");
        }
    }

    public function updateUser($userId, $newUserData) {
        if ($this->authToken && $this->apiUrl && $userId && $newUserData) {
            return $this->sendUpdateUserRequest($userId, $newUserData);
        } else {
            throw new Exception("Authentication token, API URL, user ID, and new user data must be set before attempting to update a user.");
        }
    }

    public function queryUsers() {
        if ($this->authToken && $this->apiUrl) {
            return $this->sendQueryUsersRequest();
        } else {
            throw new Exception("Authentication token and API URL must be set before attempting to query users.");
        }
    }
    public function addPolicy($policyId, $policyData) {
        if ($this->authToken && $this->apiUrl && $policyId && $policyData) {
            return $this->sendAddPolicyRequest($policyId, $policyData);
        } else {
            throw new Exception("Authentication token, API URL, policy ID, and policy data must be set before attempting to add a policy.");
        }
    }

    public function removePolicy($policyId) {
        if ($this->authToken && $this->apiUrl && $policyId) {
            return $this->sendRemovePolicyRequest($policyId);
        } else {
            throw new Exception("Authentication token, API URL, and policy ID must be set before attempting to remove a policy.");
        }
    }

    public function updatePolicy($policyId, $newPolicyData) {
        if ($this->authToken && $this->apiUrl && $policyId && $newPolicyData) {
            return $this->sendUpdatePolicyRequest($policyId, $newPolicyData);
        } else {
            throw new Exception("Authentication token, API URL, policy ID, and new policy data must be set before attempting to update a policy.");
        }
    }

    public function queryPolicies() {
        if ($this->authToken && $this->apiUrl) {
            return $this->sendQueryPoliciesRequest();
        } else {
            throw new Exception("Authentication token and API URL must be set before attempting to query policies.");
        }
    }
    public function queryUsersByDeviceId($deviceId) {
        if ($this->authToken && $this->apiUrl && $deviceId) {
            return $this->sendQueryUsersByDeviceIdRequest($deviceId);
        } else {
            throw new Exception("Authentication token, API URL, and device ID must be set before attempting to query users by device ID.");
        }
    }

    public function queryDevicesByUserId($userId) {
        if ($this->authToken && $this->apiUrl && $userId) {
            return $this->sendQueryDevicesByUserIdRequest($userId);
        } else {
            throw new Exception("Authentication token, API URL, and user ID must be set before attempting to query devices by user ID.");
        }
    }

    private function sendAuthorizationRequest($userId, $deviceFunction) {
        $url = $this->apiUrl . '/authorize';
        $params = [
            'auth_token' => $this->authToken,
            'user_id' => $userId,
            'device_function' => $deviceFunction
        ];
        return $this->makeGetRequest($url, $params);
    }

    private function sendDeviceRegistrationRequest($deviceId, $latitude, $longitude) {
        $url = $this->apiUrl . '/register_device';
        $data = [
            'device_id' => $deviceId,
            'auth_token' => $this->authToken,
            'latitude' => $latitude,
            'longitude' => $longitude
        ];
        return $this->makePostRequest($url, $data);
    }

    private function sendDeviceUnregistrationRequest($deviceId) {
        $url = $this->apiUrl . '/unregister_device/' . $deviceId;
        $data = [
            'device_id' => $deviceId,
            'auth_token' => $this->authToken
        ];
        return $this->makeDeleteRequest($url, $data);
    }

    private function sendDeviceLocationUpdateRequest($deviceId, $latitude, $longitude) {
        $url = $this->apiUrl . '/update_device_location/' . $deviceId;
        $data = [
            'auth_token' => $this->authToken,
            'latitude' => $latitude,
            'longitude' => $longitude
        ];
        return $this->makePutRequest($url, $data);
    }

    private function sendDeviceQueryRequest($lat0, $lng0, $lat1, $lng1) {
        $url = $this->apiUrl . '/query_devices_in_region';
        $params = [
            'auth_token' => $this->authToken,
            'lat0' => $lat0,
            'lng0' => $lng0,
            'lat1' => $lat1,
            'lng1' => $lng1
        ];
        return $this->makeGetRequest($url, $params);
    }

    private function sendAddUserRequest($userId, $userData) {
        $url = $this->apiUrl . '/add_user';
        $data = [
            'auth_token' => $this->authToken,
            'user_id' => $userId,
            'user_data' => $userData
        ];
        return $this->makePostRequest($url, $data);
    }

    private function sendRemoveUserRequest($userId) {
        $url = $this->apiUrl . '/remove_user/' . $userId;
        $data = [
            'auth_token' => $this->authToken
        ];
        return $this->makeDeleteRequest($url, $data);
    }

    private function sendUpdateUserRequest($userId, $newUserData) {
        $url = $this->apiUrl . '/update_user/' . $userId;
        $data = [
            'auth_token' => $this->authToken,
            'new_user_data' => $newUserData
        ];
        return $this->makePutRequest($url, $data);
    }

    private function sendQueryUsersRequest() {
        $url = $this->apiUrl . '/query_users';
        $params = [
            'auth_token' => $this->authToken
        ];
        return $this->makeGetRequest($url, $params);
    }

    private function sendAddPolicyRequest($policyId, $policyData) {
        $url = $this->apiUrl . '/add_policy';
        $data = [
            'auth_token' => $this->authToken,
            'policy_id' => $policyId,
            'policy_data' => $policyData
        ];
        return $this->makePostRequest($url, $data);
    }

    private function sendRemovePolicyRequest($policyId) {
        $url = $this->apiUrl . '/remove_policy/' . $policyId;
        $data = [
            'auth_token' => $this->authToken
        ];
        return $this->makeDeleteRequest($url, $data);
    }

    private function sendUpdatePolicyRequest($policyId, $newPolicyData) {
        $url = $this->apiUrl . '/update_policy/' . $policyId;
        $data = [
            'auth_token' => $this->authToken,
            'new_policy_data' => $newPolicyData
        ];
        return $this->makePutRequest($url, $data);
    }

    private function sendQueryPoliciesRequest() {
        $url = $this->apiUrl . '/query_policies';
        $params = [
            'auth_token' => $this->authToken
        ];
        return $this->makeGetRequest($url, $params);
    }
    
    private function sendQueryUsersByDeviceIdRequest($deviceId) {
        $url = $this->apiUrl . '/query_users_by_device_id';
        $params = [
            'auth_token' => $this->authToken,
            'device_id' => $deviceId
        ];
        return $this->makeGetRequest($url, $params);
    }

    private function sendQueryDevicesByUserIdRequest($userId) {
        $url = $this->apiUrl . '/query_devices_by_user_id';
        $params = [
            'auth_token' => $this->authToken,
            'user_id' => $userId
        ];
        return $this->makeGetRequest($url, $params);
    }

    private function makeGetRequest($url, $params) {
        $url .= '?' . http_build_query($params);
        return $this->executeRequest($url, 'GET');
    }

    private function makePostRequest($url, $data) {
        return $this->executeRequest($url, 'POST', $data);
    }

    private function makePutRequest($url, $data) {
        return $this->executeRequest($url, 'PUT', $data);
    }

    private function makeDeleteRequest($url, $data) {
        $url .= '?' . http_build_query($data);
        return $this->executeRequest($url, 'DELETE');
    }

    private function executeRequest($url, $method, $data = []) {
        $curl = curl_init();

        // Set common options
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        // Set method-specific options
        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        } elseif ($method == 'PUT') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        } elseif ($method == 'DELETE') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        $response = curl_exec($curl);

        // Check for errors
        if (curl_errno($curl)) {
            throw new Exception("cURL error: " . curl_error($curl));
        }

        // Close cURL session
        curl_close($curl);

        return $response;
    }
}

?>
