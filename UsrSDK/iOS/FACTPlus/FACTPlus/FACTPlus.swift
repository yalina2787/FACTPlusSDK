import Foundation

class FACTPlus {
    private var authToken: String?
    private var refreshToken: String?
    private var apiUrl: String?
    private var userId: String?
    
    func setAuthToken(_ token: String?) {
        authToken = token
    }
    
    func setRefreshToken(_ token: String?) {
        refreshToken = token
    }
    
    func setApiUrl(_ url: String) {
        apiUrl = url
    }
    
    func refreshAuthToken(completion: @escaping (Bool, Error?) -> Void) {
        // Check if API URL is set
        guard let apiUrl = apiUrl else {
            print("Error: API URL not set.")
            return
        }
        
        // Check if refreshToken is available
        guard let refreshToken = refreshToken else {
            print("Error: Refresh Token not set.")
            completion(false, NSError(domain: "RefreshErrorDomain", code: 3, userInfo: [NSLocalizedDescriptionKey: "Refresh Token not set."]))
            return
        }
        
        // Prepare the request URL
        guard let refreshURL = URL(string: apiUrl + "/refreshToken") else {
            print("Error: Invalid API URL.")
            return
        }
        
        // Create request body
        let requestBody = [
            "refreshToken": refreshToken
        ]
        
        // Convert request body to Data
        guard let jsonData = try? JSONSerialization.data(withJSONObject: requestBody) else {
            print("Error: Failed to convert request body to JSON.")
            return
        }
        
        // Prepare the request
        var request = URLRequest(url: refreshURL)
        request.httpMethod = "POST"
        request.httpBody = jsonData
        request.addValue("application/json", forHTTPHeaderField: "Content-Type")
        
        // Perform the refresh token request
        URLSession.shared.dataTask(with: request) { (data, response, error) in
            if let error = error {
                print("Error: \(error.localizedDescription)")
                completion(false, error)
            } else if let data = data {
                // Process the API response data
                let responseString = String(data: data, encoding: .utf8) ?? ""
                print("API Response: \(responseString)")
                
                // Parse the response to retrieve the new authentication token
                if responseString.contains("success") {
                    // Extract and set the new auth token
                    let newAuthToken = "newDummyAuthToken"
                    self.setAuthToken(newAuthToken)
                    completion(true, nil)
                } else {
                    let refreshError = NSError(domain: "RefreshErrorDomain", code: 4, userInfo: [NSLocalizedDescriptionKey: "Refresh Token request failed."])
                    completion(false, refreshError)
                }
            }
        }.resume()
    }
    
    func registerUser(username: String, password: String, completion: @escaping (Bool, Error?) -> Void) {
        // Check if API URL is set
        guard let apiUrl = apiUrl else {
            print("Error: API URL not set.")
            return
        }
        
        // Prepare the request URL with necessary parameters
        guard let registrationURL = URL(string: apiUrl + "/register") else {
            print("Error: Invalid API URL.")
            return
        }
        
        // Create request body
        let requestBody = [
            "username": username,
            "password": password
        ]
        
        // Convert request body to Data
        guard let jsonData = try? JSONSerialization.data(withJSONObject: requestBody) else {
            print("Error: Failed to convert request body to JSON.")
            return
        }
        
        // Prepare the request
        var request = URLRequest(url: registrationURL)
        request.httpMethod = "POST"
        request.httpBody = jsonData
        request.addValue("application/json", forHTTPHeaderField: "Content-Type")
        
        // Perform the registration request
        URLSession.shared.dataTask(with: request) { (data, response, error) in
            if let error = error {
                print("Error: \(error.localizedDescription)")
                completion(false, error)
            } else if let data = data {
                // Process the API response data
                let responseString = String(data: data, encoding: .utf8) ?? ""
                print("API Response: \(responseString)")
                
                let registrationResult = responseString.contains("success")
                
                if registrationResult {
                    completion(true, nil)
                } else {
                    let registrationError = NSError(domain: "RegistrationErrorDomain", code: 1, userInfo: [NSLocalizedDescriptionKey: "Registration failed"])
                    completion(false, registrationError)
                }
            }
        }.resume()
    }
    func loginUser(username: String, password: String, completion: @escaping (Bool, Error?) -> Void) {
        guard let apiUrl = apiUrl,
              let loginURL = URL(string: apiUrl + "/login") else {
            print("Error: Invalid API URL.")
            return
        }
        
        // Create request body
        let requestBody = [
            "username": username,
            "password": password
        ]
        
        // Convert request body to Data
        guard let jsonData = try? JSONSerialization.data(withJSONObject: requestBody) else {
            print("Error: Failed to convert request body to JSON.")
            return
        }
        
        // Prepare the request
        var request = URLRequest(url: loginURL)
        request.httpMethod = "POST"
        request.httpBody = jsonData
        request.addValue("application/json", forHTTPHeaderField: "Content-Type")
        
        // Perform the login request
        URLSession.shared.dataTask(with: request) { (data, response, error) in
            if let error = error {
                print("Error: \(error.localizedDescription)")
                completion(false, error)
            } else if let data = data {
                // Process the API response data
                let responseString = String(data: data, encoding: .utf8) ?? ""
                print("API Response: \(responseString)")
                
                // Parse the response to determine if login was successful
                if responseString.contains("success") {
                    // Extract and set the new auth token
                    let newAuthToken = "newDummyAuthToken"
                    self.setAuthToken(newAuthToken)
                    
                    // Extract userId from the response
                    let userId = "dummyUserId"
                    self.userId = userId
                    
                    completion(true, nil)
                } else {
                    let loginError = NSError(domain: "LoginErrorDomain", code: 2, userInfo: [NSLocalizedDescriptionKey: "Login failed"])
                    completion(false, loginError)
                }
            }
        }.resume()
    }
    
    func getUserId() -> String? {
        return userId
    }
    
    func logoutUser() {
        // Clear authentication tokens
        setAuthToken(nil)
        setRefreshToken(nil)
    }
}
