<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the "Database Connection"
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the "default" group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

// $active_group = "default";
// $active_group = "dayabay";
$active_group = "lbl";
$active_record = TRUE;

$db['default']['hostname'] = "localhost";
$db['default']['username'] = "root";
$db['default']['password'] = "3quarks";
$db['default']['database'] = "run_tag";
$db['default']['dbdriver'] = "mysql";
$db['default']['dbprefix'] = "";
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = "";
$db['default']['char_set'] = "utf8";
$db['default']['dbcollat'] = "utf8_general_ci";

$db['dayabay']['hostname'] = "dybdb1.ihep.ac.cn";
$db['dayabay']['username'] = "dayabay";
$db['dayabay']['password'] = "3quarks";
// $db['dayabay']['database'] = "testdb";
$db['dayabay']['database'] = "offline_db";
$db['dayabay']['dbdriver'] = "mysql";
$db['dayabay']['dbprefix'] = "";
$db['dayabay']['pconnect'] = FALSE;
$db['dayabay']['db_debug'] = FALSE;
$db['dayabay']['cache_on'] = FALSE;
$db['dayabay']['cachedir'] = "";
$db['dayabay']['char_set'] = "utf8";
$db['dayabay']['dbcollat'] = "utf8_general_ci";
// $db['dayabay']['port'] = 5432;

$db['ihep']['hostname'] = "dybdb2.ihep.ac.cn";
$db['ihep']['username'] = "dayabay";
$db['ihep']['password'] = "3quarks";
$db['ihep']['database'] = "offline_db";
$db['ihep']['dbdriver'] = "mysql";
$db['ihep']['dbprefix'] = "";
$db['ihep']['pconnect'] = FALSE;
$db['ihep']['db_debug'] = FALSE;
$db['ihep']['cache_on'] = FALSE;
$db['ihep']['cachedir'] = "";
$db['ihep']['char_set'] = "utf8";
$db['ihep']['dbcollat'] = "utf8_general_ci";

$db['lbl']['hostname'] = "dayabaydb.lbl.gov";
$db['lbl']['username'] = "dayabay";
$db['lbl']['password'] = "3quarks";
$db['lbl']['database'] = "offline_db";
$db['lbl']['dbdriver'] = "mysql";
$db['lbl']['dbprefix'] = "";
$db['lbl']['pconnect'] = FALSE;
$db['lbl']['db_debug'] = FALSE;
$db['lbl']['cache_on'] = FALSE;
$db['lbl']['cachedir'] = "";
$db['lbl']['char_set'] = "utf8";
$db['lbl']['dbcollat'] = "utf8_general_ci";


/* End of file database.php */
/* Location: ./system/application/config/database.php */