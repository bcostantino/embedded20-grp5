#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>
#include <Arduino_JSON.h>
#include <Servo.h>

 
const char* ssid     = "MOTOROLA-F72FE";  //My WiFi SSID
const char* password = "e15fb9dcc8";      //My WiFi pswd

String response_type = "most_recent";     //Parameters for HTTP GET request 
String recent_req = "0";
String door_id = "ce76a42e-4a5f-4d37-91c4-a270f8d25e8d";
String PIN = "330928";

bool lock_value = false;      //status of lock
bool past_lock_value = false; //past status of lock to know if the status has changed
String lock_value_string;     //status of lock as a string for if statement


Servo servo1;             //Declare Servo object
int servoPin = 5;         //Servo pin on ESP

int redLEDPin = 12;       //Red LED pin on ESP
int greenLEDPin = 13;     //Green LED pin on ESP

void setup () {
 
  Serial.begin(115200);         //Open Serial communication for troubleshooting
  WiFi.mode(WIFI_STA);          //Set ESP in station mode to connect to router
  WiFi.begin(ssid, password);   //Connect to WiFi
 
  while (WiFi.status() != WL_CONNECTED) {     //While ESP is still not connected to WiFi display message in Serial port to notify it's still connecting
 
    delay(1000);
    Serial.println("Connecting..");
  }
  Serial.print("Connected, IP Address: ");    //After ESP is connected to WiFi, display message in Serial port to notify it has connected
  Serial.println(WiFi.localIP());

  servo1.attach(servoPin);           //Attach Servo object to pin 5 of ESP
  pinMode(redLEDPin, OUTPUT);        //Attach Red LED to pin 12 of ESP
  pinMode(greenLEDPin, OUTPUT);      //Attach Green LED to pin 13 of ESP

}
 
void loop() {
 if (WiFi.status() == WL_CONNECTED) { //Check WiFi connection status
 
    HTTPClient http;  //Declare an object of class HTTPClient
 
    http.begin("http://ruembdoorlock.000webhostapp.com/client-scripts/db-query.php?response_type=" + response_type + "&recent_req=" + recent_req + "&door_id=" + door_id + "&PIN=" + PIN);
    int httpCode = http.GET();                                  //Send the request
 
    if (httpCode > 0) { //Check the returning code
 
      String payload = http.getString();        //Get the request response payload
      Serial.println(payload);                  //Print the response payload
      JSONVar myObject = JSON.parse(payload);   //Create a JSONVar object from the response payload

      Serial.print("JSON object = ");     //Print the JSONVar object for troubleshooting
      Serial.println(myObject);

      JSONVar keys = myObject.keys();     //Create a JSONVar object from the keys of myObject

      Serial.println(myObject[keys[1]]);  //Print the value in myObject using the second index of keys
      JSONVar value = myObject[keys[1]];  //Create a JSONVar object from the value at the second index of myObject

      Serial.println(value[0]);           //Print the the contents at the first index of value, this is the lock_value that we're looking for
      const char* lock_value_char = (const char*)(value[0]);      //JSONVar cannot be changed to String directly, so we change to const char* first
       lock_value_string = String(lock_value_char);
      Serial.println(lock_value_string);  //Print the lock_value_string for troubleshooitng
      if(lock_value_string == "0"){       //If lock_value_string is 0, set lock_value to false
        lock_value = false;
      }
      else if(lock_value_string == "1"){  //If lock_value_string is 1, set lock_value to true
        lock_value = true;
      }
    }

    http.end();   //Close connection for the GET Request

  if(lock_value == true){                 //if lock_value is set to lock state
    servo1.write(90);                     //have servo at 90 degrees
    if(past_lock_value != lock_value){    //if lock_value has just changed from unlock to lock, POST to server and blink green LED
      http.begin("http://ruembdoorlock.000webhostapp.com/client-scripts/post-status.php");
      String postData = "{\"door_id\":\"ce76a42e-4a5f-4d37-91c4-a270f8d25e8d\",\"status\":1}";
      int httpPostCode = http.POST(postData);
      Serial.println(httpPostCode);
      Serial.println(http.getString());
      http.end();
      for(int i = 0; i < 3; i++){
        Serial.println("green light");
        digitalWrite(greenLEDPin, HIGH);
        delay(500);
        digitalWrite(greenLEDPin, LOW);
        delay(500);
      }
      past_lock_value = lock_value;     //set past_lock_value to the current lock_value so that the esp knows that the state hasn't changed
    }
  }
  else{                                  //if lock_value is set to lock state
    servo1.write(0);                     //have servo at 0 degrees
    if(past_lock_value != lock_value){   //if lock_value has just changed from lock to unlock, POST to server and blink red LED
      http.begin("http://ruembdoorlock.000webhostapp.com/client-scripts/post-status.php");
      String postData = "{\"door_id\":\"ce76a42e-4a5f-4d37-91c4-a270f8d25e8d\",\"status\":0}";
      int httpPostCode = http.POST(postData);
      Serial.println(httpPostCode);
      Serial.println(http.getString());
      http.end();
      for(int i = 0; i < 3; i++){
        Serial.println("red light");
        digitalWrite(redLEDPin, HIGH);
        delay(500);
        digitalWrite(redLEDPin, LOW);
        delay(500);
      }
      past_lock_value = lock_value;   //set past_lock_value to the curent lock_value so that the esp knows that the state hasn't changed
    } 
  }
 }
  delay(1000);    //Send a request every 1 second
}
