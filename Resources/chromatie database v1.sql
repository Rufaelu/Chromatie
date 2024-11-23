  create schema chromatie;
  use chromatie;


-- Create the core tables first
create table artist
(
    id         int auto_increment
        primary key,
    firstname  varchar(20)    not null,
    lastname   varchar(20)    null,
    dob        date           not null,
    gender     varchar(1)     not null,
    experiance int            null,
    password   varbinary(255) null,
    bio        text           null,
    picture    mediumblob     null,
    coverpicture mediumblob null

);

CREATE TABLE pending_artist (
                                id INT AUTO_INCREMENT PRIMARY KEY,
                                firstname VARCHAR(20) NOT NULL,
                                lastname VARCHAR(20) NULL,
                                dob DATE NOT NULL,
                                gender VARCHAR(1) NOT NULL,
                                password   varbinary(255) null,
                                experiance INT NULL,
                                phonenumber VARCHAR(20) NULL,
                                email VARCHAR(40) NULL,
                                instagram VARCHAR(2048) NULL,
                                telegram VARCHAR(2048) NULL,
                                whatsapp VARCHAR(2048) NULL,
                                snapchat VARCHAR(2048) NULL,
                                bio TEXT NULL,
                                servicecatagory VARCHAR(15) NOT NULL,
                                servicetype VARCHAR(15) NOT NULL,
                                is_verified BOOLEAN DEFAULT FALSE,
                                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
create table customer
(
    id         int auto_increment
        primary key,
    firstname  varchar(20)    not null,
    lastname   varchar(20)    null,
    dob        date           not null,
    gender     varchar(1)     not null,
    password   varbinary(255) null,
    bio        text           null,
    picture    mediumblob     null
);

create table service
(
    id              int primary key AUTO_INCREMENT,
    servicetype     varchar(15),
    servicecatagory varchar(15)
);

create table artist_services
(
    artistid  int,
    serviceid int,
    price      double,
    PRIMARY KEY (artistid, serviceid),
    FOREIGN KEY (artistid) references artist (id),
    FOREIGN KEY (serviceid) references service (id)
);

CREATE TABLE artist_address
(
    artistid   INT           NOT NULL,
    type       ENUM('fas fa-phone contact-logo', 'fas fa-envelope contact-logo', 'fab fa-instagram contact-logo', 'fab fa-telegram-plane contact-logo', 'fab fa-whatsapp contact-logo', 'fab fa-snapchat-ghost contact-logo') NOT NULL,
    link       VARCHAR(2048) NULL,
    FOREIGN KEY (artistid) REFERENCES artist (id),
    PRIMARY KEY (artistid, type)
);


CREATE TABLE customer_address
(
    customerid   INT           NOT NULL,
    type       ENUM('fas fa-phone contact-logo', 'fab fa-instagram contact-logo', 'fab fa-telegram-plane contact-logo', 'fas fa-envelope contact-logo', 'fab fa-snapchat-ghost contact-logo','fab fa-whatsapp contact-logo') NOT NULL,
    link       VARCHAR(2048) NULL,
    FOREIGN KEY (customerid) REFERENCES customer(id),
    PRIMARY KEY (customerid, type)
);

CREATE TABLE artist_media
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    artistid INT,
    media_type ENUM('image', 'video') NOT NULL, -- To differentiate between images and videos
    media_data LONGBLOB NOT NULL, -- For storing both image and video binary data
    FOREIGN KEY (artistid) REFERENCES artist(id)
);

CREATE TABLE pending_artist_media (
                                      id INT AUTO_INCREMENT PRIMARY KEY,
                                      pending_artist_id INT NOT NULL,
                                      media_type ENUM('image', 'video') NOT NULL, -- To differentiate between image and video
                                      media_data LONGBLOB NOT NULL, -- Actual media (image/video data)
                                      FOREIGN KEY (pending_artist_id) REFERENCES pending_artist(id)
);


CREATE TABLE project (
                         id            INT AUTO_INCREMENT PRIMARY KEY,
                         projectname   VARCHAR(100) NOT NULL,
                         description   TEXT,
                         status ENUM('completed', 'on going'),
                         customerid   INT null,
                         FOREIGN KEY (customerid) REFERENCES customer(id)

);
CREATE TABLE project_artists (
                                 projectid INT NOT NULL,
                                 artistid INT NOT NULL,
                                 role VARCHAR(50), -- Define roles like 'Lead Artist', 'Collaborator', etc.
                                 PRIMARY KEY (projectid, artistid),
                                 FOREIGN KEY (projectid) REFERENCES project(id),
                                 FOREIGN KEY (artistid) REFERENCES artist(id)
);


create table artist_rating
(
    artistid  int  ,
    stars      int,
    comment    text,
    projectid int,
    primary key (artistid, projectid),
    foreign key (projectid) references project(id),
    foreign key (artistid) references artist (id)
);
CREATE TABLE service_request (
                                 id INT AUTO_INCREMENT PRIMARY KEY,
                                 request_type ENUM('artist to artist','customer to artist') NOT NULL,
                                 requester_id INT NOT NULL, -- Customer or artist making the request
                                 recipient_artistid INT NOT NULL, -- The artist receiving the request
                                 projectname VARCHAR(100) NULL, -- Project name from the request
                                 projectdescription TEXT NULL, -- Project description from the request
                                 request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                 status ENUM('requested', 'approved', 'rejected', 'in-progress', 'completed') DEFAULT 'requested',
                                 FOREIGN KEY (recipient_artistid) REFERENCES artist(id)
);


DELIMITER //

CREATE PROCEDURE insert_service_request(
    IN p_request_type ENUM('artist to artist', 'customer to artist'),
    IN p_requester_id INT, -- Customer or artist making the request
    IN p_recipient_artistid INT, -- The artist receiving the request
    IN p_projectname VARCHAR(100), -- Project name from the request
    IN p_projectdescription TEXT -- Project description from the request
)
BEGIN
    -- Insert a new record into the service_request table
    INSERT INTO service_request (
        request_type,
        requester_id,
        recipient_artistid,
        projectname,
        projectdescription,
        request_date,
        status
    )
    VALUES (
               p_request_type,
               p_requester_id,
               p_recipient_artistid,
               p_projectname,
               p_projectdescription,
               CURRENT_TIMESTAMP,
               'requested'
           );
END //




DELIMITER //



CREATE PROCEDURE start_project(
    IN p_service_request_id INT
)
BEGIN
    DECLARE v_requesttype ENUM('artist to artist', 'customer to artist');
    DECLARE v_recipientid INT;
    DECLARE v_projectname VARCHAR(100);
    DECLARE v_projectdescription TEXT;
    DECLARE v_requesterid INT;
    DECLARE v_projectid INT;

    -- Retrieve project details from the service_request table
    SELECT sr.recipient_artistid, sr.requester_id, sr.request_type, sr.projectname, sr.projectdescription
    INTO v_recipientid, v_requesterid, v_requesttype, v_projectname, v_projectdescription
    FROM service_request AS sr
    WHERE sr.id = p_service_request_id;

    -- Create the project
    IF v_requesttype = 'artist to artist' THEN
        INSERT INTO project (projectname, description, status)
        VALUES (v_projectname, v_projectdescription, 'started');
        SET v_projectid = LAST_INSERT_ID();
        INSERT INTO project_artists (projectid, artistid, role)
        VALUES (v_projectid, v_requesterid, 'Lead Artist'),
               (v_projectid, v_recipientid, 'Collaborator Artist');

    ELSEIF v_requesttype = 'customer to artist' THEN
        INSERT INTO project (customerid, projectname, description, status)
        VALUES (v_requesterid, v_projectname, v_projectdescription, 'started');
        SET v_projectid = LAST_INSERT_ID();
        INSERT INTO project_artists (projectid, artistid, role)
        VALUES (v_projectid, v_recipientid, 'Lead Artist');

    END IF;

END //

DELIMITER ;


CREATE TABLE messages (
                          messageid INT AUTO_INCREMENT PRIMARY KEY,
                          artistid INT NOT NULL,
                          customerid INT NOT NULL,
                          sender ENUM('artist', 'customer') NOT NULL,
                          projectid int null,
                          content TEXT,
                          file_data LONGBLOB, -- Binary data for the file
                          file_name VARCHAR(255), -- Original file name
                          file_type VARCHAR(50), -- MIME type (e.g., 'image/png', 'application/pdf')
                          is_read BOOLEAN DEFAULT FALSE, -- To track if the message is read
                          sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- When the message was sent
                          FOREIGN KEY (artistid) REFERENCES artist(id),
                          FOREIGN KEY (customerid) REFERENCES customer(id),
                          FOREIGN KEY (projectid) REFERENCES project(id)
);

DELIMITER //
CREATE PROCEDURE add_pending_artist(
    IN p_firstname VARCHAR(20),
    IN p_lastname VARCHAR(20),
    IN p_dob DATE,
    IN p_gender VARCHAR(1),
    in p_password   varbinary(255) ,
    IN p_experiance INT,
    IN p_phonenumber VARCHAR(20),
    IN p_email VARCHAR(40),
    IN p_instagram VARCHAR(2048),
    IN p_telegram VARCHAR(2048),
    IN p_whatsapp VARCHAR(2048),
    IN p_snapchat VARCHAR(2048),
    IN p_bio TEXT,
    IN p_servicecatagory VARCHAR(15),
    IN p_servicetype VARCHAR(15),
    IN p_price DOUBLE,
    OUT p_artist_id INT
)
BEGIN
    -- Insert the new artist's data into the pending_artist table
    INSERT INTO pending_artist (
        firstname,
        lastname,
        dob,
        gender,
        experiance,
        phonenumber,
        email,
        password,
        instagram,
        telegram,
        whatsapp,
        snapchat,
        bio,
        servicecatagory,
        servicetype
    )
    VALUES (
               p_firstname,
               p_lastname,
               p_dob,
               p_gender,
               p_password,
               p_experiance,
               COALESCE(p_phonenumber, ''),
               COALESCE(p_email, ''),
               COALESCE(p_instagram, ''),
               COALESCE(p_telegram, ''),
               COALESCE(p_whatsapp, ''),
               COALESCE(p_snapchat, ''),
               COALESCE(p_bio, ''),
               p_servicecatagory,
               p_servicetype
           );

    -- Get the last inserted pending artist ID
    SET p_artist_id = LAST_INSERT_ID();

    -- The media (images/videos) would be inserted separately by calling the add_pending_artist_media procedure.

END //

CREATE PROCEDURE add_pending_artist_media(
    IN p_pending_artist_id INT,
    IN p_media_type ENUM('image', 'video'),
    IN p_media_data LONGBLOB
)
BEGIN
    -- Insert the media data into the pending_artist_media table
    INSERT INTO pending_artist_media (
        pending_artist_id,
        media_type,
        media_data
    )
    VALUES (
               p_pending_artist_id,
               p_media_type,
               p_media_data
           );
END //

CREATE PROCEDURE verify_artist(
    IN p_pending_artist_id INT
)
BEGIN
    DECLARE v_firstname VARCHAR(20);
    DECLARE v_lastname VARCHAR(20);
    DECLARE v_dob DATE;
    DECLARE v_gender VARCHAR(1);
    DECLARE v_password VARBINARY(255);
    DECLARE v_recoveryquestion  VARCHAR(30);
    DECLARE v_recoverynswer  VARCHAR(20);
    DECLARE v_experiance INT;
    DECLARE v_phonenumber VARCHAR(20);
    DECLARE v_email VARCHAR(40);
    DECLARE v_instagram VARCHAR(2048);
    DECLARE v_telegram VARCHAR(2048);
    DECLARE v_whatsapp VARCHAR(2048);
    DECLARE v_snapchat VARCHAR(2048);
    DECLARE v_bio TEXT;
    DECLARE v_servicecatagory VARCHAR(15);
    DECLARE v_servicetype VARCHAR(15);


    -- Get the pending artist's data
    SELECT firstname, lastname, dob, gender,password, experiance, phonenumber,
           email, instagram, telegram, whatsapp, snapchat, bio,
           servicecatagory, servicetype
    INTO v_firstname, v_lastname, v_dob, v_gender,v_password,v_recoveryquestion,v_recoverynswer, v_experiance, v_phonenumber,
        v_email, v_instagram, v_telegram, v_whatsapp, v_snapchat, v_bio,
        v_servicecatagory, v_servicetype
    FROM pending_artist
    WHERE id = p_pending_artist_id;

    -- Insert verified artist's data into the main artist table
    INSERT INTO artist (firstname, lastname, dob, gender, experiance, bio)
    VALUES (
               COALESCE(v_firstname, ''),
               COALESCE(v_lastname, ''),
               v_dob,
               v_gender,
               v_experiance,
               COALESCE(v_bio, '')
           );

    -- Get the last inserted artist ID
    SET @last_artist_id = LAST_INSERT_ID();

    -- Insert artist's address into the artist_address table
    IF v_phonenumber IS NOT NULL AND v_phonenumber <> '' THEN
        INSERT INTO artist_address (artistid, type, link)
        VALUES (@last_artist_id, 'fas fa-phone contact-logo', v_phonenumber);
    END IF;

    -- Insert email if it's not null
    IF v_email IS NOT NULL AND v_email <> '' THEN
        INSERT INTO artist_address (artistid, type, link)
        VALUES (@last_artist_id, 'fas fa-envelope contact-logo', v_email);
    END IF;

    -- Insert Instagram if it's not null
    IF v_instagram IS NOT NULL AND v_instagram <> '' THEN
        INSERT INTO artist_address (artistid, type, link)
        VALUES (@last_artist_id, 'fab fa-instagram contact-logo', v_instagram);
    END IF;

    -- Insert Telegram if it's not null
    IF v_telegram IS NOT NULL AND v_telegram <> '' THEN
        INSERT INTO artist_address (artistid, type, link)
        VALUES (@last_artist_id, 'fab fa-telegram-plane contact-logo', v_telegram);
    END IF;

    -- Insert WhatsApp if it's not null
    IF v_whatsapp IS NOT NULL AND v_whatsapp <> '' THEN
        INSERT INTO artist_address (artistid, type, link)
        VALUES (@last_artist_id, 'fab fa-whatsapp contact-logo', v_whatsapp);
    END IF;

    -- Insert Snapchat if it's not null
    IF v_snapchat IS NOT NULL AND v_snapchat <> '' THEN
        INSERT INTO artist_address (artistid, type, link)
        VALUES (@last_artist_id, 'fab fa-snapchat-ghost contact-logo', v_snapchat);
    END IF;

    INSERT INTO artist_media (artistid, media_type, media_data)
    SELECT @last_artist_id, media_type, media_data
    FROM pending_artist_media
    WHERE pending_artist_id = p_pending_artist_id;

    -- Insert artist's service category and type into the service table
    INSERT IGNORE INTO service (servicecatagory, servicetype)
    VALUES (v_servicecatagory, v_servicetype);

    -- Get the service ID for the inserted service
    SET @last_service_id = LAST_INSERT_ID();

    -- Insert artist's service into the artist_services table
    INSERT INTO artist_services (artistid, serviceid, price)
    VALUES (@last_artist_id, @last_service_id, 0.0); -- 0.0 as a placeholder price

    -- Delete the pending artist record after verification
    DELETE FROM pending_artist WHERE id = p_pending_artist_id;
END //

CREATE PROCEDURE add_customer(
    IN c_firstname VARCHAR(20),
    IN c_lastname VARCHAR(20),
    IN c_dob DATE,
    IN c_gender VARCHAR(1),
    IN c_password VARBINARY(255),
    IN c_bio TEXT,
    IN c_picture MEDIUMBLOB,
    IN c_phone VARCHAR(2048),
    IN c_email VARCHAR(2048),
    IN c_whatsapp VARCHAR(2048),
    IN c_instagram VARCHAR(2048),
    IN c_telegram VARCHAR(2048),
    IN c_snapchat VARCHAR(2048)

)
BEGIN
    -- Insert into customer table
    INSERT INTO customer (
        firstname, lastname, dob, gender, password, bio, picture
    )
    VALUES (
               c_firstname, c_lastname, c_dob, c_gender, c_password, c_bio, c_picture
           );

    SET @customerid = LAST_INSERT_ID();

    IF c_phone IS NOT NULL THEN
        INSERT INTO customer_address (customerid, type, link)
        VALUES (@customerid, 'fas fa-phone contact-logo', c_phone);
    END IF;

    -- Insert email contact if provided
    IF c_email IS NOT NULL THEN
        INSERT INTO customer_address (customerid, type, link)
        VALUES (@customerid, 'fas fa-envelope contact-logo', c_email);
    END IF;
    -- Insert whatsapp contact if provided
    IF c_whatsapp IS NOT NULL THEN
        INSERT INTO customer_address (customerid, type, link)
        VALUES (@customerid, 'fab fa-whatsapp contact-logo', c_email);
    END IF;

    -- Insert Instagram contact if provided
    IF c_instagram IS NOT NULL THEN
        INSERT INTO customer_address (customerid, type, link)
        VALUES (@customerid, 'fab fa-instagram contact-logo', c_instagram);
    END IF;

    -- Insert Telegram contact if provided
    IF c_telegram IS NOT NULL THEN
        INSERT INTO customer_address (customerid, type, link)
        VALUES (@customerid, 'fab fa-telegram-plane contact-logo', c_telegram);
    END IF;

    -- Insert Snapchat contact if provided
    IF c_snapchat IS NOT NULL THEN
        INSERT INTO customer_address (customerid, type, link)
        VALUES (@customerid, 'fab fa-snapchat-ghost contact-logo', c_snapchat);
    END IF;

END //

CREATE PROCEDURE update_customer(
    IN c_customerid INT,
    IN c_firstname VARCHAR(20) ,
    IN c_lastname VARCHAR(20),
    IN c_dob DATE,
    IN c_gender VARCHAR(1),
    IN c_password VARBINARY(255),
    IN c_bio TEXT,
    IN c_picture MEDIUMBLOB,
    IN c_phone VARCHAR(2048),
    IN c_email VARCHAR(2048),
    IN c_whatsapp VARCHAR(2048),
    IN c_instagram VARCHAR(2048),
    IN c_telegram VARCHAR(2048),
    IN c_snapchat VARCHAR(2048)
)
BEGIN
    -- Update customer table using COALESCE to keep existing values if input is NULL
    UPDATE customer
    SET
        firstname = COALESCE(c_firstname, firstname),
        lastname = COALESCE(c_lastname, lastname),
        dob = COALESCE(c_dob, dob),
        gender = COALESCE(c_gender, gender),
        password = COALESCE(c_password, password),
        bio = COALESCE(c_bio, bio),
        picture = COALESCE(c_picture, picture)
    WHERE id = c_customerid;

    -- Update customer_address for each contact type, if provided
    IF c_phone IS NOT NULL THEN
        UPDATE customer_address SET link = c_phone WHERE customerid = c_customerid AND type = 'fas fa-phone contact-logo';
    END IF;

    IF c_email IS NOT NULL THEN
        UPDATE customer_address SET link = c_email WHERE customerid = c_customerid AND type = 'fas fa-envelope contact-logo';
    END IF;
    IF c_whatsapp IS NOT NULL THEN
        UPDATE customer_address SET link = c_whatsapp WHERE customerid = c_customerid AND type = 'fab fa-whatsapp contact-logo';
    END IF;

    IF c_instagram IS NOT NULL THEN
        UPDATE customer_address SET link = c_instagram WHERE customerid = c_customerid AND type = 'fab fa-instagram contact-logo';
    END IF;

    IF c_telegram IS NOT NULL THEN
        UPDATE customer_address SET link = c_telegram WHERE customerid = c_customerid AND type = 'fab fa-telegram-plane contact-logo';
    END IF;

    IF c_snapchat IS NOT NULL THEN
        UPDATE customer_address SET link = c_snapchat WHERE customerid = c_customerid AND type = 'fab fa-snapchat-ghost contact-logo';
    END IF;

END //

CREATE PROCEDURE delete_customer(
    IN c_customerid INT
)
BEGIN
    -- Delete customer from customer_address
    DELETE FROM customer_address WHERE customerid = c_customerid;

    -- Delete customer from customer table
    DELETE FROM customer WHERE id = c_customerid;
END //

CREATE OR REPLACE VIEW artistproject AS
SELECT
    a.id as artistid,p.id as projectid,
    p.projectname AS project_names,
    p.status AS project_statuses,
    pa.role as artistrole
From artist as a
         LEFT JOIN project_artists as pa on a.id = pa.artistid
         LEFT JOIN project as p ON pa.projectid=p.id
;
create view artistminiprofile as
SELECT
    artist.id AS ID,
    CONCAT(artist.firstname, ' ', artist.lastname) AS 'Full Name',
    artist.picture as Picture,
    GROUP_CONCAT(DISTINCT service.servicetype SEPARATOR ', ') AS Services,
    GROUP_CONCAT(DISTINCT service.servicecatagory SEPARATOR ', ') AS Service_categories,
    SUM(artist_rating.stars) / COUNT(artist_rating.stars) AS Rating
FROM
    artist
        LEFT  JOIN
    artist_rating ON artist.id = artist_rating.artistid
        JOIN
    artist_services ON artist.id = artist_services.artistid
        JOIN
    service ON artist_services.serviceid = service.id
GROUP BY
    artist.id;

CREATE OR REPLACE VIEW artistprofile AS
SELECT
    a.id AS artistid,
    CONCAT(a.firstname, ' ', a.lastname) AS 'Full Name',
    IFNULL(SUM(ar.stars) / COUNT(ar.stars), 0) AS Rating,  -- Calculate average rating
    a.bio AS bio,
    a.coverpicture as Coverpicture

FROM
    artist as a
        LEFT JOIN artist_rating as ar ON a.id = ar.artistid
GROUP BY    a.id;

CREATE OR REPLACE VIEW artistaddress
AS
SELECT
    a.id as artistid,
    ad.type as Class,
    ad.link as href

from artist as a
         LEFT JOIN artist_address as ad ON a.id = ad.artistid
GROUP BY a.id;

CREATE OR REPLACE VIEW artistservices AS
SELECT
    a.id as artistid,s.servicecatagory as 'Service Category', s.servicetype as 'Service Type'

from artist as a
         -- Join artist services with services table
         LEFT JOIN artist_services  as aser ON a.id = aser.artistid
         LEFT JOIN service as s ON aser.serviceid = s.id
group by a.id;

CREATE OR REPLACE VIEW artistmedia AS
SELECT
    a.id,am.id as mediaid, am.media_data as File, am.media_type 'File Type'
from artist as a
         LEFT JOIN artist_media as am ON a.id = am.artistid
GROUP BY a.id;



create or replace view artistpassword as
select artist.id as ID, artist.password , artist_address.link from artist
                                                                       join artist_address on artist.id = artist_address.artistid and artist_address.type='fas fa-envelope contact-logo';

create or replace view customerpassword as
select customer.id, customer.password, customer_address.link from customer
                                                                      join customer_address on customer.id = customer_address.customerid and customer_address.type='fas fa-envelope contact-logo';


create or replace view customerprofile as
select c.id as ID, CONCAT(c.firstname, ' ', c.lastname) AS 'Full Name',
       TIMESTAMPDIFF(YEAR, c.dob, CURDATE()) as Age,
       c.gender as Gender, c.bio as Bio, c.picture as Picture
from customer as c;

CREATE OR REPLACE VIEW customeraddress
AS
SELECT
    c.id as ID,
    cd.type as class,
    cd.link as href

from customer as c
         LEFT JOIN customer_address as cd ON c.id = cd.customerid
GROUP BY c.id;

DELIMITER //

CREATE procedure selectartist(
    IN a_id int
)
begin
    SELECT * FROM artistprofile
    where artistid=a_id;
    SELECT * FROM artistaddress
    WHERE artistid=a_id;
    SELECT * FROM artistservices
    WHERE artistid=a_id;
    SELECT * FROM artistmedia
    WHERE id=a_id;
    SELECT * FROM artistproject
    WHERE artistid=a_id;
end//

CREATE PROCEDURE updateartist(
    IN uartistid INT,
    IN ufirstname VARCHAR(20),
    IN ulastname VARCHAR(20),
    IN udob DATE,
    IN uexperiance INT,
    IN upassword VARBINARY(255),
    IN ubio TEXT,
    IN upicture MEDIUMBLOB,
    IN ucoverpicture MEDIUMBLOB
)
BEGIN

    UPDATE artist
    SET
        firstname= COALESCE(ufirstname,firstname),
        lastname= COALESCE(ulastname,lastname),
        dob= COALESCE(udob,dob),
        experiance= COALESCE(uexperiance,experiance),
        password= COALESCE(upassword,password),
        bio= COALESCE(ubio,bio),
        picture= COALESCE(upicture,picture),
        coverpicture= COALESCE(ucoverpicture,coverpicture)
    WHERE id=uartistid;

END//

CREATE PROCEDURE updateartistaddress(
    IN p_artistid INT,
    IN p_phone VARCHAR(2048),
    IN p_email VARCHAR(2048),
    IN p_whatsapp VARCHAR(2048),
    IN p_telegram VARCHAR(2048),
    IN p_instagram VARCHAR(2048),
    IN p_snapchat VARCHAR(2048)
)
BEGIN
    -- Update phone number if provided
    IF p_phone IS NOT NULL THEN
        UPDATE artist_address
        SET link = p_phone
        WHERE artistid = p_artistid AND type = 'fas fa-phone contact-logo';
    END IF;

    -- Update email if provided
    IF p_email IS NOT NULL THEN
        UPDATE artist_address
        SET link = p_email
        WHERE artistid = p_artistid AND type = 'fas fa-envelope contact-logo';
    END IF;

    -- Update WhatsApp if provided
    IF p_whatsapp IS NOT NULL THEN
        UPDATE artist_address
        SET link = p_whatsapp
        WHERE artistid = p_artistid AND type = 'fab fa-whatsapp contact-logo';
    END IF;

    -- Update Telegram if provided
    IF p_telegram IS NOT NULL THEN
        UPDATE artist_address
        SET link = p_telegram
        WHERE artistid = p_artistid AND type = 'fab fa-telegram-plane contact-logo';
    END IF;

    -- Update Instagram if provided
    IF p_instagram IS NOT NULL THEN
        UPDATE artist_address
        SET link = p_instagram
        WHERE artistid = p_artistid AND type = 'fab fa-instagram contact-logo';
    END IF;

    -- Update Snapchat if provided
    IF p_snapchat IS NOT NULL THEN
        UPDATE artist_address
        SET link = p_snapchat
        WHERE artistid = p_artistid AND type = 'fab fa-snapchat-ghost contact-logo';
    END IF;
END //

CREATE PROCEDURE deleteartistmedia(
    IN p_media_id INT,
    IN p_artistid INT
)
BEGIN
    -- Check if the specified media exists for the artist
    IF EXISTS (SELECT 1 FROM artist_media WHERE id = p_media_id AND artistid = p_artistid) THEN
        -- Delete the media entry
        DELETE FROM artist_media
        WHERE id = p_media_id AND artistid = p_artistid;
        SELECT 'Media deleted successfully.' AS message;
    ELSE
        -- If no media is found, return a message
        SELECT 'Media not found or already deleted.' AS message;
    END IF;
END //

CREATE PROCEDURE updateartistmedia(
    IN p_media_id INT,
    IN p_artistid INT,
    IN p_media_type ENUM('image', 'video'),
    IN p_media_data LONGBLOB
)
BEGIN
    -- Check if the specified media exists for the given artist and media ID
    IF EXISTS (SELECT 1 FROM artist_media WHERE id = p_media_id AND artistid = p_artistid AND media_type = p_media_type) THEN
        -- Update the media data if it exists
        UPDATE artist_media
        SET media_data = COALESCE(p_media_data, media_data)
        WHERE id = p_media_id AND artistid = p_artistid AND media_type = p_media_type;
    ELSE
        -- If no matching media is found, insert a new record
        INSERT INTO artist_media (artistid, media_type, media_data)
        VALUES (p_artistid, p_media_type, p_media_data);
    END IF;
END //


CREATE PROCEDURE deleteartistaddress(
    IN p_artistid INT,
    IN p_phone BOOLEAN,
    IN p_email BOOLEAN,
    IN p_whatsapp BOOLEAN,
    IN p_telegram BOOLEAN,
    IN p_instagram BOOLEAN,
    IN p_snapchat BOOLEAN
)
BEGIN
    -- Delete phone number if requested
    IF p_phone THEN
        DELETE FROM artist_address
        WHERE artistid = p_artistid AND type = 'fas fa-phone contact-logo';
    END IF;

    -- Delete email if requested
    IF p_email THEN
        DELETE FROM artist_address
        WHERE artistid = p_artistid AND type = 'fas fa-envelope contact-logo';
    END IF;

    -- Delete WhatsApp if requested
    IF p_whatsapp THEN
        DELETE FROM artist_address
        WHERE artistid = p_artistid AND type = 'fab fa-whatsapp contact-logo';
    END IF;

    -- Delete Telegram if requested
    IF p_telegram THEN
        DELETE FROM artist_address
        WHERE artistid = p_artistid AND type = 'fab fa-telegram-plane contact-logo';
    END IF;

    -- Delete Instagram if requested
    IF p_instagram THEN
        DELETE FROM artist_address
        WHERE artistid = p_artistid AND type = 'fab fa-instagram contact-logo';
    END IF;

    -- Delete Snapchat if requested
    IF p_snapchat THEN
        DELETE FROM artist_address
        WHERE artistid = p_artistid AND type = 'fab fa-snapchat-ghost contact-logo';
    END IF;
END //
