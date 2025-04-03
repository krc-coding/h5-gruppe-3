#ifndef Display_h
#define Display_h

#include "IncludeFile.h"

extern MKRIoTCarrier carrier;

class Display {
public:
  static void ClearDisplay() {
    carrier.display.fillScreen(0x0000);
  }
  static void ClearArea(int xPos, int yPos, int width = 5, int height = 7) {
    carrier.display.fillRect(xPos, yPos, width, height, 0x0000);
  }
  static void PrintToDisplay(String text, int xPos, int yPos, int fontSize) {
    carrier.display.setTextSize(fontSize);
    carrier.display.setCursor(xPos, yPos);
    carrier.display.print(text);
  }
  static void OverrideOnDisplay(String text, int xPos, int yPos, int fontSize) {
    carrier.display.setTextSize(fontSize);

    // Set text color to black.
    carrier.display.setTextColor(0x0000);
    carrier.display.setCursor(xPos, yPos);
    carrier.display.print(text);

    // Rest to white.
    carrier.display.setTextColor(0xFFFF);
  }
};

#endif
