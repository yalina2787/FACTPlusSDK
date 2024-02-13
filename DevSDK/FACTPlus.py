# FACTPlus.py

import requests  # Import requests library for making HTTP requests

class FACTPlus:
    def __init__(self):
        self.auth_token = None
        self.api_url = None
        self.device_id = None
        self.refresh_token = None
        self.latitude = None
        self.longitude = None

    def set_auth_token(self, auth_token):
        self.auth_token = auth_token

    def set_api_url(self, api_url):
        self.api_url = api_url

    def set_device_id(self, device_id):
        self.device_id = device_id

    def set_refresh_token(self, refresh_token):
        self.refresh_token = refresh_token

    def set_geo_location(self, latitude, longitude):
        self.latitude = latitude
        self.longitude = longitude

    def register_device(self):
        if self.auth_token is not None and self.api_url is not None and self.device_id is not None and self.latitude is not None and self.longitude is not None:
            response = self.send_device_registration_request()
            if response.status_code == 200:
                return response.json().get("device_id")
            else:
                raise Exception(f"Failed to register device. Status code: {response.status_code}, Response: {response.text}")
        else:
            raise Exception("Authentication token, API URL, device ID, and geo-location must be set before attempting device registration.")

    def unregister_device(self):
        if self.auth_token is not None and self.api_url is not None and self.device_id is not None:
            response = self.send_device_unregistration_request()
            if response.status_code == 200:
                return response.json().get("device_id")
            else:
                raise Exception(f"Failed to unregister device. Status code: {response.status_code}, Response: {response.text}")
        else:
            raise Exception("Authentication token, API URL, and device ID must be set before attempting device unregistration.")

    def update_device_location(self, latitude, longitude):
        if self.auth_token is not None and self.api_url is not None and self.device_id is not None and latitude is not None and longitude is not None:
            self.set_geo_location(latitude, longitude)
            response = self.send_device_location_update_request()
            if response.status_code == 200:
                return response.json().get("device_id")
            else:
                raise Exception(f"Failed to update device location. Status code: {response.status_code}, Response: {response.text}")
        else:
            raise Exception("Authentication token, API URL, device ID, and new geo-location must be set before attempting device location update.")

    def authorize(self, user_id, device_function):
        if self.auth_token is not None and self.api_url is not None and self.device_id is not None and user_id is not None:
            response = self.send_authorization_request(user_id, device_function)
            if response.status_code == 200:
                return response.json().get("authorized")
            else:
                raise Exception(f"Authorization failed. Status code: {response.status_code}, Response: {response.text}")
        else:
            raise Exception("Authentication token, API URL, device ID, and user ID must be set before attempting authorization.")

    def send_authorization_request(self, user_id, device_function):
        params = {
            "device_id": self.device_id,
            "auth_token": self.auth_token,
            "user_id": user_id,
            "device_function": device_function
        }
        response = requests.get(self.api_url + "/authorize", params=params)
        return response

    def send_device_registration_request(self):
        data = {
            "device_id": self.device_id,
            "auth_token": self.auth_token,
            "latitude": self.latitude,
            "longitude": self.longitude
        }
        response = requests.post(self.api_url + "/register_device", json=data)
        return response

    def send_device_unregistration_request(self):
        data = {
            "device_id": self.device_id,
            "auth_token": self.auth_token
        }
        response = requests.delete(self.api_url + f"/unregister_device/{self.device_id}", json=data)
        return response

    def send_device_location_update_request(self):
        data = {
            "device_id": self.device_id,
            "auth_token": self.auth_token,
            "latitude": self.latitude,
            "longitude": self.longitude
        }
        response = requests.put(self.api_url + f"/update_device_location/{self.device_id}", json=data)
        return response

    def authenticate_and_get_refresh_token(self):
        if self.auth_token is not None and self.api_url is not None and self.device_id is not None and self.latitude is not None and self.longitude is not None:
            if self.authenticate():
                refresh_token = self.request_refresh_token()
                self.set_refresh_token(refresh_token)
                return refresh_token
            else:
                raise Exception("Authentication failed. Unable to get refresh token.")
        else:
            raise Exception("Authentication token, API URL, device ID, and geo-location must be set before attempting authentication.")

    def request_refresh_token(self):
        data = {
            "device_id": self.device_id,
            "auth_token": self.auth_token,
            "latitude": self.latitude,
            "longitude": self.longitude
        }
        response = requests.post(self.api_url + "/refresh_token", json=data)
        
        if response.status_code == 200:
            return response.json().get("refresh_token")
        else:
            raise Exception(f"Failed to get refresh token. Status code: {response.status_code}, Response: {response.text}")

    def authenticate(self):
        params = {
            "device_id": self.device_id,
            "auth_token": self.auth_token,
            "latitude": self.latitude,
            "longitude": self.longitude
        }
        response = requests.get(self.api_url + "/verify_token", params=params)
        return response.status_code == 200
