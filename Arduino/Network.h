#ifndef Network_h
#define Network_h

#include "IncludeFile.h"

// If the server host is using ssl, then change the client below to the SSLClient.
WiFiSSLClient client;
// WiFiClient client;

void ConnectWifi(const char* ssid, const char* pass) {
  Serial.print("Attempting to connect to SSID: ");
  Serial.println(ssid);
  while (WiFi.begin(ssid, pass) != WL_CONNECTED) {
    // failed, retry
    Serial.print("-");
    delay(5000);
  }
  Serial.println("You're connected to the network");
  Serial.println();
}

void getMacAddress(char macAddressStr[18]) {
  byte mac[6];
  WiFi.macAddress(mac);
  // Create a buffer to store the formatted MAC address
  // 6 bytes * 2 characters + 5 colons + null terminator
  for (int i = 0; i < 6; ++i) {
    // Format each byte as a two-digit hexadecimal number
    sprintf(macAddressStr + 3 * i, "%02X", mac[i]);

    // Add a colon between bytes (except for the last one)
    if (i < 5) {
      macAddressStr[3 * i + 2] = ':';
    }
  }
}

/*
Checks if the device exists
*/
int checkIfDeviceExists(String uuid) {
  int returnCode = 0;
  // Connect to the server, using the server host and port from secrets.h
  if (client.connect(SERVER_HOST, SERVER_PORT)) {
    // if connected:
    Serial.println("Connected to server");
    // make a HTTP request:
    // send HTTP header
    client.println("GET /api/device/exists?uuid=" + uuid + " HTTP/1.1");
    client.println("Host: " + String(SERVER_HOST));
    // client.println("Accept: application/json");
    client.println("Connection: close");
    client.println();  // end HTTP header

    // Handle response
    String line = "";
    int statusCode = 0;

    while (client.connected()) {
      // Serial.println("Client connected");
      
      if (client.available()) {
        // Serial.println("Client available");
        // read an incoming byte from the server and print it to serial monitor:
        char c = client.read();
        Serial.print(c);

        if (c == '\n') {
          Serial.println(line);

          if (line.startsWith("HTTP/1.1 ")) {
            Serial.print("Status code: ");
            statusCode = line.substring(9, 12).toInt();
            Serial.println(statusCode);
          }
          line = "";
        } else {
          line += c;
        }
      }
    }

    if (statusCode == 200) {
      returnCode = 1;
    }

    // the server's disconnected, stop the client:
    client.stop();
    Serial.println("disconnected");
  } else {
    // if not connected:
    Serial.println("connection failed");
  }
  return returnCode;
}

String createDevice() {
  String deviceUUID = "";
  // Connect to the server, using the server host and port from secrets.h
  if (client.connect(SERVER_HOST, SERVER_PORT)) {
    // if connected:
    Serial.println("Connected to server");
    // make a HTTP request:
    // send HTTP header
    client.println("POST /api/device/create HTTP/1.1");
    client.println("Host: " + String(SERVER_HOST));
    client.println("Connection: close");
    client.println();  // end HTTP header

    // Handle response
    String line = "";
    String responseData = "";

    while (client.connected()) {
      if (client.available()) {
        // read an incoming byte from the server and print it to serial monitor:
        char c = client.read();
        if (c == '\n') {
          // Serial.println(line);

          if (line.startsWith("HTTP/1.1 ")) {
            Serial.print("Status code: ");
            Serial.println(line.substring(9, 12));
          }
          if (line.startsWith("{\"data\":")) {
            responseData = line;
          }
          line = "";
        } else {
          line += c;
        }
      }
    }

    // The response body:
    Serial.println("Response body:");
    Serial.println(responseData);
    int uuidStartIndex = responseData.indexOf("uuid") + 7;
    deviceUUID = responseData.substring(uuidStartIndex, uuidStartIndex + 36);

    Serial.println();
    Serial.print("Device uuid: ");
    Serial.println(deviceUUID);

    // the server's disconnected, stop the client:
    client.stop();
    Serial.println("disconnected");
  } else {
    // if not connected:
    Serial.println("connection failed");
  }
  return deviceUUID;
}

#endif
