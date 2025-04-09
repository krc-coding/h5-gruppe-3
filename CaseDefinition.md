## **Case beskrivelse**

### **Projektoplæg**

Indeholder et scenarie med en eller flere problemstillinger, som eleven skal undersøge og finde mulige løsninger på.

## **Produktrapport**

**Indhold**:

- En kortfattet projektbeskrivelse, der forklarer målet. Kan inkludere blokdiagrammer eller skitser af løsningen.
- Domænemodel, der beskriver systemets nøgleobjekter og deres relationer.
- Funktionelle krav - Her kan der bruges
- Ikke-funktionelle krav.

### **Kravspecifikation**

1. **Projektbeskrivelse:**
    Vi ønsker at skabe et system til at indsamle data fra fysiske butikker. De data, vi er interesseret i at indsamle, er:
    - Hvor mange mennesker der er kommet ind i butikken
    - Hvor mange produkter der er blevet solgt
    - Hvilke typer produkter der er blevet solgt
    - Den samlede værdi af solgte produkter
    - Hvor mange pakker der er blevet afhentet og leveret til butikken

    Formålet er at opsamle data fra butikker, og vise det på en intuitiv måde hvor en bruger kan filtere og søge i dataen.

2. **Domænemodel**
    - Beskrivelse af de vigtigste objekter i systemet og relationerne mellem dem.
    - Ofte illustreret med et UML-diagram, hvor hver klasse/entitet præsenteres kort (navn, egenskaber, relationer).
    - Dette gælder også hvis I bruger mange systemer til at opbygge systemet

    **Domænemodel:**
    - IoT-enheder: Indsamler data kontinuerligt og sender det hver time.
    - Server: Kører en nginx-webserver, der dirigerer trafik til en backend skrevet i PHP ved hjælp af Laravel-rammen.
    - Backend: Modtager data gennem en message broker og gemmer det i en MariaDB SQL-server.
    - Frontend: Bruger Laravel blade templates til at vise data.

3. **Funktionelle krav:**
    - IoT enhederne skal vise et id på et display, som kan bruges til filterering af dataen.
    - IoT enhederne må kun bruge http til den initiele kommunikation til at få et uuid.
    - IoT-enheder skal kunne indsamle og sende data kontinuerligt.

    - Serveren skal kunne modtage og gemme data fra IoT-enhederne.

    - Frontend skal kunne vise de indsamlede data.
    - Frontend skal have en admin side: 
        - Login + logout.
        - Hvor man kan se en liste af enheder.
        - Hvor man kan grupperer enheder, som en enkelt enhed.
        - Administerer enheder + grupperede enheder
    - Data visningen skal inkludere muligheder for filtrering og søgning.
    - Data skal kunne eksporteres til csv.
    - alt data skal være tilgængeligt for alle.
    - CI/CD Deployment


4. **Ikke-funktionelle krav:**
    - Brugervenlighed: Brugergrænsefladen skal være intuitiv og nem at navigere.
    - Pålidelighed: Systemet skal have en oppetid på 99,9%.
    - Ydeevne: Systemet skal kunne håndtere op til 1000 samtidige enheder.
    - Supportability: Koden skal være veldokumenteret og let at vedligeholde.

5. **Afgrænsning:**


## **Case Description**

### **Project Proposal**

A retail store wants to optimize its operations by understanding customer behavior and sales patterns. The store faces challenges in manually tracking the number of visitors, sales, and package deliveries. The student is tasked with developing a system that automates data collection and provides insights through an intuitive interface. The solution should help the store plan inventory, staffing, and marketing strategies more effectively.

## **Product Report**

### **Requirements Specification**

1. **Project Description:**
    We want to create a system to collect data from physical stores. The data we are interested in collecting are:
    - How many people have entered the store
    - How many products have been sold
    - What types of products have been sold
    - The total value of sold products
    - How many packages have been picked up and delivered to the store if the store has a package delivery spot

    The purpose is to collect data from stores and display it in an intuitive way where a user can filter and search the data.

2. **Domain Model:**
    - IoT devices: Collect data continuously and send it every hour.
    - Server: Runs an nginx web server that directs traffic to a backend written in PHP using the Laravel framework.
    - Backend: Receives data through a message broker and stores it in a MariaDB SQL server.
    - Frontend: Uses Laravel blade templates to display data.

    ```mermaid
    ---
    title: System setup
    ---
    graph LR;
    E[Frontend] --- C
    A[IoT]-->B[Message broker]
    B-->C[Backend]
    C-->D[(Database)]
    A-- HTTP One time (on startup) ---C
    ```

3. **Functional Requirements:**
    - IoT devices must display an ID on a display, which can be used to filter the data.
    - IoT devices must only use HTTP for the initial communication to get a UUID.
    - IoT devices must be able to collect and send data continuously.
    - The server must be able to receive and store data from IoT devices.
    - The frontend must be able to display the collected data.
    - The frontend must have an admin page:
        - Login + logout.
        - Where you can see a list of devices.
        - Where you can group devices as a single unit.
        - Manage devices + grouped devices.
    - Data display must include filtering and searching options.
    - Data must be exportable to CSV.
    - All data must be accessible to everyone.
    - CI/CD Deployment.

4. **Non-functional Requirements:**
    - Usability: The user interface must be intuitive and easy to navigate.
    - Reliability: The system must have an uptime of 99.9%.
    - Performance: The system must handle up to 1000 concurrent devices.
    - Supportability: The code must be well-documented and easy to maintain.

5. **Limitations:**
    - Data predictions