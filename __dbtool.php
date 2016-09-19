<?php

require_once( 'dbconfig.php' );
$db = new Database();
$conn = $db->dbConnection();  // This will die if it fails.

echo '<br>Connected successfully';

/*

-- This is a reference table
DROP TABLE IF EXISTS wc__calendar_table;
CREATE TABLE wc__calendar_table (
 dt DATE NOT NULL PRIMARY KEY
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE PROCEDURE insert_dates()
BEGIN
  DECLARE endDate DATE;
  DECLARE iterDate DATE;
  SET endDate = '2116-12-31';
  SET iterDate = '2016-01-01';

label1: LOOP
   INSERT INTO wc__calendar_table( dt ) VALUES( iterDate );
   SET iterDate = DATE_ADD( iterDate, INTERVAL 1 DAY );
   IF iterDate <= endDate THEN
     ITERATE label1;
   END IF;
   LEAVE label1;
  END LOOP label1;
END;

CALL insert_dates();  -- Ignore the error when run from phpMyAdmin that seems to call it twice.

DROP PROCEDURE insert_dates;

DROP TABLE IF EXISTS wc__notifications;
DROP TABLE IF EXISTS wc__challenges;
DROP TABLE IF EXISTS wc__challenge_participant;
DROP TABLE IF EXISTS wc__user_weigh_in;
DROP TABLE IF EXISTS wc__users;
CREATE TABLE wc__users(
   user_id INT(11) NOT NULL AUTO_INCREMENT
  ,sessionid CHAR( 128 ) NOT NULL
  ,user_name VARCHAR(15) NOT NULL
  ,user_email VARCHAR(40) NOT NULL
  ,user_pass VARCHAR(255) NOT NULL
  ,join_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
  ,CONSTRAINT PRIMARY KEY ( user_id )
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
INSERT INTO wc__users(user_id, sessionid, user_name, user_email, user_pass, join_date) VALUES( 1, 'X', 'kyle', 'kyle.brazell@ymail.com', '$2y$10$BbEIYQUUFMD7GwCQYWWqsuagDJjjDxdlirOnVTwMIugNrNXEM4o9i', '2016-08-31 11:53:26');
INSERT INTO wc__users(user_id, sessionid, user_name, user_email, user_pass, join_date) VALUES( 2, 'X', 'bogus1', 'bogus1@bogus.org', '$2y$10$1PNKvjHzM2ol5u9b.tbmI.J3.WRaDbz3v/bxR1urQFl4/WYrLj0la', '2016-08-31 12:00:00');
INSERT INTO wc__users(user_id, sessionid, user_name, user_email, user_pass, join_date) VALUES( 3, 'X', 'bogus2', 'bogus2@bogus.org', '$2y$10$zpc19DIPR1vcpjM44bf7UOSWHGHW5TL7JD09JPTMC0bzjBzBMI79q', '2016-08-31 13:00:00');

DROP TABLE IF EXISTS wc__challenge_participant;
DROP TABLE IF EXISTS wc__challenges;
CREATE TABLE wc__challenges
(
  challenge_id INT(10) NOT NULL AUTO_INCREMENT
 ,fk_created_by INT NOT NULL
 ,challenge_name VARCHAR(255) NOT NULL
 ,start_date DATE NOT NULL COMMENT 'date on which the challenge begins'
 ,end_date DATE NOT NULL COMMENT 'date on which the challenge ends'
 ,challenge_type INT(10) NOT NULL DEFAULT 1 COMMENT '1 = Weight loss, 2 = weight gain'
 ,CONSTRAINT PRIMARY KEY ( challenge_id )
 ,CONSTRAINT FOREIGN KEY (fk_created_by) REFERENCES wc__users(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


INSERT INTO wc__challenges(challenge_id, fk_created_by, challenge_name, start_date, end_date, challenge_type)
VALUES
 ( 1, 1, 'First challenge',             '2016-01-01', '2016-01-31', 1)
,( 2, 1, 'Second challenge',            '2016-02-01', '2016-02-27', 1)
,( 3, 1, 'third challenge',             '2016-03-01', '2016-03-31', 1)
,( 7, 2, 'Test Challenge',              '2015-01-01', '2015-01-31', 1)
,( 8, 2, 'Do I self-invite?',           '2016-10-01', '2016-10-31', 1)
,( 9, 2, 'Manual Active Challenge One', '2016-09-01', '2016-09-30', 1)
,(10, 2, 'Manual Active Challenge Two', '2016-09-15', '2016-10-15', 1)



DROP TABLE IF EXISTS wc__challenge_participant;
CREATE TABLE wc__challenge_participant
(
  fk_challenge_id INT(10) NOT NULL
 ,fk_user_id INT(10) NOT NULL
 ,start_date DATE NOT NULL COMMENT 'copy this data value when challenge is joined'
 ,end_date DATE NOT NULL COMMENT 'copy this data value when challenge is joined'
 ,challenge_type INT(10) NOT NULL DEFAULT 1 COMMENT 'copy this data value when challenge is joined'
 ,start_weight INT(10) NULL
 ,goal_weight INT(10) NULL
 ,rank INT(10) NULL
 ,team_size INT(10) NULL COMMENT 'update this every time a team member joins'
 ,`status` VARCHAR(30) NULL COMMENT 'One of Invited, Accepted, Declined, Participating, Complete, or Disqualified'
,CONSTRAINT FOREIGN KEY (fk_challenge_id) REFERENCES wc__challenges(challenge_id)
,CONSTRAINT FOREIGN KEY (fk_user_id) REFERENCES wc__users(user_id)
,CONSTRAINT uc_participant UNIQUE (fk_challenge_id, fk_user_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO wc__challenge_participant(fk_challenge_id,fk_user_id,start_date,end_date,challenge_type,start_weight,goal_weight,rank,team_size,status)
VALUES
 (1,1,    '2016-01-01', '2016-01-31', 1,  260,  250,  1,  0,  'Complete')
,(2,1,    '2016-02-01', '2016-02-27', 1,  260,  250,  1,  0,  'Complete')
,(3,1,    '2016-03-01', '2016-03-31', 1,  260,  250,  1,  0,  'Complete')
,(7,1,    '2015-01-01', '2015-01-31', 1,  null, 0,  null, 2,  'Declined')
,(1,2,    '2016-01-01', '2016-01-31', 1,  260,  250,  1,  0,  'Complete')
,(2,2,    '2016-02-01', '2016-02-27', 1,  260,  250,  1,  0,  'Complete')
,(3,2,    '2016-03-01', '2016-03-31', 1,  260,  250,  1,  0,  'Complete')
,(7,2,    '2015-01-01', '2015-01-31', 1,  null, 0,  null, 2,  'Complete')
,(8,2,    '2016-10-01', '2016-10-31', 1,  null, 0,  null, 0,  'Invited')
,(1,3,    '2016-01-01', '2016-01-31', 1,  260,  250,  1,  0,  'Complete')
,(2,3,    '2016-02-01', '2016-02-27', 1,  260,  250,  1,  0,  'Complete')
,(3,3,    '2016-03-01', '2016-03-31', 1,  260,  250,  1,  0,  'Complete')
,(7,3,    '2015-01-01', '2015-01-31', 1,  null, null, null, null, 'Declined')
,(8,3,    '2016-10-01', '2016-10-31', 1,  null, null, null, null, 'Invited')
,(9,3,    '2016-09-01', '2016-09-30', 1,  250,  230,  1,  1,  'Participating')
,(10,3,   '2016-09-15', '2016-10-15', 1,  240,  220,  1,  1,  'Participating');

DROP TABLE IF EXISTS wc__user_weigh_in;
CREATE TABLE wc__user_weigh_in
(
  fk_user_id INT(10) NOT NULL
 ,weigh_date DATE NOT NULL
 ,weight INT(10) NULL
,CONSTRAINT FOREIGN KEY (fk_user_id) REFERENCES wc__users(user_id)
,CONSTRAINT uc_weigh_in UNIQUE (fk_user_id, weigh_date)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-01', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-02', 249 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-03', 248 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-04', 247 );

-- Jan 5 will show as MISSING! (to be repaired by user viewing of data) (add a NULL for this date)
--INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-05', 246 );

INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-06', 246 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-07', 247 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-08', 247 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-09', 245 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-10', 246 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-11', 248 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-12', 246 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-13', 245 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-14', 244 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-15', 243 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-16', 243 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-17', 244 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-18', 243 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-19', 242 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-20', 242 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-21', 241 );
-- Jan 22, 23, & 24 will show as MISSING! (to be repaired by user viewing of data) (add a NULL for this date)
--INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-22', 243 );
--INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-23', 244 );
--INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-24', 243 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-25', 242 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-26', 242 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-27', 243 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-28', 242 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-29', 241 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-30', 241 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-01-31', 241 );


INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-01', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-02', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-03', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-04', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-05', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-06', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-07', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-08', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-09', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-10', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-11', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-12', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-13', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-14', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-15', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-16', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-17', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-18', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-19', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-20', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-21', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-22', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-23', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-24', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-25', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-26', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-27', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-28', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-29', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-09-30', 250 );

INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-01', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-02', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-03', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-04', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-05', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-06', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-07', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-08', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-09', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-10', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-11', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-12', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-13', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-14', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-15', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-16', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-17', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-18', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-19', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-20', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-21', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-22', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-23', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-24', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-25', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-26', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-27', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-28', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-29', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-30', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 1, '2016-10-31', 250 );


INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-01', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-02', 250 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-03', 249 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-04', 249 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-05', 248 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-06', 248 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-07', 247 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-08', 247 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-09', 246 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-10', 246 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-11', 245 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-12', 245 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-13', 244 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-14', 244 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-15', 243 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-16', 242 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-17', 242 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-18', 241 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-19', 241 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-20', 240 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-21', 240 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-22', 240 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-23', 239 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-24', 239 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-25', 238 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-26', 238 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-27', 237 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-28', 237 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-29', 236 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-09-30', 236 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-01', 235 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-02', 235 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-03', 234 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-04', 234 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-05', 233 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-06', 233 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-07', 232 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-08', 232 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-09', 231 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-10', 231 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-11', 230 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-12', 230 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-13', 230 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-14', 229 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-15', 229 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-16', 228 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-17', 228 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-18', 227 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-19', 227 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-20', 226 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-21', 226 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-22', 225 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-23', 225 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-24', 224 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-25', 224 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-26', 223 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-27', 223 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-28', 222 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-29', 222 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-30', 221 );
INSERT INTO wc__user_weigh_in( fk_user_id, weigh_date, weight ) VALUES( 3, '2016-10-31', 221 );

  INSERT INTO wc__notifications( fk_user_id, msg_id, msg_text, added_on )
  VALUES ( :uid, :msg_type, :msg_text, now() )';

DROP TABLE IF EXISTS wc__notifications;
CREATE TABLE wc__notifications
(
   fk_user_id INT(10) NOT NULL
  ,msg_id INT(10) NOT NULL
  ,msg_text VARCHAR(255) NOT NULL
  ,added_on DATETIME NOT NULL DEFAULT NOW()
 ,CONSTRAINT FOREIGN KEY (fk_user_id) REFERENCES wc__users(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

*/

echo '<br>Describe table';
echo '<br>';
$sql = 'DESCRIBE users';
$stmt = $conn->prepare( $sql );
$stmt->execute();

$result = $stmt->fetch( PDO::FETCH_ASSOC );
print_r( $result );

?>