To achieve the goals mentioned in the case definition.

To collect the data from the stores, we plan to use IoT devices.
 - The IoT devices will be collecting data contiuesly, and sent it to us every hour, however for this demo we're using Arduino Oplá, which isn't designed for our case, so we're randomly generating the data, to prove that the system can handle the data.
 - The IoT devices will primarily transmit data over the mqtt protocol.
 - The IoT devices will on initial setup use http to get some identification (uuid), so that the data sent from one device doesn't interferer with other devices.

To handle the data we plan to create a server.
 - The server will be running nginx webserver, which will direct the trafic to our primary backend written in php using the Laravel framework.
 - The backend will store the data received through our message broker, in a MariaDB sql server.
 - To ensure that updating the servers doesn't break anything, we also want to setup extenxive testing of all endpoints to ensure that the code is working.

For the frontend, we intend to use blade.php.
 - The site will have to primary views, the first is to get an overview of devices, and the second is to view the data collected.
 - The data view will include options for filtering and searching the data.

IoT:
 - Arduino 
 - c++

Message broker:
 - RabbitMQ

Backend:
 - php
 - Laravel

Database:
 - MariaDB

Frontend:
 - Laravel blade templates
