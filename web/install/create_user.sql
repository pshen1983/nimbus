CREATE DATABASE NIMBUS_USER;

########################################################################################################

CREATE TABLE NIMBUS_USER.USER
(
	UID INT UNSIGNED NOT NULL AUTO_INCREMENT,
	EMAIL VARCHAR(41) NOT NULL,
	PASSWD VARCHAR(41),
	FULLNAME NVARCHAR(41) NOT NULL,
	PIC MEDIUMBLOB,
	PIC_UP DATETIME,
	UNAME NVARCHAR(13),
	RTIME DATETIME NOT NULL,
	REG_TYPE ENUM('E', 'C') NOT NULL,
	DESCRIPTION TEXT,
	COMPANY TINYTEXT,
	TITLE TINYTEXT,
	CELL VARCHAR(15),
	WEIBO NVARCHAR(41),

	PRIMARY KEY (UID)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE UNIQUE INDEX EMAIL_INDEX ON NIMBUS_USER.USER (EMAIL(40));
CREATE INDEX FULLNAME_INDEX ON NIMBUS_USER.USER (FULLNAME(40));
CREATE INDEX UNAME_INDEX ON NIMBUS_USER.USER (UNAME(12));

########################################################################################################

CREATE TABLE NIMBUS_USER.USER_T
(
	CID INT UNSIGNED NOT NULL,
	UID INT UNSIGNED NOT NULL,
	EMAIL VARCHAR(41),
	FULLNAME NVARCHAR(41) NOT NULL,
	PIC MEDIUMBLOB,
	PIC_UP DATETIME,
	CTIME DATETIME NOT NULL,
	DESCRIPTION TEXT,
	COMPANY TINYTEXT,
	TITLE TINYTEXT,
	CELL VARCHAR(15) NOT NULL,
	WEIBO NVARCHAR(41),
	
	FOREIGN KEY (UID) REFERENCES NIMBUS_USER.USER (UID),
	UNIQUE (CID, UID)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE INDEX CID_INDEX ON NIMBUS_USER.USER_T (CID);
CREATE INDEX CTIME_INDEX ON NIMBUS_USER.USER_T (CTIME);

########################################################################################################

CREATE TABLE NIMBUS_USER.INVT
(
	ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
	CID INT UNSIGNED NOT NULL,
	UID INT UNSIGNED NOT NULL,
	CELL VARCHAR(15) NOT NULL,
	EMAIL VARCHAR(41),
	FULLNAME NVARCHAR(41) NOT NULL,
	COMPANY TINYTEXT,
	TITLE TINYTEXT,
	STIME DATETIME,
	
	PRIMARY KEY (ID),
	FOREIGN KEY (UID) REFERENCES NIMBUS_USER.USER (UID)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE INDEX CID_INDEX ON NIMBUS_USER.INVT (CID);

########################################################################################################

CREATE TABLE NIMBUS_USER.CONTACT
(
	CID INT UNSIGNED NOT NULL AUTO_INCREMENT,
	METHOD TINYTEXT NOT NULL,
	INFO TINYTEXT,
	UID INT UNSIGNED NOT NULL,

	PRIMARY KEY (CID),
	FOREIGN KEY (UID) REFERENCES NIMBUS_USER.USER (UID)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

########################################################################################################

CREATE TABLE NIMBUS_USER.FOCUS
(
	UID INT UNSIGNED NOT NULL,
	FID INT UNSIGNED NOT NULL,

	FOREIGN KEY (UID) REFERENCES NIMBUS_USER.USER (UID)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

########################################################################################################

CREATE TABLE NIMBUS_USER.FANS
(
	UID INT UNSIGNED NOT NULL,
	FID INT UNSIGNED NOT NULL,

	FOREIGN KEY (UID) REFERENCES NIMBUS_USER.USER (UID)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

########################################################################################################

GRANT ALL ON NIMBUS_USER.* TO auser@'%' IDENTIFIED BY 'auserpass';
FLUSH PRIVILEGES;