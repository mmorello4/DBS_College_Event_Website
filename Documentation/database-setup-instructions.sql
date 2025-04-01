-- turn on wamp server. 
-- go to localhost/phpmyadmin
-- login to root (you can leave password blank)
-- click Databases
-- Enter database name: rso_events
-- click Create
-- select rso_events database
-- click SQL tab
-- paste the code below and click Go


CREATE TABLE Users (
    UID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL,
    Email VARCHAR(191) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL
);

CREATE TABLE Locations (
    LocationID INT AUTO_INCREMENT PRIMARY KEY,
    Address VARCHAR(255) NOT NULL,
    City VARCHAR(100),
    State VARCHAR(50),
    ZipCode VARCHAR(20)
);

CREATE TABLE Events (
    EventID INT AUTO_INCREMENT PRIMARY KEY,
    Title VARCHAR(255) NOT NULL,
    Description TEXT,
    EventTime DATETIME NOT NULL,
    LocationID INT,
    CreatedBy INT,
    FOREIGN KEY (LocationID) REFERENCES Locations(LocationID),
    FOREIGN KEY (CreatedBy) REFERENCES Users(UID) ON DELETE SET NULL
);

CREATE TABLE Comments (
    CommentID INT AUTO_INCREMENT PRIMARY KEY,
    EventID INT,
    UserID INT,
    Text TEXT NOT NULL,
    Rating INT CHECK (Rating BETWEEN 1 AND 5),
    Timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (EventID) REFERENCES Events(EventID),
    FOREIGN KEY (UserID) REFERENCES Users(UID)
);

CREATE TABLE RSOs (
    RSOID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(191) NOT NULL UNIQUE,  -- Reduced from 255 to 191
    CreatedBy INT,
    FOREIGN KEY (CreatedBy) REFERENCES Users(UID) ON DELETE SET NULL
);

CREATE TABLE RSO_Events (
    RSOEventID INT AUTO_INCREMENT PRIMARY KEY,
    EventID INT,
    RSOID INT,
    FOREIGN KEY (EventID) REFERENCES Events(EventID),
    FOREIGN KEY (RSOID) REFERENCES RSOs(RSOID)
);

CREATE TABLE Admins (
    AdminID INT AUTO_INCREMENT PRIMARY KEY,
    UID INT UNIQUE,
    FOREIGN KEY (UID) REFERENCES Users(UID) ON DELETE CASCADE
);

CREATE TABLE SuperAdmins (
    SuperAdminID INT AUTO_INCREMENT PRIMARY KEY,
    UID INT UNIQUE,
    FOREIGN KEY (UID) REFERENCES Users(UID) ON DELETE CASCADE
);

ALTER TABLE Events ADD COLUMN EventType ENUM('RSO', 'Private', 'Public') NOT NULL;