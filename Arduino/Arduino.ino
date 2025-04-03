#include "IncludeFile.h"

MKRIoTCarrier carrier;
Config config = Config();

MessageHandler messageHandler = MessageHandler();

WiFiUDP ntpUDP;
NTPClient timeClient(ntpUDP, "pool.ntp.org");

String deviceUUID = "";

void setup() {
  // Initialize serial:
  Serial.begin(9600);
  // Wait 5 sek for serial port to connect. Needed for native USB port only
  // delay(5000);
  while (!Serial) {
    ;  // wait for serial port to connect. Needed for native USB port only
  }

  // carrier.noCase();
  carrier.withCase();
  carrier.begin();

  ConnectWifi(config.ssid, config.pass);
  printWiFiStatus();

  deviceUUID = SDCardWrapper::readFile("CONFIG");
  Serial.println(deviceUUID);
  int deviceExists = checkIfDeviceExists(deviceUUID);

  // Device doesn't exist, creating new device.
  if (deviceExists == 0) {
    deviceUUID = createDevice();
    SDCardWrapper::deleteFile("CONFIG");
    SDCardWrapper::writeToFile("CONFIG", deviceUUID);
  }

  Serial.println(deviceUUID);

  Display::ClearDisplay();
  Display::PrintToDisplay("device uuid: ", 50, 80, 2);
  Display::PrintToDisplay(deviceUUID, 10, 120, 1);

  messageHandler.init();
}

void printWiFiStatus() {
  // print the SSID of the network you're attached to:
  Serial.print("SSID: ");
  Serial.println(WiFi.SSID());

  // print your board's IP address:
  IPAddress ip = WiFi.localIP();
  Serial.print("IP Address: ");
  Serial.println(ip);

  // print the received signal strength:
  long rssi = WiFi.RSSI();
  Serial.print("signal strength (RSSI):");
  Serial.print(rssi);
  Serial.println(" dBm");
}

void loop() {
}
