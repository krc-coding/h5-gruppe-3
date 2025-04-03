#ifndef config_h
#define config_h

#include "IncludeFile.h"

class Config {
  public:
  const char* ssid = WIFI_SSID;
  const char* pass = WIFI_PASSWORD;
  const char* server = SERVER_HOST;
  const char* topic = "DataCollection";
  const char* mqtt_user = MQTT_USERNAME;
  const char* mqtt_pass = MQTT_PASSWORD;
};

#endif
