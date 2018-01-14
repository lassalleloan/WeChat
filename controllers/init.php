<?php
/**
 * STI - Project Web
 * WeChat
 * Description: Web site to send mails between users 
 * Authors: Matthieu Chatelan, Loan Lassalle, Wojciech Myszkorowski
 */

// Parameter of the time zone
date_default_timezone_set('Europe/Zurich');

require_once(dirname(__DIR__).'/models/Database.php');
require_once(dirname(__DIR__).'/models/Mail.php');
require_once(dirname(__DIR__).'/models/Role.php');
require_once(dirname(__DIR__).'/models/User.php');

/**
 * Create the tables
 */

Database::get_instance()->query('DROP TABLE IF EXISTS roles;', null);
Database::get_instance()->query('CREATE TABLE IF NOT EXISTS roles (
                                id INTEGER PRIMARY KEY AUTOINCREMENT, 
                                name VARCHAR('.Database::PHP_STR_MAX.') UNIQUE NOT NULL);',
                                null);

Database::get_instance()->query('DROP TABLE IF EXISTS users;', null);
Database::get_instance()->query('CREATE TABLE IF NOT EXISTS users (
                                id INTEGER PRIMARY KEY AUTOINCREMENT,
                                username VARCHAR('.Database::PHP_STR_MAX.') UNIQUE NOT NULL,
                                salt VARCHAR('.Database::PHP_STR_MAX.') NOT NULL,
                                digest VARCHAR('.Database::DIGEST_LEN.') UNIQUE NOT NULL,
                                active INTEGER NOT NULL,
                                idRole INTEGER NOT NULL,
                                FOREIGN KEY(idRole) REFERENCES roles(id));',
                                null);

Database::get_instance()->query('DROP TABLE IF EXISTS mails;', null);
Database::get_instance()->query('CREATE TABLE IF NOT EXISTS mails (
                                id INTEGER PRIMARY KEY AUTOINCREMENT,
                                date VARCHAR('.Database::PHP_DATE_LEN.') UNIQUE NOT NULL,
                                idSender INTEGER,
                                idReceiver INTEGER,
                                subject VARCHAR('.Database::PHP_STR_MAX.') NOT NULL,
                                body TEXT('.Database::PHP_TEXT_MAX.') NOT NULL,
                                FOREIGN KEY(idSender) REFERENCES users(id),
                                FOREIGN KEY(idReceiver) REFERENCES users(id));',
                                null);

Database::get_instance()->query('DROP TABLE IF EXISTS blacklist;', null);
Database::get_instance()->query('CREATE TABLE IF NOT EXISTS blacklist (
                                id INTEGER PRIMARY KEY AUTOINCREMENT,
                                date VARCHAR('.Database::PHP_DATE_LEN.') UNIQUE NOT NULL,
                                attempt INTEGER NOT NULL,
                                ip VARCHAR(15) NOT NULL);',
                                null);

/**
 * Set the data
 */

$roles = array(
            array('name' => 'Administrator'),
            array('name' => 'Co-worker')
            );
            
$users = array(
            array('username' => 'root',
                'password' => 'rootroot',
                'active' => true,
                'role' => 'Administrator'),
            array('username' => 'toor',
                'password' => 'toortoor',
                'active' => false,
                'role' => 'Administrator'),
            array('username' => 'loan',
                'password' => 'loanloan',
                'active' => true,
                'role' => 'Co-worker'),
            array('username' => 'wojciech',
                'password' => 'wojciech',
                'active' => true,
                'role' => 'Co-worker'),
            array('username' => 'tano',
                'password' => 'tanotano',
                'active' => false,
                'role' => 'Co-worker')
            );
            
$mails = array(
            array('date' => '2017-07-25T16:47:33.698',
                'sender' => 'loan',
                'receiver' => 'wojciech',
                'subject' => 'CTF to Bucarest !',
                'body' => 'Hi Wojciech, are you ? Are you ready to go to Bucarest ? I can not wait !! Take care.'),
            array('date' => '2017-08-09T10:14:23.698',
                'sender' => 'wojciech',
                'receiver' => 'loan',
                'subject' => 'RE: CTF to Bucarest !',
                'body' => 'Hello Loan ! Yes ! I am ready to go !! Do you prefer to go by plane, train or car? Because my father offers us to take the private jet !'),
            array('date' => '2017-08-14T22:37:43.698',
                'sender' => 'tano',
                'receiver' => 'toor',
                'subject' => 'Hints for Labo_01',
                'body' => 'Can you tell me the answer of the first question please.'),
            array('date' => '2017-08-25T12:12:38.388',
                'sender' => 'root',
                'receiver' => 'loan',
                'subject' => 'Welcome !',
                'body' => 'Welcome to WeChat, the net social plateform. Enjoy !'),
            array('date' => '2017-08-25T16:16:39.798',
                'sender' => 'root',
                'receiver' => 'wojciech',
                'subject' => 'Welcome !',
                'body' => 'Welcome to WeChat, the net social plateform. Enjoy !'),
            array('date' => '2017-09-01T18:49:38.698',
                'sender' => 'root',
                'receiver' => 'tano',
                'subject' => 'Welcome !',
                'body' => 'Welcome to WeChat, the net social plateform. Enjoy !'),
            array('date' => '2017-09-08T21:57:38.698',
                'sender' => 'toor',
                'receiver' => 'wojciech',
                'subject' => 'Protect your password',
                'body' => 'Welcome to WeChat, the net social plateform. Enjoy !'),
            array('date' => '2017-09-22T23:36:38.698',
                'sender' => 'loan',
                'receiver' => 'wojciech',
                'subject' => 'RE: CTF to Bucarest !',
                'body' => 'the PRIVATE JET !!!'),
            array('date' => '2017-10-03T07:36:38.698',
                'sender' => 'loan',
                'receiver' => 'root',
                'subject' => 'Administration of website ',
                'body' => 'Can I join administration of the website ?'),
            array('date' => '2017-10-03T10:36:38.698',
                'sender' => 'loan',
                'receiver' => 'root',
                'subject' => 'Pentesting',
                'body' => 'You should think about doing pentest. Because your website is under attack.')
            );

/**
 * Inserts data
 */

Role::get_instance()->insert_multiple($roles);
User::get_instance()->insert_multiple($users);
Mail::get_instance()->insert_multiple($mails);

/**
 * Closes the connection to the database
 */
Database::get_instance()->deconnection();

/**
 * Go to website
 */
header('location:/wechat/index.php');
?>
