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

  // Wait 5 seconds to allow time for serial port to connect, only needed for native USB port debugging
  delay(5000);

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
  timeClient.update();
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
  // Wait 1 minute
  delay(1000 * 60 * 1);

  // Update the time client timestamp.
  timeClient.update();

  // Create json document for product categories.
  JsonDocument productCategories;
  int total = 101;
  int meat = random(0, total);
  total = total - meat;
  int milk = random(0, total);
  total = total - milk;
  int vegetables = random(0, total);
  total = total - vegetables;
  int candy = total - 1;

  productCategories["meat"] = meat;
  productCategories["milk products"] = milk;
  productCategories["vegetables"] = vegetables;
  productCategories["candy"] = candy;

  // Serialize product categories document, before creating the final mqtt payload document.
  char buffer[255];
  serializeJson(productCategories, buffer);

  // Create json document for mqtt payload.
  JsonDocument document;
  document["device_uuid"] = deviceUUID;
  document["people"] = random(1, 100);
  document["products_pr_person"] = random(1, 100);
  document["total_value"] = random(1, 100);
  document["product_categories"] = buffer;
  document["packages_received"] = random(1, 100);
  document["packages_delivered"] = random(1, 100);
  document["data_recorded_at"] = timeClient.getEpochTime();

  // Call function for sending data over mqtt.
  messageHandler.sendData(document);
}
