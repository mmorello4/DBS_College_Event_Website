-- turn on wamp server. 
-- go to localhost/phpmyadmin
-- login to root (you can leave password blank)
-- click Databases
-- Enter database name: rso_events
-- click Create
-- select rso_events database
-- click SQL tab
-- paste the code below and click Go

-- University Table
CREATE TABLE Universities (
    UniversityID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(191) NOT NULL UNIQUE,
    Domain VARCHAR(100) NOT NULL UNIQUE,  -- e.g., 'ucf.edu'

    -- [ADDED] Optional attributes based on project description
    Location VARCHAR(255),
    NumberOfStudents INT,
    PictureURL TEXT
);

-- Users Table (UPDATED)
CREATE TABLE Users (
    UID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL,
    Email VARCHAR(191) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,
    UniversityID INT NOT NULL,
    FOREIGN KEY (UniversityID) REFERENCES Universities(UniversityID)
);

-- RSOs Table
CREATE TABLE RSOs (
    RSOID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(191) NOT NULL UNIQUE,
    Description TEXT,
    CreatedBy INT,
    UniversityID INT,
    FOREIGN KEY (CreatedBy) REFERENCES Users(UID) ON DELETE SET NULL,
    FOREIGN KEY (UniversityID) REFERENCES Universities(UniversityID)
);

-- RSO Membership Table
CREATE TABLE RSO_Members (
    UID INT,
    RSOID INT,
    PRIMARY KEY (UID, RSOID),
    FOREIGN KEY (UID) REFERENCES Users(UID),
    FOREIGN KEY (RSOID) REFERENCES RSOs(RSOID)
);

-- Locations Table
CREATE TABLE Locations (
    LocationID INT AUTO_INCREMENT PRIMARY KEY,
    Description VARCHAR(191) NOT NULL UNIQUE,

    -- [ADDED] Additional fields required
    Name VARCHAR(191) NOT NULL,
    Latitude DECIMAL(9,6),
    Longitude DECIMAL(9,6)
);

-- Events Table (general)
CREATE TABLE Events (
    EventID INT AUTO_INCREMENT PRIMARY KEY,
    Title VARCHAR(255) NOT NULL,
    Description TEXT,
    EventTime DATETIME NOT NULL,
    EndTime DATETIME NOT NULL,
    LocationID INT,
    ContactPhone VARCHAR(20),
    ContactEmail VARCHAR(191),
    CreatedBy INT,
    FOREIGN KEY (LocationID) REFERENCES Locations(LocationID),
    FOREIGN KEY (CreatedBy) REFERENCES Users(UID) ON DELETE SET NULL,

    -- [ADDED] Category support
    CategoryID INT,
    FOREIGN KEY (CategoryID) REFERENCES EventCategories(CategoryID)
);

-- [ADDED] Event Categories Table
CREATE TABLE EventCategories (
    CategoryID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) UNIQUE NOT NULL
);

-- RSO Event Subtype
CREATE TABLE RSO_Events (
    EventID INT PRIMARY KEY,
    RSOID INT,
    FOREIGN KEY (EventID) REFERENCES Events(EventID) ON DELETE CASCADE,
    FOREIGN KEY (RSOID) REFERENCES RSOs(RSOID)
);

-- Private Event Subtype
CREATE TABLE Private_Events (
    EventID INT PRIMARY KEY,
    UniversityID INT,
    FOREIGN KEY (EventID) REFERENCES Events(EventID) ON DELETE CASCADE,
    FOREIGN KEY (UniversityID) REFERENCES Universities(UniversityID)
);

-- Public Event Subtype
CREATE TABLE Public_Events (
    EventID INT PRIMARY KEY,

    -- [ADDED] Approval fields
    NeedsApproval BOOLEAN DEFAULT TRUE,
    ApprovedBy INT,
    FOREIGN KEY (EventID) REFERENCES Events(EventID) ON DELETE CASCADE,
    FOREIGN KEY (ApprovedBy) REFERENCES SuperAdmins(SuperAdminID)
);

-- ADMINS TABLE
CREATE TABLE Admins (
    AdminID INT AUTO_INCREMENT PRIMARY KEY,
    UID INT UNIQUE,
    FOREIGN KEY (UID) REFERENCES Users(UID) ON DELETE CASCADE
);

-- SUPER ADMINS TABLE
CREATE TABLE SuperAdmins (
    SuperAdminID INT AUTO_INCREMENT PRIMARY KEY,
    UID INT UNIQUE,
    FOREIGN KEY (UID) REFERENCES Users(UID) ON DELETE CASCADE
);

-- [ADDED] Comments Table
CREATE TABLE Comments (
    CommentID INT AUTO_INCREMENT PRIMARY KEY,
    EventID INT NOT NULL,
    UID INT NOT NULL,
    CommentText TEXT,
    Rating INT CHECK (Rating BETWEEN 1 AND 5),
    Timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (EventID) REFERENCES Events(EventID) ON DELETE CASCADE,
    FOREIGN KEY (UID) REFERENCES Users(UID) ON DELETE CASCADE
);

-- Trigger: Prevent overlapping events at the same location and time
DELIMITER //

CREATE TRIGGER PreventOverlappingEvents
BEFORE INSERT ON Events
FOR EACH ROW
BEGIN
    DECLARE conflict_count INT;

    SELECT COUNT(*) INTO conflict_count
    FROM Events
    WHERE LocationID = NEW.LocationID
      AND (
            (NEW.EventTime BETWEEN EventTime AND EndTime) OR
            (NEW.EndTime BETWEEN EventTime AND EndTime) OR
            (EventTime BETWEEN NEW.EventTime AND NEW.EndTime)
          );

    IF conflict_count > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: An event already exists at this location during this time range.';
    END IF;
END;
//

DELIMITER ;


-- inserts some universities into the university table
INSERT INTO Universities (Name, Domain, Location, NumberOfStudents, PictureURL) VALUES
('University of Central Florida', 'ucf.edu', 'Orlando, FL', 70000, 'https://www.ucf.edu/files/2023/03/ucf-campus.jpg'),
('University of Florida', 'ufl.edu', 'Gainesville, FL', 55000, 'https://www.ufl.edu/assets/img/home/hero.jpg'),
('Florida State University', 'fsu.edu', 'Tallahassee, FL', 43000, 'https://www.fsu.edu/images/homepage/fsu-campus.jpg'),
('University of South Florida', 'usf.edu', 'Tampa, FL', 51000, 'https://www.usf.edu/_images/homepage/usf-campus.jpg'),
('Massachusetts Institute of Technology', 'mit.edu', 'Cambridge, MA', 11500, 'https://mitadmissions.org/apply/mit-campus.jpg'),
('Stanford University', 'stanford.edu', 'Stanford, CA', 17000, 'https://www.stanford.edu/sites/default/files/styles/hero_lg/public/media/2022-07/stanford-campus.jpg'),
('Georgia Institute of Technology', 'gatech.edu', 'Atlanta, GA', 39000, 'https://www.gatech.edu/sites/default/files/gtcampus.jpg');
