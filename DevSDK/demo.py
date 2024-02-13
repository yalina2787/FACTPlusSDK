# demo.py
from FACTPlus import FACTPlus

if __name__ == "__main__":
    fact_plus_instance = FACTPlus()

    # Set authentication token
    fact_plus_instance.set_auth_token("valid_token")

    # Set API URL
    fact_plus_instance.set_api_url("https://example.com/api")

    # Set device ID
    fact_plus_instance.set_device_id("device123")

    # Set geo-location
    fact_plus_instance.set_geo_location(37.7749, -122.4194)

    # Example 1: Register a Device
    registered_device_id = fact_plus_instance.register_device()
    print(f"Device Registered with ID: {registered_device_id}")
    print("-" * 50)

    # Example 2: Authenticate and Get Refresh Token
    authenticated = fact_plus_instance.authenticate()
    if authenticated:
        refresh_token = fact_plus_instance.authenticate_and_get_refresh_token()
        print(f"Authenticated successfully. Refresh Token: {refresh_token}")
    else:
        print("Authentication failed.")
    print("-" * 50)

    # Example 3: Unregister a Device
    unregister_device_id = fact_plus_instance.unregister_device()
    print(f"Device Unregistered with ID: {unregister_device_id}")
    print("-" * 50)

    # Example 4: Authorize User for a Device Function
    user_id = "user123"
    device_function = "specific_function"
    authorized = fact_plus_instance.authorize(user_id, device_function)
    if authorized:
        print(f"User {user_id} is authorized to access {device_function}.")
    else:
        print(f"User {user_id} is not authorized to access {device_function}.")
    print("-" * 50)

    # Example 5: Update Device Location
    updated_device_id = fact_plus_instance.update_device_location(38.9072, -77.0370)
    print(f"Device Location Updated. Updated Device ID: {updated_device_id}")
    print("-" * 50)

    print("Demo completed.")
