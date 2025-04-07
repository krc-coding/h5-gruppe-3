#ifndef Display_h
#define Display_h

#include "IncludeFile.h"

extern MKRIoTCarrier carrier;

class Display {
public:
  static void ClearDisplay() {
    carrier.display.fillScreen(0x0000);
  }
  
  static void PrintToDisplay(String text, int xPos, int yPos, int fontSize) {
    carrier.display.setTextSize(fontSize);
    carrier.display.setCursor(xPos, yPos);
    carrier.display.print(text);
  }
};

#endif
