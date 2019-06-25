CREATE DATABASE conference_db;
USE conference_db;

CREATE TABLE organizing_committee(
    subcommittee_name VARCHAR(30) PRIMARY KEY NOT NULL, 
    chairman_id CHAR(5) NOT NULL
);

CREATE TABLE subcommittee_members(
    subcommittee_name VARCHAR(30) NOT NULL, 
    first_name VARCHAR(30), 
    last_name VARCHAR(30),
    id CHAR(5) NOT NULL,
    PRIMARY KEY (id, subcommittee_name),
    FOREIGN KEY (subcommittee_name)
        REFERENCES organizing_committee(subcommittee_name)
        ON DELETE CASCADE
);

CREATE TABLE attendees(
    first_name VARCHAR(30),    
    last_name VARCHAR(30),
    attendee_type ENUM('Student','Professional','Sponsor'),
    id CHAR(5) PRIMARY KEY NOT NULL
);

CREATE TABLE price(
    attendee_type ENUM('Student','Professional','Sponsor') PRIMARY KEY,
    cost DECIMAL(5,2)
);

CREATE TABLE rooms(
    room_number CHAR(3) PRIMARY KEY NOT NULL,
    spots INT NOT NULL,
    spots_taken INT NOT NULL
    
);

CREATE TABLE students(
    first_name VARCHAR(30) NOT NULL,
    last_name VARCHAR(30) NOT NULL,
    id CHAR(5) NOT NULL,
    room_number CHAR(3),    
    FOREIGN KEY (id) 
        REFERENCES attendees(id)
        ON DELETE CASCADE,
    FOREIGN KEY (room_number)
        REFERENCES rooms(room_number)
        ON DELETE CASCADE   
);

CREATE TABLE companies(
    company VARCHAR(30) PRIMARY KEY NOT NULL,
    sponsor_rank ENUM('Bronze','Silver','Gold','Platinum'),
    emails_sent INT NOT NULL DEFAULT 0
);

CREATE TABLE sponsor_members(
    first_name VARCHAR(30) NOT NULL,
    last_name VARCHAR(30) NOT NULL,
    id CHAR(5) NOT NULL,
    company VARCHAR(30) NOT NULL, 
    FOREIGN KEY (id) 
        REFERENCES attendees(id)
        ON DELETE CASCADE,
    FOREIGN KEY (company)
        REFERENCES companies(company)
        ON DELETE CASCADE
);

CREATE TABLE jobs(
    title VARCHAR(30) NOT NULL,
    company VARCHAR(30) NOT NULL,
    pay DECIMAL(7,2) NOT NULL,
    location VARCHAR(50) NOT NULL,
    job_id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    FOREIGN KEY (company)
        REFERENCES companies(company)
        ON DELETE CASCADE
);

CREATE TABLE sessions(
    speaker_first VARCHAR(30) NOT NULL,
    speaker_last VARCHAR(30) NOT NULL,
    speaker_id CHAR(5) NOT NULL,
    session VARCHAR(30) NOT NULL,
    session_day ENUM('Day 1','Day 2') NOT NULL,
    start_t TIME NOT NULL,
    end_t TIME NOT NULL,
    room CHAR(3) NOT NULL,
    PRIMARY KEY (room, start_t),
    FOREIGN KEY (speaker_id) 
        REFERENCES attendees(id)
        ON DELETE CASCADE
);

INSERT INTO price VALUES
    ('Student', 50.00),
    ('Professional', 100.00),
    ('Sponsor',0.00);

INSERT INTO organizing_committee VALUES
    ("Program Committee", "00001"),
    ("Registration Committee", "00002"),
    ("Sponsorship Committee", "00003");

INSERT INTO subcommittee_members VALUES
    ("Program Committee", "Kenya", "Moore", "00001"),
    ("Program Committee", "Gabby", "Bermudez", "00002"),
    ("Registration Committee", "Gabby", "Bermudez", "00002"),
    ("Registration Committee", "Jacqueline", "Heaton", "00004"),
    ("Sponsorship Committee", "Patrick", "Landry", "00003");

INSERT INTO attendees VALUES
    ("Jacqueline","Heaton",'Professional','10001'),
    ("Gabby","Bermudez",'Professional','10002'),
    ("Jason","Liu",'Student','10003'),
    ("Tara","Carette",'Student','10004'),
    ("Kenya","Moore",'Professional','10005'),
    ("Edward","Elric",'Student','10006'),
    ("Roy","Mustang",'Sponsor','10007'),
    ("Oliver","Armstrong",'Sponsor','10008'),
    ("Maes","Huges",'Sponsor','10009');

INSERT INTO students (first_name, last_name, id)
    SELECT first_name, last_name, id FROM attendees WHERE attendee_type = 'Student';

INSERT INTO rooms VALUES
    ('103',4,0),
    ('104',4,0),
    ('105',4,0),
    ('106',2,0),
    ('107',4,0),
    ('108',2,0);

INSERT INTO companies (company, sponsor_rank) VALUES
    ('Google','Bronze'),
    ('Samsung','Silver'),
    ('Facebook','Gold'),
    ('Microsoft','Platinum');

INSERT INTO sponsor_members VALUES
    ((SELECT first_name FROM attendees WHERE id = '10007'),(SELECT last_name FROM attendees WHERE id = '10007'), '10007', 'Google'),
    ((SELECT first_name FROM attendees WHERE id = '10008'), (SELECT last_name FROM attendees WHERE id = '10008'),'10008' , 'Microsoft'),
    ((SELECT first_name FROM attendees WHERE id = '10009'), (SELECT last_name FROM attendees WHERE id = '10009'), '10009', 'Facebook');

INSERT INTO jobs VALUES
    ('Software Developer', 'Microsoft', 50000, 'Toronto', NULL),
    ('Data Analyst','Google', 40000,'Waterloo', NULL),
    ('Application Tester','Samsung', 30000,'Kingston', NULL),
    ('Web Developer', 'Facebook',50000,'Toronto',NULL),
    ('Software Engineer', 'Google', 60000,'Waterloo',NULL),
    ('Database Management', 'Microsoft', '40000','Pickering', NULL);

INSERT INTO sessions VALUES
    ((SELECT first_name FROM attendees WHERE id = '10007'),(SELECT last_name FROM attendees WHERE id = '10007'), '10007', 'Firefox', 'Day 1', '12:00', '1:00', '123'),
    ((SELECT first_name FROM attendees WHERE id = '10008'),(SELECT last_name FROM attendees WHERE id = '10008'), '10008', 'Hardware Permissions', 'Day 2', '8:00', '9:00', '121'),
    ((SELECT first_name FROM attendees WHERE id = '10008'),(SELECT last_name FROM attendees WHERE id = '10008'), '10008', 'OS Systems', 'Day 1', '10:00', '11:00', '122');    
   
