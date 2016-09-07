<?php

require_once( 'dbconfig.php' );
$db = new Database();
$conn = $db->dbConnection();  // This will die if it fails.

echo '<br>Connected successfully';

/*
echo '<br>Trying to create table';
$sql = "CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(15) NOT NULL,
  `user_email` varchar(40) NOT NULL,
  `user_pass` varchar(255) NOT NULL,
  `joining_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
$stmt = $conn->prepare( $sql );
$stmt->execute();
echo '<br>Table created';
*/


/*
DROP TABLE users;
CREATE TABLE IF NOT EXISTS users(
   user_id int(11) NOT NULL AUTO_INCREMENT
  ,user_name varchar(15) NOT NULL
  ,user_email varchar(40) NOT NULL
  ,user_pass varchar(255) NOT NULL
  ,join_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
  ,CONSTRAINT PRIMARY KEY ( user_id )
) ENGINE=InnoDB;
--) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
INSERT INTO users(user_id, user_name, user_email, user_pass, join_date) VALUES( 1, 'kyle', 'kyle.brazell@ymail.com', '$2y$10$BbEIYQUUFMD7GwCQYWWqsuagDJjjDxdlirOnVTwMIugNrNXEM4o9i', '2016-08-31 11:53:26');
INSERT INTO users(user_id, user_name, user_email, user_pass, join_date) VALUES( 2, 'bogus1', 'bogus1@bogus.org', '$2y$10$1PNKvjHzM2ol5u9b.tbmI.J3.WRaDbz3v/bxR1urQFl4/WYrLj0la', '2016-08-31 12:00:00');
INSERT INTO users(user_id, user_name, user_email, user_pass, join_date) VALUES( 3, 'bogus2', 'bogus2@bogus.org', '$2y$10$zpc19DIPR1vcpjM44bf7UOSWHGHW5TL7JD09JPTMC0bzjBzBMI79q', '2016-08-31 13:00:00');

DROP TABLE challenge_participant; -- Drop this one first because of foreign key issues
DROP TABLE challenges;
CREATE TABLE challenges
(
  challenge_id INT(10) NOT NULL
, fk_created_by INT NOT NULL
, challenge_name VARCHAR(255) NOT NULL
, start_date DATETIME NOT NULL COMMENT 'date on which the challenge begins'
, end_date DATETIME NOT NULL COMMENT 'date on which the challenge ends'
, challenge_type INT(10) NOT NULL DEFAULT 1 COMMENT '1 = Weight loss, 2 = weight gain'
, CONSTRAINT PRIMARY KEY ( challenge_id )
) ENGINE=InnoDB;
INSERT INTO challenges(challenge_id, fk_created_by, challenge_name, start_date, end_date, challenge_type) VALUES( 1, 1, 'First challenge', '2016-01-01 12:00:00', '2016-01-31 12:00:00', 1);
INSERT INTO challenges(challenge_id, fk_created_by, challenge_name, start_date, end_date, challenge_type) VALUES( 2, 1, 'Second challenge', '2016-02-01 12:00:00', '2016-02-27 12:00:00', 1);
INSERT INTO challenges(challenge_id, fk_created_by, challenge_name, start_date, end_date, challenge_type) VALUES( 3, 1, 'third challenge', '2016-03-01 12:00:00', '2016-03-31 12:00:00', 1);



CREATE TABLE challenge_participant
(
  fk_challenge_id INT(10) NOT NULL
, fk_user_id INT(10) NOT NULL
, goal_weight INT(10) NOT NULL
,CONSTRAINT FOREIGN KEY (fk_challenge_id) REFERENCES challenges(challenge_id)
,CONSTRAINT FOREIGN KEY (fk_user_id) REFERENCES users(user_id)
) ENGINE=InnoDB;
INSERT INTO challenge_participant(fk_challenge_id, fk_user_id, goal_weight) VALUES( 1, 1, 250 );
INSERT INTO challenge_participant(fk_challenge_id, fk_user_id, goal_weight) VALUES( 1, 2, 250 );
INSERT INTO challenge_participant(fk_challenge_id, fk_user_id, goal_weight) VALUES( 1, 3, 250 );

*/

echo '<br>Describe table';
echo '<br>';
$sql = 'DESCRIBE users';
$stmt = $conn->prepare( $sql );
$stmt->execute();

$result = $stmt->fetch( PDO::FETCH_ASSOC );
print_r( $result );

?>