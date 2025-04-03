#ifndef MessageHandler_h
#define MessageHandler_h

#include "IncludeFile.h"

WiFiClient wifiClient;
MqttClient mqttClient(wifiClient);

class MessageHandler {
    private:
    /* data */
    const char broker[16] = "78.47.49.176";
    int port = 1883;

    public:
    void init() {
        char mac[18];
        getMacAddress(mac);
        String topic = String(mac) + "/response";

        Serial.print("Attempting to connect to the MQTT broker: ");
        Serial.println(broker);

        if (!mqttClient.connect(broker, port)) {
            Serial.print("MQTT connection failed! Error code = ");
            Serial.println(mqttClient.connectError());

            while (1)
                ;
        }

        Serial.println("You're connected to the MQTT broker!");
        Serial.println();
        
        Serial.print("Subscribing to topic: ");
        Serial.println(topic);
        Serial.println();

        // subscribe to a topic
        mqttClient.subscribe(topic);

        // topics can be unsubscribed using:
        // mqttClient.unsubscribe(topic);

        Serial.print("Topic: ");
        Serial.println(topic);

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
