#include "WiFi.h"
#ifndef Network_h
#define Network_h

#include "IncludeFile.h"
#include <ArduinoHttpClient.h>


// If the server host is using ssl, then change the client below to the SSLClient.
// WiFiSSLClient wifiClient;
WiFiClient wifiClient;
HttpClient client = HttpClient(wifiClient, SERVER_HOST, SERVER_PORT);

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

/*
Checks if the device exists
*/
int checkIfDeviceExists(String uuid) {
  Serial.println("Checking if device exists.");
  client.get("/api/device/exists?uuid=" + uuid);
  int statusCode = client.responseStatusCode();
  String body = client.responseBody();

  Serial.print("Status code: ");
  Serial.println(statusCode);

  Serial.print("Response body: ");
  Serial.println(body);

  if (statusCode == 200) {
    return 1;
  }
  return 0;
}

/*
Creates a new device
*/
String createDevice() {
  Serial.println("Creating device.");
  client.post("/api/device/create");
  int statusCode = client.responseStatusCode();
  String body = client.responseBody();

  Serial.print("Status code: ");
  Serial.println(statusCode);

  Serial.print("Response body: ");
  Serial.println(body);

  int uuidStartIndex = body.indexOf("uuid") + 7;
  String deviceUUID = body.substring(uuidStartIndex, uuidStartIndex + 36);
  return deviceUUID;
}

#endif
