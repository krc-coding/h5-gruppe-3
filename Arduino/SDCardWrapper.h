#ifndef SDCardWrapper_h
#define SDCardWrapper_h

#include "IncludeFile.h"

class SDCardWrapper {
    public:
    static void writeToFile(String fileName, String data) {
        Serial.println("Opening / creating file " + fileName);
        File file = SD.open(fileName, FILE_WRITE);
        Serial.println("Writing to file");
        file.println(data);
        file.close();
        Serial.println("Done writing to file");
    }

    static String readFile(String fileName) {
        // re-open the file for reading:
        File file = SD.open(fileName, FILE_READ);
        String contents = "";
        if (file) {
            Serial.println("Reading " + fileName + ":");
            contents = file.readString();
            // close the file:
            file.close();
        }
        else {
            // if the file didn't open, print an error:
            Serial.println("error opening " + fileName);
        }

        return contents;
    }

    static void deleteFile(String fileName) {
        SD.remove(fileName);
    }
};

#endif
