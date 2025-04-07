#include "ArduinoJson/Json/JsonSerializer.hpp"
#ifndef MessageHandler_h
#define MessageHandler_h

#include "IncludeFile.h"
#include <PubSubClient.h>

WiFiClient mqttWifiClient;
PubSubClient mqttClient(mqttWifiClient);

class MessageHandler {
private:
  Config config = Config();
  int port = 1883;

public:
  void init() {
    Serial.print("Attempting to connect to the MQTT broker: ");
    Serial.println(config.mqtt_host);

    mqttClient.setServer(config.mqtt_host, port);
    if (!mqttClient.connect("ArduinoClient", config.mqtt_user, config.mqtt_pass)) {
      Serial.print("MQTT connection failed! Error code = ");
      Serial.println(mqttClient.state());

      Display::PrintToDisplay("Please restart", 40, 160, 2);
      Display::PrintToDisplay("the device!", 40, 180, 2);

      // Prevent the program from continuing further,
      // as the device is not setup correctly.
      while (true) {
        ;
      }
    }

    // Increase the default buffer to allow larger payloads.
    mqttClient.setBufferSize(400);

    Serial.println("You're connected to the MQTT broker!");
    Serial.println();
  }

  void sendData(JsonDocument data) {
    // Hold forbindelsen til MQTT-broker
    if (!mqttClient.connected()) {
      mqttClient.connect("ArduinoClient", config.mqtt_user, config.mqtt_pass);
    }
    mqttClient.loop();

    char buffer[300];
    serializeJson(data, buffer, 300);

    // Send data to mqtt broker
    if (mqttClient.publish(config.topic, buffer)) {
      Serial.println("Data successfully sent.");
    } else {
      Serial.println("Error sending data.");
    }
  }
};

#endif
