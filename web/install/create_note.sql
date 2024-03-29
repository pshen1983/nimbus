CREATE DATABASE NIMBUS_NOTE;

########################################################################################################

CREATE TABLE NIMBUS_NOTE.NOTE
(
	ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
	UID INT UNSIGNED NOT NULL,
	CID INT UNSIGNED NOT NULL,
	NBODY TEXT,
	MDATA NVARCHAR(41),
	CTIME DATETIME NOT NULL,

	PRIMARY KEY (ID),
	FOREIGN KEY (UID) REFERENCES NIMBUS_USER.USER (UID),
	FOREIGN KEY (CID) REFERENCES NIMBUS_CONF.CONF (CID)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

########################################################################################################

CREATE TABLE NIMBUS_NOTE.BROADCAST
(
	ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
	CID INT UNSIGNED NOT NULL,
	BTITLE NVARCHAR(41) NOT NULL,
	BBODY MEDIUMTEXT,
	CTIME DATETIME NOT NULL,
	UTIME DATETIME NOT NULL,

	PRIMARY KEY (ID),
	FOREIGN KEY (CID) REFERENCES NIMBUS_CONF.CONF (CID)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

########################################################################################################

CREATE TABLE NIMBUS_NOTE.MESSAGE
(
	ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
	TITLE NVARCHAR(321) NOT NULL,

	R_ID INT UNSIGNED NOT NULL,
	S_ID INT UNSIGNED NOT NULL,

	BODY TEXT,

	CTIME DATETIME NOT NULL,
	IS_READ ENUM('N','Y'),

	PRIMARY KEY (ID),
	FOREIGN KEY (R_ID) REFERENCES NIMBUS_USER.USER (UID),
	FOREIGN KEY (S_ID) REFERENCES NIMBUS_USER.USER (UID)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE INDEX TITLE_INDEX ON NIMBUS_NOTE.MESSAGE (TITLE(320));
CREATE INDEX IS_READ_INDEX ON NIMBUS_NOTE.MESSAGE (IS_READ);
CREATE INDEX CREATE_TIME_INDEX ON NIMBUS_NOTE.MESSAGE (CTIME);

########################################################################################################

GRANT ALL ON NIMBUS_NOTE.* TO anote@'%' IDENTIFIED BY 'anotepass';
FLUSH PRIVILEGES;