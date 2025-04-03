#ifndef MessageHandler_h
#define MessageHandler_h

#include "IncludeFile.h"

WiFiClient wifiClient;
MqttClient mqttClient(wifiClient);

class MessageHandler {
private:
  Config config = Config();
  int port = 1883;

public:
  void init() {
    char mac[18];
    getMacAddress(mac);
    String topic = config.topic;

    Serial.print("Attempting to connect to the MQTT broker: ");
    Serial.println(config.mqtt_host);

    if (!mqttClient.connect(config.mqtt_host, port)) {
      Serial.print("MQTT connection failed! Error code = ");
      Serial.println(mqttClient.connectError());

      while (1)
        ;
    }

    Serial.println("You're connected to the MQTT broker!");
    Serial.println();
  }

  void postMessage(float temperature) {
    char mac[18];

    getMacAddress(mac);
    mqttClient.beginMessage(mac);
    mqttClient.print("{\"temperature\":");
    mqttClient.print(temperature);
    mqttClient.print("}");
    mqttClient.endMessage();
  }
};

#endif
