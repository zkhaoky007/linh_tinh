<?php
if (!defined('RAPIDLEECH'))
  {
  require_once("index.html");
  exit;
  }

$no_cache = true; # true - Prohibition by Browser; otherwise allowed (You should leave this intact unless you know what you are doing)
//$images_via_php = false; # true - RapidShare images are downloaded through the script, but it requires ssl support; turn it off if you can't see the image.
$redir = true; # true - Redirect passive method (You should leave this intact unless you know what you are doing)
$show_all = true; # true - To show all files in the catalog, false to hide it

$login = false; # false - Authorization mode is off, true - on
$users = array('test' => 'test'); # false - Authorization mode is off, enter the username and password in the given way

$forbidden_filetypes = array('.htaccess', '.htpasswd', '.php', '.php3', '.php4', '.php5', '.phtml', '.asp', '.aspx', '.cgi'); # Enter the forbidden filetypes in the given way, if you want to allow all then after the = put two '
$rename_these_filetypes_to = '.xxx'; # If you want to prevent them from downloading then set this to false without the '.
$check_these_before_unzipping = true;

$download_dir = "files/"; // This is where your downloaded files are saved
$download_dir_is_changeable = false; // Set it to false to disallow users to change the download dir

$disable_deleting = false; //Set it to True to disallow users to DELETE OR RENAME files(useful for public servers)

$server_info = false; //cpu & memory load info.

$premium_acc = array();
//$premium_acc["rs_de"] = array('user' => 'your username', 'pass' => 'your password'); # Remove '//' from the beginning and enter your username and password for rapidshare.de premium account
//$premium_acc["rs_com"] = array('user' => 'your username', 'pass' => 'your password'); # Remove '//' from the beginning and enter your username and password for rapidshare.com premium account
//$premium_acc["megaupload"] = array('user' => 'your username', 'pass' => 'your password'); # Remove '//' from the beginning and enter your username and password for rapidshare.com premium account
//$premium_acc["netload"] = array('user' => 'your username', 'pass' => 'your password'); # Remove '//' from the beginning and enter your username and password for rapidshare.com premium account
//$premium_acc["megashare"] = array('user' => 'your username', 'pass' => 'your password'); # Remove '//' from the beginning and enter your username and password for megashare.com premium account

### Megaupload cookie ###
//$mu_cookie_user_value = '';  // like: b658b369856766f621ca292fac113a5c, that contains un&pass of premium account and can be shared to others, because it's an encrypted value.

### Imageshack Torrent Account ###
//$imageshack_acc = array('user' => 'your username', 'pass' => 'your password'); 

###Auto Download Premium Account ###
//$premium_acc["au_dl"] = array('user' => 'your username', 'pass' => 'your password'); # Remove '//' from the beginning and enter your username and password for rapidshare.de premium account

### Bandwidth Saving ###
$bw_save = true; 

### Disable All Actions (upload, split, zip, etc.)###
$disable_action = false;

### file_get_contents for LinkChecker ##
$fgc = 0; // 1= use file_get_contents // 0 = use cURL
?>