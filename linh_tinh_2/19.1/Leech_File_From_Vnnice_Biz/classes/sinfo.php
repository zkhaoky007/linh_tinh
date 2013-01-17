<hr>
<SCRIPT LANGUAGE="JavaScript"> 
<!-- Begin 
    function getthedate(){ 
        var mydate=new Date(); 
        var hours=mydate.getHours(); 
        var minutes=mydate.getMinutes(); 
        var seconds=mydate.getSeconds(); 
        var dn="AM"; 
        if (hours>=12) dn="PM"; 
        if (hours>12) hours=hours-12;        
        if (hours==0) hours=12; 
        if (minutes<=9) minutes="0"+minutes; 
        if (seconds<=9)    seconds="0"+seconds; 
        

        var cdate="<span style=\"color:#994A1D\">Local Time:</span> &nbsp;&nbsp;&nbsp;<span style=\"color:#999\">"+hours+":"+minutes+":"+seconds+" "+dn+"</span><BR>";
        if (document.all) 
            document.all.clock.innerHTML=cdate; 
        else if (document.getElementById) 
            document.getElementById("clock").innerHTML=cdate; 
        else 
            document.write(cdate); 
    } 
    if (!document.all&&!document.getElementById) getthedate(); 

    function goforit(){ 
        if (document.all||document.getElementById) setInterval("getthedate()",1000); 
    } 
    window.onload=goforit; 
// End --> 
</SCRIPT>
<table cellspacing="2" cellpadding="2">
<tr>
<?php
if ($servername == "")
{
    $theservername = $_SERVER['SERVER_NAME'];
}
else
{
    $theservername = $servername;
}
if ($customos == "")
{
    $osname = checkos();
}
else
{
    $os = "nocpu";
    $osname = $customos;
}
if (php_sapi_name() == "apache2handler")
{
    $httpapp = "Apache";
}
else
{
    $httpapp = php_sapi_name();
}
function checkos()
{
    if (substr(PHP_OS, 0, 3) == "WIN")
    {
        $osType = winosname();
        $osbuild = php_uname('v');
        $os = "windows";
    } elseif (PHP_OS == "FreeBSD")
    {
        $os = "nocpu";
        $osType = "FreeBSD";
        $osbuild = php_uname('r');
    } elseif (PHP_OS == "Darwin")
    {
        $os = "nocpu";
        $osType = "Apple OS X";
        $osbuild = php_uname('r');
    } elseif (PHP_OS == "Linux")
    {
        $os = "linux";
        $osType = "Linux";
        $osbuild = php_uname('r');
    }
    else
    {
        $os = "nocpu";
        $osType = "Unknown OS";
        $osbuild = php_uname('r');
    }
    return $osType;
}
function winosname()
{
    $wUnameB = php_uname("v");
    $wUnameBM = php_uname("r");
    $wUnameB = eregi_replace("build ", "", $wUnameB);
    if ($wUnameBM == "5.0" && ($wUnameB == "2195"))
    {
        $wVer = "Windows 2000";
    }
    if ($wUnameBM == "5.1" && ($wUnameB == "2600"))
    {
        $wVer = "Windows XP";
    }
    if ($wUnameBM == "5.2" && ($wUnameB == "3790"))
    {
        $wVer = "Windows Server 2003";
    }
    if ($wUnameBM == "6.0" && (php_uname("v") == "build 6000"))
    {
        $wVer = "Windows Vista";
    }
    if ($wUnameBM == "6.0" && (php_uname("v") == "build 6001"))
    {
        $wVer = "Windows Vista SP1";
    }
    return $wVer;
}
if (PHP_OS == "WINNT")
{
    $os = "windows";
    $osbuild = php_uname('v');
} elseif (PHP_OS == "Linux")
{
    $os = "linux";
    $osbuild = php_uname('r');
}
else
{
    $os = "nocpu";
    $osbuild = php_uname('r');
}
function ZahlenFormatieren($Wert)
{
    if ($Wert > 1099511627776)
    {
        $Wert = number_format($Wert / 1099511627776, 2, ".", ",") . " TB";
    } elseif ($Wert > 1073741824)
    {
        $Wert = number_format($Wert / 1073741824, 2, ".", ",") . " GB";
    } elseif ($Wert > 1048576)
    {
        $Wert = number_format($Wert / 1048576, 2, ".", ",") . " MB";
    } elseif ($Wert > 1024)
    {
        $Wert = number_format($Wert / 1024, 2, ".", ",") . " kB";
    }
    else
    {
        $Wert = number_format($Wert, 2, ".", ",") . " Bytes";
    }

    return $Wert;
}
$frei = disk_free_space("./");
$insgesamt = disk_total_space("./");
$belegt = $insgesamt - $frei;
$prozent_belegt = 100 * $belegt / $insgesamt;
?>
<td align="left" valign="top" style="color:ccc"><span style="color:#FF8700">Server Space:</span><br>
In Use = <b><?php echo ZahlenFormatieren($belegt); ?></b>(<?php echo round($prozent_belegt,"2"); ?> %)<br>
<img src="<?php echo CLASS_DIR ?>bar.php?rating=<?php echo round($prozent_belegt,"2"); ?>" border="0"><br>
Free Space = <b><?php echo ZahlenFormatieren($frei); ?></b><br>
Disk Space = <b><?php echo ZahlenFormatieren($insgesamt); ?></b></td>
<?php
{
    if ($os == "windows")
    {
        $wmi = new COM("Winmgmts://");
        $cpus = $wmi->execquery("SELECT * FROM Win32_Processor");
        echo '<td align="left" valign="top" style="color:ccc"><span style="color:#FF8700">CPU:</span><br>';
        echo 'CPU Load:';
        foreach ($cpus as $cpu)
        {
            echo "" . $cpu->loadpercentage . "%<br />";
        }
        echo '<img src="'.CLASS_DIR.'bar.php?rating=' . round($cpu->loadpercentage, "2") . '" border="0"><br>';
		echo '<span style="color:#FF8700">Server Time: </span>';
        $thetimeis = getdate(time()); 
        $thehour = $thetimeis['hours']; 
        $theminute = $thetimeis['minutes']; 
        $thesecond = $thetimeis['seconds']; 
        if($thehour > 12){ 
            $thehour = $thehour - 12; 
            $dn = "PM"; 
        }else{ 
            $dn = "AM"; 
        } 
		echo "$thehour: $theminute:$thesecond $dn <br>"; 
		echo '<span id="clock"></span>';
		echo '</td>';
    } elseif ($os == "linux")
    {
        function getStat($_statPath)
        {
            if (trim($_statPath) == '')
            {
                $_statPath = '/proc/stat';
            }

            ob_start();
            passthru('cat ' . $_statPath);
            $stat = ob_get_contents();
            ob_end_clean();


            if (substr($stat, 0, 3) == 'cpu')
            {
                $parts = explode(" ", preg_replace("!cpu +!", "", $stat));
            }
            else
            {
                return false;
            }

            $return = array();
            $return['user'] = $parts[0];
            $return['nice'] = $parts[1];
            $return['system'] = $parts[2];
            $return['idle'] = $parts[3];
            return $return;
        }

        function getCpuUsage($_statPath = '/proc/stat')
        {
            $time1 = getStat($_statPath) or die("getCpuUsage(): couldn't access STAT path or STAT file invalid\n");
            sleep(1);
            $time2 = getStat($_statPath) or die("getCpuUsage(): couldn't access STAT path or STAT file invalid\n");

            $delta = array();

            foreach ($time1 as $k => $v)
            {
                $delta[$k] = $time2[$k] - $v;
            }

            $deltaTotal = array_sum($delta);
            $percentages = array();

            foreach ($delta as $k => $v)
            {
                $percentages[$k] = round($v / $deltaTotal * 100, 2);
            }
            return $percentages;
        }
        $cpu = getCpuUsage();
        $cpulast = 100 - $cpu['idle'];
        echo '<td align="left" valign="top" style="color:ccc"><span style="color:#FF8700">CPU:</span><br>';
        echo "CPU Load: " . round($cpulast,"0") . "%<br>";
        echo '<img src="'.CLASS_DIR.'bar.php?rating=' . round($cpulast, "2") . '" border="0"><br>';
		echo '<span style="color:#FF8700">Server Time: </span>';
        $thetimeis = getdate(time()); 
        $thehour = $thetimeis['hours']; 
        $theminute = $thetimeis['minutes']; 
        $thesecond = $thetimeis['seconds']; 
        if($thehour > 12){ 
            $thehour = $thehour - 12; 
            $dn = "PM"; 
        }else{ 
            $dn = "AM"; 
        } 
		echo "$thehour: $theminute:$thesecond $dn <br>"; 
		echo '<span id="clock"></span>';
		echo '</td>';
    } elseif ($os == "nocpu")
    {
        echo "";
    }
    else
    {
        echo 'CPU Load<br>';
        echo "CPU Load: There Was An Error.<br>";
    }
}
?>
</tr>
</table>