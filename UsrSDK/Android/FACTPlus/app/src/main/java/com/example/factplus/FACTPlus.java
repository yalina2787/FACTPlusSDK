package com.example.factplus;

import android.annotation.SuppressLint;
import android.os.AsyncTask;
import android.util.Log;

import org.json.JSONObject;

import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.nio.charset.StandardCharsets;

public class FACTPlus {
    private String authToken;
    private String refreshToken;
    private String apiUrl;
    private String userId;

    public void setAuthToken(String token) {
        authToken = token;
    }

    public void setRefreshToken(String token) {
        refreshToken = token;
    }

    public void setApiUrl(String url) {
        apiUrl = url;
    }

    @SuppressLint("StaticFieldLeak")
    public void refreshAuthToken(final RefreshCallback callback) {
        if (apiUrl == null || refreshToken == null) {
            Log.e("FACTPlus", "Error: API URL or Refresh Token not set.");
            callback.onRefreshComplete(false, new Exception("API URL or Refresh Token not set."));
            return;
        }

        final String refreshEndpoint = apiUrl + "/refreshToken";

        new AsyncTask<Void, Void, Boolean>() {
            @Override
            protected Boolean doInBackground(Void... params) {
                try {
                    URL url = new URL(refreshEndpoint);
                    HttpURLConnection connection = (HttpURLConnection) url.openConnection();
                    connection.setRequestMethod("POST");
                    connection.setRequestProperty("Content-Type", "application/json");
                    connection.setDoOutput(true);

                    // Create request body
                    JSONObject requestBody = new JSONObject();
                    requestBody.put("refreshToken", refreshToken);

                    // Write request body to OutputStream
                    try (OutputStream os = connection.getOutputStream()) {
                        byte[] input = requestBody.toString().getBytes(StandardCharsets.UTF_8);
                        os.write(input, 0, input.length);
                    }

                    int responseCode = connection.getResponseCode();

                    if (responseCode == HttpURLConnection.HTTP_OK) {
                        // Parse the response to retrieve the new authentication token
                        JSONObject jsonResponse = new JSONObject(readResponse(connection));
                        String newAuthToken = jsonResponse.getString("newAuthToken");
                        setAuthToken(newAuthToken);
                        return true;
                    } else {
                        Log.e("FACTPlus", "Error: Token refresh request failed. Response Code: " + responseCode);
                        return false;
                    }
                } catch (Exception e) {
                    Log.e("FACTPlus", "Error: " + e.getMessage());
                    return false;
                }
            }

            @Override
            protected void onPostExecute(Boolean success) {
                if (success) {
                    callback.onRefreshComplete(true, null);
                } else {
                    callback.onRefreshComplete(false, new Exception("Token refresh failed."));
                }
            }
        }.execute();
    }
    @SuppressLint("StaticFieldLeak")
    public void registerUser(String username, String password, final RegisterCallback callback) {
        if (apiUrl == null) {
            Log.e("FACTPlus", "Error: API URL not set.");
            callback.onRegisterComplete(false, new Exception("API URL not set."));
            return;
        }

        final String registerEndpoint = apiUrl + "/register";

        new AsyncTask<Void, Void, Boolean>() {
            @Override
            protected Boolean doInBackground(Void... params) {
                try {
                    URL url = new URL(registerEndpoint);
                    HttpURLConnection connection = (HttpURLConnection) url.openConnection();
                    connection.setRequestMethod("POST");
                    connection.setRequestProperty("Content-Type", "application/json");
                    connection.setDoOutput(true);

                    // Create request body
                    JSONObject requestBody = new JSONObject();
                    requestBody.put("username", username);
                    requestBody.put("password", password);

                    // Write request body to OutputStream
                    try (OutputStream os = connection.getOutputStream()) {
                        byte[] input = requestBody.toString().getBytes(StandardCharsets.UTF_8);
                        os.write(input, 0, input.length);
                    }

                    int responseCode = connection.getResponseCode();

                    if (responseCode == HttpURLConnection.HTTP_OK) {
                        // Parse the response to determine if registration was successful
                        JSONObject jsonResponse = new JSONObject(readResponse(connection));
                        boolean registrationSuccess = jsonResponse.getBoolean("success");

                        if (registrationSuccess) {
                            callback.onRegisterComplete(true, null);
                        } else {
                            Log.e("FACTPlus", "Error: Registration failed.");
                            callback.onRegisterComplete(false, new Exception("Registration failed."));
                        }
                    } else {
                        Log.e("FACTPlus", "Error: Registration request failed. Response Code: " + responseCode);
                        callback.onRegisterComplete(false, new Exception("Registration request failed."));
                    }
                } catch (Exception e) {
                    Log.e("FACTPlus", "Error: " + e.getMessage());
                    callback.onRegisterComplete(false, new Exception("Registration failed."));
                }
                return null;
            }
        }.execute();
    }


    private String readResponse(HttpURLConnection connection) throws IOException {
        StringBuilder response = new StringBuilder();
        try (BufferedReader reader = new BufferedReader(new InputStreamReader(connection.getInputStream()))) {
            String line;
            while ((line = reader.readLine()) != null) {
                response.append(line);
            }
        }
        return response.toString();
    }

    @SuppressLint("StaticFieldLeak")
    public void loginUser(String username, String password, final LoginCallback callback) {
        if (apiUrl == null) {
            Log.e("FACTPlus", "Error: API URL not set.");
            return;
        }

        final String loginEndpoint = apiUrl + "/login";

        new AsyncTask<Void, Void, Boolean>() {
            @Override
            protected Boolean doInBackground(Void... params) {
                try {
                    URL url = new URL(loginEndpoint);
                    HttpURLConnection connection = (HttpURLConnection) url.openConnection();
                    connection.setRequestMethod("POST");
                    connection.setRequestProperty("Content-Type", "application/json");
                    connection.setDoOutput(true);

                    // Create request body
                    JSONObject requestBody = new JSONObject();
                    requestBody.put("username", username);
                    requestBody.put("password", password);

                    // Write request body to OutputStream
                    try (OutputStream os = connection.getOutputStream()) {
                        byte[] input = requestBody.toString().getBytes(StandardCharsets.UTF_8);
                        os.write(input, 0, input.length);
                    }

                    int responseCode = connection.getResponseCode();

                    if (responseCode == HttpURLConnection.HTTP_OK) {
                        JSONObject jsonResponse = new JSONObject(readResponse(connection));
                        String newAuthToken = jsonResponse.getString("newAuthToken");
                        userId = jsonResponse.getString("userId");

                        setAuthToken(newAuthToken);
                        return true;
                    } else {
                        Log.e("FACTPlus", "Error: Login request failed. Response Code: " + responseCode);
                        return false;
                    }
                } catch (Exception e) {
                    Log.e("FACTPlus", "Error: " + e.getMessage());
                    return false;
                }
            }

            @Override
            protected void onPostExecute(Boolean success) {
                if (success) {
                    callback.onLoginComplete(true, null);
                } else {
                    callback.onLoginComplete(false, new Exception("Login failed."));
                }
            }
        }.execute();
    }

    public String getUserId() {
        return userId;
    }

    private void setUserId(String userId) {
        this.userId = userId;
    }
    @SuppressLint("StaticFieldLeak")
    public void logoutUser(final LogoutCallback callback) {
        if (apiUrl == null) {
            Log.e("FACTPlus", "Error: API URL not set.");
            callback.onLogoutComplete(false, new Exception("API URL not set."));
            return;
        }

        final String logoutEndpoint = apiUrl + "/logout";

        new AsyncTask<Void, Void, Boolean>() {
            @Override
            protected Boolean doInBackground(Void... params) {
                try {
                    URL url = new URL(logoutEndpoint);
                    HttpURLConnection connection = (HttpURLConnection) url.openConnection();
                    connection.setRequestMethod("POST");
                    connection.setRequestProperty("Content-Type", "application/json");
                    connection.setDoOutput(true);

                    // Optionally, you can include the auth token in the request headers
                    if (authToken != null) {
                        connection.setRequestProperty("Authorization", "Bearer " + authToken);
                    }

                    int responseCode = connection.getResponseCode();

                    if (responseCode == HttpURLConnection.HTTP_OK) {
                        setAuthToken(null);
                        setRefreshToken(null);
                        setUserId(null);

                        callback.onLogoutComplete(true, null);
                    } else {
                        Log.e("FACTPlus", "Error: Logout request failed. Response Code: " + responseCode);
                        callback.onLogoutComplete(false, new Exception("Logout request failed."));
                    }
                } catch (Exception e) {
                    Log.e("FACTPlus", "Error: " + e.getMessage());
                    callback.onLogoutComplete(false, new Exception("Logout failed."));
                }
                return null;
            }
        }.execute();
    }

    public interface RefreshCallback {
        void onRefreshComplete(boolean success, Exception error);
    }

    public interface RegisterCallback {
        void onRegisterComplete(boolean success, Exception error);
    }

    public interface LoginCallback {
        void onLoginComplete(boolean success, Exception error);
    }

    public interface LogoutCallback {
        void onLogoutComplete(boolean success, Exception error);
    }
}