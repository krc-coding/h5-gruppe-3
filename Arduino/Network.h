#ifndef Network_h
#define Network_h

#include "IncludeFile.h"

// If the server host is using ssl, then change the client below to the SSLClient. 
// WiFiSSLClient client;
WiFiClient client;

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


#endif
