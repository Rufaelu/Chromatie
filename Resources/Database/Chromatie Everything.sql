create table artist
(
    id           int auto_increment
        primary key,
    firstname    varchar(20)  not null,
    lastname     varchar(20)  null,
    dob          date         not null,
    gender       varchar(1)   not null,
    experience   int          null,
    password     varchar(500) null,
    bio          text         null,
    picture      text         null,
    coverpicture text         null
);

create table artist_address
(
    artistid int                                                                      not null,
    type     enum ('phone', 'email', 'instagram', 'telegram', 'whatsapp', 'snapchat') not null,
    link     varchar(2048)                                                            null,
    primary key (artistid, type),
    constraint artist_address_ibfk_1
        foreign key (artistid) references artist (id)
);

create table artist_media
(
    id         int auto_increment
        primary key,
    artistid   int         null,
    media_type varchar(10) not null,
    media_path text        not null,
    constraint artist_media_ibfk_1
        foreign key (artistid) references artist (id)
);


create table artist_rating
(
    id       int auto_increment
        primary key,
    artistid int  not null,
    stars    int  null,
    comment  text null,
    constraint artist_rating_ibfk_1
        foreign key (artistid) references artist (id)
);
select * from customer;


create table customer
(
    id        int auto_increment
        primary key,
    firstname varchar(20)  not null,
    lastname  varchar(20)  null,
    dob       date         not null,
    gender    varchar(1)   not null,
    password  varchar(500) null,
    bio       text         null,
    picture   text         null
);
select * from visited;
delete from visited;
create table customer_address
(
    customerid int                     not null,
    type       enum ('email', 'phone') not null,
    link       varchar(2048)           null,
    constraint customerid
        unique (customerid, type),
    constraint customer_address_ibfk_1
        foreign key (customerid) references customer (id)
);

create table project
(
    id          int auto_increment
        primary key,
    projectname varchar(100)                   not null,
    description text                           null,
    status      enum ('completed', 'on going') null,
    artistid    int                            null,
    constraint project_ibfk_1
        foreign key (artistid) references artist (id)
);


create table service
(
    id              int auto_increment
        primary key,
    servicetype     varchar(15) null,
    servicecatagory varchar(15) null
);

create table artist_services
(
    artistid  int    not null,
    serviceid int    not null,
    price     double null,
    primary key (artistid, serviceid),
    constraint artist_services_ibfk_1
        foreign key (artistid) references artist (id),
    constraint artist_services_ibfk_2
        foreign key (serviceid) references service (id)
);


create table visited
(
    id          int auto_increment
        primary key,
    visitorid   int                                  not null,
    artistid    int                                  not null,
    visitortype varchar(9)                           not null,
    `when`      datetime default current_timestamp() null,
    stat        enum ('read', 'notread')             null,
    constraint visited_ibfk_1
        foreign key (artistid) references artist (id)
);

call DeleteArtist(10);
call selectartist(10);
select * from artist_address where artistid=10;

create view artistaddress as
select `a`.`id` AS `artistid`, `ad`.`type` AS `Class`, `ad`.`link` AS `href`
from (`chromatie`.`artist` `a` left join `chromatie`.`artist_address` `ad` on (`a`.`id` = `ad`.`artistid`));

create view artistmedia as
select `a`.`id` AS `id`, `am`.`id` AS `mediaid`, `am`.`media_path` AS `Path`, `am`.`media_type` AS `File Type`
from (`chromatie`.`artist` `a` left join `chromatie`.`artist_media` `am` on (`a`.`id` = `am`.`artistid`));

create view artistpassword as
select `chromatie`.`artist`.`id`           AS `ID`,
       `chromatie`.`artist`.`password`     AS `password`,
       `chromatie`.`artist_address`.`link` AS `email`
from (`chromatie`.`artist` join `chromatie`.`artist_address`
      on (`chromatie`.`artist`.`id` = `chromatie`.`artist_address`.`artistid` and
          `chromatie`.`artist_address`.`type` = 'email'));

create view artistprofile as
select `a`.`id`                                     AS `artistid`,
       concat(`a`.`firstname`, ' ', `a`.`lastname`) AS `FullName`,
       `a`.`bio`                                    AS `bio`,
       `a`.`picture`                                AS `picture`,
       `a`.`coverpicture`                           AS `Coverpicture`
from `chromatie`.`artist` `a`
group by `a`.`id`;

create view artistservices as
select `a`.`id`                AS `artistid`,
       `s`.`servicecatagory`   AS `Service Category`,
       `s`.`servicetype`       AS `Service Type`,
       min(`aser`.`serviceid`) AS `MainServiceId`
from ((`chromatie`.`artist` `a` left join `chromatie`.`artist_services` `aser`
       on (`a`.`id` = `aser`.`artistid`)) left join `chromatie`.`service` `s` on (`aser`.`serviceid` = `s`.`id`))
group by `a`.`id`;

create view customeraddress as
select `c`.`id` AS `ID`, `cd`.`type` AS `class`, `cd`.`link` AS `href`
from (`chromatie`.`customer` `c` left join `chromatie`.`customer_address` `cd` on (`c`.`id` = `cd`.`customerid`))
group by `c`.`id`;

create view customerpassword as
select `chromatie`.`customer`.`id`           AS `id`,
       `chromatie`.`customer`.`password`     AS `password`,
       `chromatie`.`customer_address`.`link` AS `email`
from (`chromatie`.`customer` join `chromatie`.`customer_address`
      on (`chromatie`.`customer`.`id` = `chromatie`.`customer_address`.`customerid` and
          `chromatie`.`customer_address`.`type` = 'email'));

create view customerview as
select `c`.`id`                                     AS `ID`,
       concat(`c`.`firstname`, ' ', `c`.`lastname`) AS `FullName`,
       `c`.`dob`                                    AS `dob`,
       `c`.`gender`                                 AS `Gender`,
       `c`.`bio`                                    AS `Bio`,
       `c`.`picture`                                AS `Picture`
from `chromatie`.`customer` `c`;

SELECT * FROM visitor_info WHERE artistid = 1 ;


create or replace view visitor_info as
select `v`.`id`                                                                                                   AS `visit_id`,
       `v`.`artistid`                                                                                             AS `artistid`,
       `v`.`when`                                                                                                 AS `when`,
       `v`.`stat`                                                                                                 AS `stat`,
       case
           when `v`.`visitortype` = 'customer' then `c`.`id`
           when `v`.`visitortype` = 'artist'
               then `a`.`id` end                                                                                  AS `visitor_id`,
       case
           when `v`.`visitortype` = 'customer' then concat(`c`.`firstname`, ' ', `c`.`lastname`)
           when `v`.`visitortype` = 'artist'
               then concat(`a`.`firstname`, ' ', `a`.`lastname`) end                                              AS `visitor_fullname`,
       case
           when `v`.`visitortype` = 'customer' then `c`.`picture`
           when `v`.`visitortype` = 'artist'
               then `a`.`picture` end                                                                             AS `visitor_picture`,
       `v`.`visitortype`                                                                                          AS `visitortype`
from ((`chromatie`.`visited` `v` left join `chromatie`.`customer` `c`
       on (`v`.`visitorid` = `c`.`id` and `v`.`visitortype` = 'customer')) left join `chromatie`.`artist` `a`
      on (`v`.`visitorid` = `a`.`id` and `v`.`visitortype` = 'artist'))
order by `v`.id DESC ;

SELECT * From customer;

CREATE OR REPLACE VIEW dashboard_stats AS
SELECT 
    (SELECT COUNT(*) FROM artist) AS total_artists,
    (SELECT COUNT(*) FROM customer) AS total_customers,
    (SELECT COUNT(*) FROM artist_services) AS total_services;

select * from visitor_info;
create procedure CheckUserExistsByEmail(IN email varchar(2048))
BEGIN
    DECLARE artist_count INT DEFAULT 0;
    DECLARE customer_count INT DEFAULT 0;

    -- Count the number of artist addresses with the given email
    select COUNT(*) into artist_count from artist_address where link=email and type='email';


    -- Count the number of customer addresses with the given email
    SELECT COUNT(*) INTO customer_count
    FROM customer_address
    WHERE link = email AND type = 'email';

    -- Select 'true' if either artist or customer exists, otherwise 'false'
    IF artist_count > 0 OR customer_count > 0 THEN
        SELECT 'true' AS found;
    ELSE
        SELECT 'false' AS found;
    END IF;

END;

create procedure DeleteArtist(IN p_artistid int)
BEGIN
    -- Delete related addresses and media for the artist
    DELETE FROM artist_address WHERE artistid = p_artistid;
    DELETE FROM artist_media WHERE artistid = p_artistid;
    DELETE FROM artist_services WHERE artistid=p_artistid;
    DELETE FROM visited WHERE artistid=p_artistid;
    -- Delete the artist
    DELETE FROM artist WHERE id = p_artistid;

    -- Optional: Check if the artist was successfully deleted
    IF ROW_COUNT() = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Artist not found or could not be deleted.';
    END IF;
END;

create procedure DeleteArtistService(IN p_artistid int, IN p_serviceid int)
BEGIN
    -- Check if the service exists for the given artist
    IF EXISTS (
        SELECT 1
        FROM artist_services
        WHERE artistid = p_artistid AND serviceid = p_serviceid
    ) THEN
        -- If it exists, delete the service
        DELETE FROM artist_services
        WHERE artistid = p_artistid AND serviceid = p_serviceid;
    ELSE
        -- Optionally, you can raise an error or handle it as needed
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Service not found for the specified artist.';
    END IF;
END;

create procedure GetArtistProfileCards()
BEGIN
    -- Select all artists, their first service, all services, and the visit count
    SELECT
        a.id AS ArtistID,
        CONCAT(a.firstname, ' ', a.lastname) AS FullName,
        a.picture AS Picture,
        -- Get the first service and its category
        (SELECT
             s.servicetype
         FROM
             service s
                 JOIN artist_services a_s ON s.id = a_s.serviceid
         WHERE
             a_s.artistid = a.id
         ORDER BY
             s.id
         LIMIT 1) AS FirstService,
        (SELECT
             s.servicecatagory
         FROM
             service s
                 JOIN artist_services a_s ON s.id = a_s.serviceid
         WHERE
             a_s.artistid = a.id
         ORDER BY
             s.id
         LIMIT 1) AS FirstServiceCategory,
        GROUP_CONCAT(DISTINCT s.servicetype ORDER BY s.id ASC) AS AllServices,
        COUNT(v.id) AS VisitCount -- Counting visits for each artist
    FROM
        artist a
            LEFT JOIN
        artist_services a_s ON a.id = a_s.artistid
            LEFT JOIN
        service s ON a_s.serviceid = s.id
            LEFT JOIN
        visited v ON a.id = v.artistid -- Join with the visited table to count visits
    GROUP BY
        a.id; -- Grouping by artist ID to aggregate services and visits
END;

create procedure UpdateArtistService(IN p_artistid int, IN p_servicetype varchar(15),
                                     IN p_servicecategory varchar(15))
BEGIN
    DECLARE v_serviceid INT;
    select * from artist_address;
-- Check if the service exists
    IF p_servicecategory IS NOT NULL THEN
        SELECT id INTO v_serviceid
        FROM service
        WHERE servicetype = p_servicetype
          AND servicecatagory = p_servicecategory
        LIMIT 1;
    ELSE
        SELECT id INTO v_serviceid
        FROM service
        WHERE servicetype = p_servicetype
        LIMIT 1;
    END IF;

    -- If the service doesn't exist, insert it
    IF v_serviceid IS NULL THEN
        IF p_servicecategory IS NOT NULL THEN
            INSERT INTO service (servicetype, servicecatagory)
            VALUES (p_servicetype, p_servicecategory);
        ELSE
            INSERT INTO service (servicetype, servicecatagory)
            VALUES (p_servicetype, 'default'); -- or any default you wish
        END IF;

        -- Get the new service ID
        SET v_serviceid = LAST_INSERT_ID();
    END IF;

    -- Update or insert the artist service
    INSERT IGNORE INTO artist_services (artistid, serviceid)
    VALUES (p_artistid, v_serviceid);

END;
create procedure add_customer(IN c_firstname varchar(20), IN c_lastname varchar(20),
                              IN c_dob date, IN c_gender varchar(1),
                              IN c_password varchar(255), IN c_bio text,
                              IN c_picture text, IN c_phone varchar(2048),
                              IN c_email varchar(2048))
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
        VALUES (@customerid, 'phone', c_phone);
    END IF;

    -- Insert email contact if provided
    IF c_email IS NOT NULL THEN
        INSERT INTO customer_address (customerid, type, link)
        VALUES (@customerid, 'email', c_email);
    END IF;

END;

create procedure add_visit(IN p_visitorid int, IN p_artistid int, IN p_visitortype varchar(9))
BEGIN
    INSERT INTO visited (visitorid, artistid, visitortype)
    VALUES (p_visitorid, p_artistid, p_visitortype);
END;

create procedure check_password(IN p_email varchar(255), IN p_password varchar(500))
BEGIN
    DECLARE v_id INT;
    DECLARE v_account_type VARCHAR(10);
    DECLARE v_stored_password text;
-- Check if the email exists in the artistpassword view
    SELECT ID, password INTO v_id, v_stored_password
    FROM artistpassword
    WHERE email = p_email;

-- If a match is found, check the password directly
    IF v_id IS NOT NULL THEN
        IF v_stored_password = p_password THEN
            SET v_account_type = 'artist';
            SELECT v_account_type AS account_type, v_id AS account_id;
        END IF;
    END IF;

-- Reset variables for the customer check
    SET v_id = NULL;
    SET v_stored_password = NULL;

-- Check if the email exists in the customerpassword view
    SELECT id, password INTO v_id, v_stored_password
    FROM customerpassword
    WHERE email = p_email;

-- If a match is found, check the password directly
    IF v_id IS NOT NULL THEN
        IF v_stored_password = p_password THEN
            SET v_account_type = 'customer';
            SELECT v_account_type AS account_type, v_id AS account_id;
        END IF;
    END IF;

-- If no match is found, return a message indicating failure
    SELECT 'invalid' AS account_type, NULL AS account_id;
END;

create procedure delete_customer(IN c_customerid int)
BEGIN
    -- Delete customer from customer_address
    DELETE FROM customer_address WHERE customerid = c_customerid;
    delete FROM visited where visitortype='customer' and visitorid=c_customerid;

-- Delete customer from customer table
    DELETE FROM customer WHERE id = c_customerid;
END;

create procedure deleteartistaddress(IN p_artistid int, IN p_phone tinyint(1),
                                     IN p_email tinyint(1), IN p_whatsapp tinyint(1),
                                     IN p_telegram tinyint(1), IN p_instagram tinyint(1),
                                     IN p_snapchat tinyint(1))
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
END;

create  procedure deleteartistmedia(IN p_media_id int)
BEGIN
    -- Check if the specified media exists
    IF EXISTS (SELECT 1 FROM artist_media WHERE id = p_media_id) THEN
        -- Delete the media entry
        DELETE FROM artist_media WHERE id = p_media_id;
        SELECT 'Media deleted successfully.' AS message;
    ELSE
        -- If no media is found, return a message
        SELECT 'Media not found or already deleted.' AS message;
    END IF;
END;

create procedure getCustomerDetails(IN p_customer_id int)
BEGIN
    -- Select from customerview
    SELECT * FROM customerview WHERE ID = p_customer_id;

-- Select from customeraddress
    SELECT * FROM customeraddress WHERE ID = p_customer_id;
END;

create  procedure insert_artist(IN p_firstname varchar(20), IN p_lastname varchar(20),
                                IN p_dob date, IN p_gender varchar(1),
                                IN p_password varchar(500), IN p_experience int,
                                IN p_phonenumber varchar(20), IN p_email varchar(40),
                                IN p_instagram varchar(2048), IN p_telegram varchar(2048),
                                IN p_whatsapp varchar(2048), IN p_snapchat varchar(2048),
                                IN p_bio text, IN p_servicecategory varchar(15),
                                IN p_servicetype varchar(15), IN p_media_type varchar(10),
                                IN p_media_path text, OUT p_artist_id int)
BEGIN
    -- Step 1: Insert into the artist table
    INSERT INTO artist (
        firstname, lastname, dob,password, gender, experience, bio,picture
    ) VALUES (
                 COALESCE(p_firstname, ''),
                 COALESCE(p_lastname, ''),
                 p_dob,
                 p_password,
                 p_gender,
                 p_experience,
                 COALESCE(p_bio, ''),
                 p_media_path
             );

-- Step 2: Get the last inserted artist ID
    SET p_artist_id = LAST_INSERT_ID();



    -- Step 5: check if service already exit
    SELECT id INTO @service_id
    FROM service
    WHERE servicecatagory = p_servicecategory AND servicetype = p_servicetype;

-- Step 5.1: Insert into the service table if not already present
    IF @service_id IS NULL THEN
        INSERT INTO service (servicecatagory, servicetype)
        VALUES (p_servicecategory, p_servicetype);

        SET @service_id = LAST_INSERT_ID();
    end if;
    -- Step 7: Insert into artist_services table
    INSERT INTO artist_services (artistid, serviceid, price)
    VALUES (p_artist_id, @service_id, 0.0); -- Using 0.0 as a placeholder price
END;

create procedure insertmedia(IN p_artistid int, IN p_media_type text, IN p_media_path text)
BEGIN
    -- Insert a new media record
    INSERT INTO artist_media (artistid, media_type, media_path)
    VALUES (p_artistid, p_media_type, p_media_path);
END;



create procedure selectartist(IN a_id int)
BEGIN
    -- Select the artist profile
    SELECT * FROM artistprofile WHERE artistid = a_id;

    -- Select the artist address
    SELECT * FROM artistaddress WHERE artistid = a_id;

    -- Select the main service (the first service inserted)
    SELECT aser.serviceid AS `Service ID`,
           s.servicecatagory AS `Service Category`,
           s.servicetype AS `Service Type`
    FROM artist_services aser
             JOIN service s ON aser.serviceid = s.id
    WHERE aser.artistid = a_id
    ORDER BY aser.serviceid ASC -- Assuming `id` is the order of insertion
    LIMIT 1; -- Get the first service

    -- Select all other services
    SELECT aser.serviceid AS `Service ID`,
           s.servicecatagory AS `Service Category`,
           s.servicetype AS `Service Type`
    FROM artist_services aser
             JOIN service s ON aser.serviceid = s.id
    WHERE aser.artistid = a_id
    ORDER BY aser.serviceid ASC; -- Select the rest of the services

    -- Select the artist media
    SELECT * FROM artistmedia WHERE id = a_id;
END;

create procedure updateArtistBio(IN p_artistid int, IN p_bio text)
BEGIN
    -- Update the bio of the artist based on the provided artist ID
    UPDATE artist
    SET bio = COALESCE(p_bio, bio)
    WHERE id = p_artistid;
END;

create  procedure update_customer(IN c_customerid int, IN c_firstname varchar(20),
                                  IN c_lastname varchar(20), IN c_dob date,
                                  IN c_gender varchar(1), IN c_bio text, IN c_picture text,
                                  IN c_phone varchar(2048), IN c_email varchar(2048))
BEGIN
    -- Update customer table using COALESCE to keep existing values if input is NULL
    UPDATE customer
    SET
        firstname = COALESCE(c_firstname, firstname),
        lastname = COALESCE(c_lastname, lastname),
        dob = COALESCE(c_dob, dob),
        gender = COALESCE(c_gender, gender),
        bio = COALESCE(c_bio, bio),
        picture = COALESCE(c_picture, picture)
    WHERE id = c_customerid;

-- Update or insert phone number
    IF c_phone IS NOT NULL THEN
        -- Check if the phone record exists
        IF EXISTS (SELECT 1 FROM customer_address WHERE customerid = c_customerid AND type = 'phone') THEN
            -- Update the existing phone number
            UPDATE customer_address
            SET link = c_phone
            WHERE customerid = c_customerid AND type = 'phone';
        ELSE
            -- Insert a new phone number record
            INSERT INTO customer_address (customerid, link, type)
            VALUES (c_customerid, c_phone, 'phone');
        END IF;
    END IF;

    IF c_email IS NOT NULL THEN
        UPDATE customer_address SET link = c_email WHERE customerid = c_customerid AND type = 'email';
    END IF;

END;

create  procedure updateartist(IN uartistid int, IN ufirstname varchar(20),
                               IN ulastname varchar(20), IN udob date, IN uexperience int,
                               IN upassword varbinary(255), IN ubio text,
                               IN upicture mediumblob, IN ucoverpicture mediumblob)
BEGIN

    UPDATE artist
    SET
        firstname= COALESCE(ufirstname,firstname),
        lastname= COALESCE(ulastname,lastname),
        dob= COALESCE(udob,dob),
        experience= COALESCE(uexperience,experience),
        password= COALESCE(upassword,password),
        bio= COALESCE(ubio,bio),
        picture= COALESCE(upicture,picture),
        coverpicture= COALESCE(ucoverpicture,coverpicture)
    WHERE id=uartistid;

END;

create  procedure updateartistaddress(IN p_artistid int, IN p_phone varchar(2048),
                                      IN p_email varchar(2048), IN p_whatsapp varchar(2048),
                                      IN p_telegram varchar(2048), IN p_instagram varchar(2048),
                                      IN p_snapchat varchar(2048))
BEGIN
    -- Update or Insert phone number
    IF p_phone IS NOT NULL THEN
        UPDATE artist_address
        SET link = p_phone
        WHERE artistid = p_artistid AND type = 'phone';

        -- If no rows were updated, insert a new row
        IF ROW_COUNT() = 0 THEN
            INSERT INTO artist_address (artistid, type, link)
            VALUES (p_artistid, 'phone', p_phone);
        END IF;
    END IF;

    -- Update or Insert email
    IF p_email IS NOT NULL THEN
        UPDATE artist_address
        SET link = p_email
        WHERE artistid = p_artistid AND type = 'email';

        IF ROW_COUNT() = 0 THEN
            INSERT INTO artist_address (artistid, type, link)
            VALUES (p_artistid, 'email', p_email);
        END IF;
    END IF;

    -- Update or Insert WhatsApp
    IF p_whatsapp IS NOT NULL THEN
        UPDATE artist_address
        SET link = p_whatsapp
        WHERE artistid = p_artistid AND type = 'whatsapp';

        IF ROW_COUNT() = 0 THEN
            INSERT INTO artist_address (artistid, type, link)
            VALUES (p_artistid, 'whatsapp', p_whatsapp);
        END IF;
    END IF;

    -- Update or Insert Telegram
    IF p_telegram IS NOT NULL THEN
        UPDATE artist_address
        SET link = p_telegram
        WHERE artistid = p_artistid AND type = 'telegram';

        IF ROW_COUNT() = 0 THEN
            INSERT INTO artist_address (artistid, type, link)
            VALUES (p_artistid, 'telegram', p_telegram);
        END IF;
    END IF;

    -- Update or Insert Instagram
    IF p_instagram IS NOT NULL THEN
        UPDATE artist_address
        SET link = p_instagram
        WHERE artistid = p_artistid AND type = 'instagram';

        IF ROW_COUNT() = 0 THEN
            INSERT INTO artist_address (artistid, type, link)
            VALUES (p_artistid, 'instagram', p_instagram);
        END IF;
    END IF;

    -- Update or Insert Snapchat
    IF p_snapchat IS NOT NULL THEN
        UPDATE artist_address
        SET link = p_snapchat
        WHERE artistid = p_artistid AND type = 'snapchat';

        IF ROW_COUNT() = 0 THEN
            INSERT INTO artist_address (artistid, type, link)
            VALUES (p_artistid, 'snapchat', p_snapchat);
        END IF;
    END IF;
END;

create procedure updatepassword(IN email varchar(255), IN newpassword varbinary(255))
BEGIN
    DECLARE artistid INT;
    DECLARE customerid INT;

    -- Try to find artist by email
    SELECT a.artistid
    INTO artistid
    FROM artist_address a
    WHERE a.type = 'email' AND a.link = email
    LIMIT 1;

-- Try to find customer by email
    SELECT c.customerid
    INTO customerid
    FROM customer_address c
    WHERE c.type = 'email' AND c.link = email
    LIMIT 1;

    IF artistid IS NULL AND customerid IS NULL THEN
        Select 'user not found';

    end if;
    -- Check if artistid is found and customerid is null
    IF artistid IS NOT NULL AND customerid IS NULL THEN
        UPDATE artist
        SET password = newpassword
        WHERE id = artistid;
        Select 'password changed';


-- Check if customerid is found and artistid is null
    ELSEIF customerid IS NOT NULL AND artistid IS NULL THEN
        UPDATE customer
        SET password = newpassword
        WHERE id = customerid;
        Select 'password changed';
-- If neither or both are found, raise an error

    END IF;
END;

