# FACT+ Python Library and Demo Server

The FACT+ Python Library provides a simple interface to interact with a server for device management and user authorization. Additionally, a demo server is included to simulate responses for testing purposes.

## `FACTPlus` Class Functions

### `set_auth_token(auth_token: str) -> None`

Set the authentication token for the device.

### `set_api_url(api_url: str) -> None`

Set the API URL for communication with the server.

### `set_device_id(device_id: str) -> None`

Set the device ID for device-specific operations.

### `set_refresh_token(refresh_token: str) -> None`

Set the refresh token obtained during authentication.

### `set_geo_location(latitude: float, longitude: float) -> None`

Set the geographical location (latitude and longitude) of the device.

### `register_device() -> str`

Register the device with the server. Returns the registered device ID.

### `unregister_device() -> str`

Unregister the device from the server. Returns the unregistered device ID.

### `update_device_location(latitude: float, longitude: float) -> str`

Update the device location on the server. Returns the updated device ID.

### `authorize(user_id: str, device_function: str) -> bool`

Check with the server if a user is authorized to access a specific device function. Returns `True` if authorized, otherwise `False`.

### `authenticate_and_get_refresh_token() -> str`

Authenticate the device with the server and obtain a refresh token. Returns the obtained refresh token.

### `request_refresh_token() -> str`

Request a refresh token from the server. Returns the obtained refresh token.

### `authenticate() -> bool`

Verify the validity of the authentication token, device ID, and geo-location with the server. Returns `True` if authentication is successful, otherwise `False`.

## Demo Server

The included demo server is implemented using Flask and provides simulated responses for device registration, unregistration, user authorization, and device location update.

To run the demo server:

```bash
pip install flask
python server.py
