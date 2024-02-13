# server.py
from flask import Flask, request, jsonify

app = Flask(__name__)

# Example data to simulate device registration, authorization, and device functions
registered_devices = {}
authorized_users = {"user123": ["specific_function"]}

@app.route('/register_device', methods=['POST'])
def register_device():
    data = request.get_json()
    device_id = data.get("device_id")
    registered_devices[device_id] = data
    return jsonify({"device_id": device_id, "message": "Device registered successfully."}), 200

@app.route('/unregister_device/<device_id>', methods=['DELETE'])
def unregister_device(device_id):
    if device_id in registered_devices:
        del registered_devices[device_id]
        return jsonify({"device_id": device_id, "message": "Device unregistered successfully."}), 200
    else:
        return jsonify({"error": "Device not found."}), 404

@app.route('/authorize', methods=['GET'])
def authorize():
    device_id = request.args.get("device_id")
    auth_token = request.args.get("auth_token")
    user_id = request.args.get("user_id")
    device_function = request.args.get("device_function")

    if user_id in authorized_users and device_function in authorized_users[user_id]:
        return jsonify({"authorized": True, "message": "User authorized to access the device function."}), 200
    else:
        return jsonify({"authorized": False, "message": "User not authorized to access the device function."}), 403

@app.route('/update_device_location/<device_id>', methods=['PUT'])
def update_device_location(device_id):
    if device_id in registered_devices:
        data = request.get_json()
        registered_devices[device_id]["latitude"] = data.get("latitude")
        registered_devices[device_id]["longitude"] = data.get("longitude")
        return jsonify({"device_id": device_id, "message": "Device location updated successfully."}), 200
    else:
        return jsonify({"error": "Device not found."}), 404

if __name__ == '__main__':
    app.run(debug=True)
