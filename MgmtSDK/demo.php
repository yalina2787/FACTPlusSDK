<?php

// Include the FACTPlus class
require_once 'FACTPlus.php';

// Create an instance of FACTPlus
$factPlus = new FACTPlus();

// Set authentication token and API URL
$factPlus->setAuthToken('your_auth_token');
$factPlus->setApiUrl('https://your-api-url.com');

// Device-related example
try {
    // Register a new device
    $registrationResponse = $factPlus->registerDevice('device123', 40.7128, -74.0060);
    echo "Device Registration Response: " . $registrationResponse . PHP_EOL;

    // Update device location
    $updateLocationResponse = $factPlus->updateDeviceLocation('device123', 34.0522, -118.2437);
    echo "Device Location Update Response: " . $updateLocationResponse . PHP_EOL;

    // Query devices in a region
    $queryDevicesResponse = $factPlus->queryDevicesInRegion(30.0, -90.0, 40.0, -80.0);
    echo "Query Devices in Region Response: " . $queryDevicesResponse . PHP_EOL;

    // Unregister the device
    $unregistrationResponse = $factPlus->unregisterDevice('device123');
    echo "Device Unregistration Response: " . $unregistrationResponse . PHP_EOL;
} catch (Exception $e) {
    echo "Device-related Exception: " . $e->getMessage() . PHP_EOL;
}

// User-related example
try {
    // Add a new user
    $addUserResponse = $factPlus->addUser('user456', ['name' => 'John Doe', 'email' => 'john.doe@example.com']);
    echo "Add User Response: " . $addUserResponse . PHP_EOL;

    // Update user information
    $updateUserResponse = $factPlus->updateUser('user456', ['name' => 'Jane Doe', 'email' => 'jane.doe@example.com']);
    echo "Update User Response: " . $updateUserResponse . PHP_EOL;

    // Query all users
    $queryUsersResponse = $factPlus->queryUsers();
    echo "Query Users Response: " . $queryUsersResponse . PHP_EOL;

    // Remove the user
    $removeUserResponse = $factPlus->removeUser('user456');
    echo "Remove User Response: " . $removeUserResponse . PHP_EOL;
} catch (Exception $e) {
    echo "User-related Exception: " . $e->getMessage() . PHP_EOL;
}

// Policy-related example
try {
    // Add a new policy
    $addPolicyResponse = $factPlus->addPolicy('policy789', ['permission' => 'read']);
    echo "Add Policy Response: " . $addPolicyResponse . PHP_EOL;

    // Update policy information
    $updatePolicyResponse = $factPlus->updatePolicy('policy789', ['permission' => 'write']);
    echo "Update Policy Response: " . $updatePolicyResponse . PHP_EOL;

    // Query all policies
    $queryPoliciesResponse = $factPlus->queryPolicies();
    echo "Query Policies Response: " . $queryPoliciesResponse . PHP_EOL;

    // Remove the policy
    $removePolicyResponse = $factPlus->removePolicy('policy789');
    echo "Remove Policy Response: " . $removePolicyResponse . PHP_EOL;
} catch (Exception $e) {
    echo "Policy-related Exception: " . $e->getMessage() . PHP_EOL;
}

// Query example based on device and user
try {
    // Query users based on device ID
    $queryUsersByDeviceIdResponse = $factPlus->queryUsersByDeviceId('device123');
    echo "Query Users by Device ID Response: " . $queryUsersByDeviceIdResponse . PHP_EOL;

    // Query devices based on user ID
    $queryDevicesByUserIdResponse = $factPlus->queryDevicesByUserId('user456');
    echo "Query Devices by User ID Response: " . $queryDevicesByUserIdResponse . PHP_EOL;
} catch (Exception $e) {
    echo "Query Example Exception: " . $e->getMessage() . PHP_EOL;
}
?>
