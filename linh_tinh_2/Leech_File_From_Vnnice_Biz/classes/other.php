<?php
if (!defined('RAPIDLEECH'))
  {
  require_once("index.html");
  exit;
  }

function create_hosts_file($host_file = "hosts.php")
	{
	$fp = opendir(HOST_DIR);
	while (($file = readdir($fp)) !== false)
		{
		if (substr($file, -4) == ".inc")
			{
			require_once(HOST_DIR.$file);
			}
		}
	if (!is_array($host))
		{
		print "No host file found";
		}
	else
		{
		$fs = fopen(HOST_DIR.$host_file, "wb");
		if (!$fs)
			{
			print "Cannot create hosts file";
			}
		else
			{
			fwrite($fs, "<?php\r\n\$host = array(\r\n");
			$i = 0;
			foreach ($host as $site => $file)
				{
				if ($i != (count($host) - 1))
					{
					fwrite($fs, "'".$site."' => '".$file."',\r\n");
					}
				else
					{
					fwrite($fs, "'".$site."' => '".$file."');\r\n?>");
					}
				$i++;
				}
			closedir($fp);
			fclose($fs);
			}
		}
	}

function logged_user($u)
	{
	global $_SERVER;
	foreach ($u as $user => $pass)
		{
		if ($_SERVER['PHP_AUTH_USER'] == $user && $_SERVER['PHP_AUTH_PW'] == $pass)
			return true;
		}
	return false;
	}

function is_present($lpage, $mystr, $strerror = "", $head = 0)
	{
  $strerror = $strerror ? $strerror : $mystr;
	if (stristr($lpage, $mystr))
		{
		html_error($strerror, $head);
		}
	}

function is_notpresent($lpage, $mystr, $strerror, $head = 0)
	{
	if (!stristr($lpage,$mystr))
		{
		html_error($strerror, $head);
		}
	}

function insert_location($newlocation)
	{
	global $nn;
	list($location, $list) = explode("?", $newlocation);
	$list = explode("&", $list);
	print "<form action=\"$location\" method=\"post\">".$nn;
	foreach ($list as $l)
		{
		list($name, $value) = explode("=", $l);
		print "<input type=\"hidden\" name=\"$name\" value=\"$value\">".$nn;
		
		}
	?>
<script language="JavaScript">
void(document.forms[0].submit());
</script>
</form>
</body>
</html>
	<?php
	flush();
	}

/*function insert_location($newlocation, $url="", $showurl=false ,$test=false)
	{

		if ($url && $showurl)
			{
				echo "<center><b>$url</b></center>\n";
				return true;
			}

		if ($test === false)
			{
?>

<script language=javascript>
	location.href='<?php echo $newlocation ?>';
</script>
<?php
			}
				else
			{
				echo "<center><b>$newlocation</b></center>\n";
			}
	flush();
	return true;
	}
*/

function pause_download()
	{
	global $pathWithName, $PHP_SELF, $_GET, $nn, $bytesReceived, $fs, $fp;
	$status = connection_status();
	if (($status == 2 || $status == 3) && $pathWithName && $bytesReceived > -1)
    {
    flock($fs, LOCK_UN);
		fclose($fs);
		fclose($fp);
    }
	}

function cut_str($str, $left, $right)
  {
  $str = substr(stristr($str, $left), strlen($left));
  $leftLen = strlen(stristr($str, $right));
  $leftLen = $leftLen ? -($leftLen) : strlen($str);
  $str = substr($str, 0, $leftLen);
  return $str;
  }

function write_file($file_name, $data, $trunk = 1)
  {
  if($trunk == 1)
    {
    $mode = "wb";
    }
  elseif ($trunk == 0)
    {
    $mode = "ab";
    }
  $fp = fopen($file_name, $mode);
  if(!$fp)
    {
    return FALSE;
    }
  else
    {
    if(!flock($fp, LOCK_EX))
      {
      return FALSE;
      }
    else
      {
      if(!fwrite($fp, $data))
        {
        return FALSE;
        }
      else
        {
        if(!flock($fp, LOCK_UN))
          {
          return FALSE;
          }
        else
          {
          if(!fclose($fp))
            {
            return FALSE;
            }
          }
        }
      }
    }
  return TRUE;
  }

function read_file($file_name, $count = -1)
  {
  if($count == -1)
    {
    $count = filesize($file_name);
    }
  $fp = fopen($file_name, "rb");
  flock($fp, LOCK_SH);
  $ret = fread($fp, $count);
  flock($fp, LOCK_UN);
  fclose($fp);
  return $ret;
  }

function pre($var)
  {
  echo "<pre>";
  print_r($var);
  echo "</pre>";
  }

function getmicrotime()
  {
  list($usec, $sec) = explode(" ",microtime());
  return ((float)$usec + (float)$sec);
  }

function html_error($msg, $head = 1)
  {
  global $PHP_SELF;
  if ($head == 1)
    {
    ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title>Error...</title>
<style type="text/css">
<!--
@import url("<?php print IMAGE_DIR; ?>rl_style_pm.css");
-->
</style>
</head>
<body>
<center><img src="<?php print IMAGE_DIR; ?>logo_pm.gif" alt="RAPIDLEECH PLUGMOD"></center><br><br>
<?php
    }
    ?>
<center>
<?php echo "<span style=\"color:red; background-color:#fec; padding:3px; border:2px solid #FFaa00; line-height:25px\"><b>$msg</b></span><br>"; ?><br>
<a href="<?php print $PHP_SELF; ?>">Go back to main</a>
</center>
</body>
</html>
<?php
  exit;
  }

function sec2time($time)
  {
  $hour = round($time / 3600, 2);
  if($hour >= 1)
    {
    $hour = floor($hour);
    $time -= $hour * 3600;
    }
  $min = round($time / 60, 2);
  if($min >= 1)
    {
    $min = floor($min);
    $time -= $min * 60;
    }
  $sec = $time;
  $hour = ($hour > 1) ? $hour." hours " : ($hour == 1) ? $hour." hour " : "";
  $min = ($min > 1) ? $min." minutes " : ($min == 1) ? $min." minute " : "";
  $sec = ($sec > 1) ? $sec." seconds" : ($sec == 1 || $sec == 0) ? $sec." second" : "";
  return $hour.$min.$sec;
  }

function bytesToKbOrMbOrGb($bytes)
  {
  if (is_numeric($bytes))
    {
    if ($bytes >= (1024 * 1024 * 1024))
      {
      $size = round($bytes / (1024 * 1024 * 1024), 2)." GB";
      }
    elseif ($bytes >= (1024 * 1024))
      {
      $size = round($bytes / (1024 * 1024), 2)." MB";
      }
    elseif ($bytes >= (1024))
      {
      $size = round($bytes / 1024, 2)." KB";
      }
    else
      {
      $size = $bytes." B";
      }
    }
  else
    {
    $size = "Unknown";
    }
  return $size;
  }
  
function updateListInFile($list)
  {
  if(count($list) > 0)
    {
    foreach($list as $key => $value)
      {
      $list[$key] = serialize($value);
      }
    if(!@write_file(CONFIG_DIR."files.lst", implode("\r\n", $list)."\r\n") && count($list) > 0)
      {
      return FALSE;
      }
    else
      {
      return TRUE;
      }
    }
  elseif (@file_exists(CONFIG_DIR."files.lst"))
    {
    return unlink(CONFIG_DIR."files.lst");
    }
  }

function _cmp_list_enums($a,$b)
  {
  return strcmp($a["name"],$b["name"]);
  }

function _create_list()
  {
  global $list, $_COOKIE, $show_all, $forbidden_filetypes;
  $glist = array();
  if(($show_all === true) & ($_COOKIE["showAll"] == 1))
    {
    $dir = dir(DOWNLOAD_DIR);
    while(false !== ($file = $dir->read()))
      {
      if($file != "." && $file != ".." && (!is_array($forbidden_filetypes) || !in_array(strtolower(strrchr($file, ".")), $forbidden_filetypes))&& is_file(DOWNLOAD_DIR.$file) && basename($file) != "files.lst")
        {
        $file = DOWNLOAD_DIR.$file;
        $time = filectime($file);
        while(isset($glist[$time])) $time++;
        $glist[$time] = array("name" => realpath($file),
                              "size" => bytesToKbOrMbOrGb(filesize($file)),
                              "date" => $time);
        }
      }
    $dir->close();
    @uasort($glist,"_cmp_list_enums");
    }
  else
    {
    if(@file_exists(CONFIG_DIR."files.lst"))
      {
      $glist = file(CONFIG_DIR."files.lst");
      foreach($glist as $key => $record)
        {
        foreach(unserialize($record) as $field => $value)
          {
          $listReformat[$key][$field] = $value;
          if($field == "date") $date = $value;
          }
        $glist[$date] = $listReformat[$key];
        unset($glist[$key], $glistReformat[$key]);
        }
      }
    }
  $list = $glist;
  }

function checkmail($mail)
  {
  if(strlen($mail) == 0)
    {
    return false;
    }
  if(!preg_match("/^[a-z0-9_\.-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|".
   "edu|gov|arpa|info|biz|inc|name|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-".
   "9]{1,3}\.[0-9]{1,3})$/is", $mail))
    {
    return false;
    }
  return true;
  }
/* Fixed Shell exploit by: icedog */
function fixfilename($fname,$fpach='')
	{
		$f_name=basename($fname);
		$f_dir=dirname(eregi_replace("\.\./", "", $fname));
		$f_dir=($f_dir == '.') ? '' : $f_dir;
		$f_dir=eregi_replace("\.\./", "", $f_dir);
		$fpach=eregi_replace("\.\./", "", $fpach);
		$f_name=eregi_replace("\.(php|hta|pl|cgi|sph)", ".xxx", $f_name);
		$ret= ($fpach) ? $fpach.DIRECTORY_SEPARATOR.$f_name : ($f_dir ? $f_dir.DIRECTORY_SEPARATOR : '').$f_name;
		return $ret;
	}
function getfilesize($f)
	{
		global $is_windows;
		$stat=stat($f);
		
		if ($is_windows) return sprintf("%u", $stat[7]);
		if (($stat[11] * $stat[12]) < 4*1024*1024*1024) return sprintf("%u", $stat[7]);
	
		global $max_4gb;
		if ($max_4gb === false)
			{
				$tmp_=trim(@shell_exec(" ls -Ll ".@escapeshellarg($f)));
				while (strstr($tmp_,'  ')) { $tmp_=@str_replace('  ',' ',$tmp_); }
				$r=@explode(' ',$tmp_);
				$size_=$r[4];
			}
				else
			{
				$size_=-1;
			}
	
		return $size_;
	}
function bytesToKbOrMb($bytes){
$size = ($bytes >= (1024 * 1024 * 1024 * 1024)) ? round($bytes / (1024 * 1024 * 1024 * 1024), 2)." TB" : (($bytes >= (1024 * 1024 * 1024)) ? round($bytes / (1024 * 1024 * 1024), 2)." GB" : (($bytes >= (1024 * 1024)) ? round($bytes / (1024 * 1024), 2)." MB" : round($bytes / 1024, 2)." KB"));
return $size;
}
function defport($urls)
	{
		if ($urls["port"] !== '' && isset($urls["port"])) return $urls["port"];
		
		switch (strtolower($urls["scheme"]))
			{
				case "http" : return '80';
				case "https" : return '443';
				case "ftp" : return '21';
			}
	}
function getSize($file)
	{
        $size = filesize($file);
        if ($size < 0)
            if (!(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN'))
                $size = trim(`stat -c%s $file`);
            else{
                $fsobj = new COM("Scripting.FileSystemObject");
                $f = $fsobj->GetFile($file);
                $size = $file->Size;
            }
        return $size;
    }
?>