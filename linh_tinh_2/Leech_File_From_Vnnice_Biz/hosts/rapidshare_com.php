<?php
if (!defined('RAPIDLEECH'))
  {
  require_once("index.html");
  exit;
  }

if (($_GET["premium_acc"] == "on" && $_GET["premium_user"] && $_GET["premium_pass"]) || ($_GET["premium_acc"] == "on" && $premium_acc["rs_com"]["user"] && $premium_acc["rs_com"]["pass"]))
	{
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, 0, 0, 0, $_GET["proxy"],$pauth);
	is_page($page);
					
	is_present($page,"The file could not be found.", "The file could not be found. Please check the download link.");
	is_present($page,"Due to a violation of our terms of use, the file has been removed from the server.");
	is_present($page,"This file is suspected to contain illegal content and has been blocked.");
	is_present($page,"The uploader has removed this file from the server.");
	is_present($page,"This file has been removed from the server, because the file has not been accessed in a long time.");
	is_present($page,"is momentarily not available", "This server is momentarily not available.  We are aware of this and are working to get this resolved.");
	is_present($page,"unavailable due to hardware-problems", "Server unavailable due to hardware-problems");
					
	$FileName = basename(trim(cut_str($page, '<form action="', '"')));
	!$FileName ? $FileName = basename($Url["path"]) : "";
	$auth = $_GET["premium_user"] ? base64_encode($_GET["premium_user"].":".$_GET["premium_pass"]) : base64_encode($premium_acc["rs_com"]["user"].":".$premium_acc["rs_com"]["pass"]);
					
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, 0, 0, 0, $_GET["proxy"],$pauth, $auth);
	is_page($page);
	is_present($page,"Account found, but password is incorrect");
	is_present($page,"Account not found");

	if (stristr($page, "Location:"))
		{
		$Href = trim(cut_str($page, "Location:","\n"));
		$Url =  parse_url($Href);
	               	
	 	insert_location("$PHP_SELF?filename=".urlencode($FileName)."&host=".$Url["host"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($LINK).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "")."&auth=".$auth.($pauth ? "&pauth=$pauth" : ""));
		}
	else
		{
		html_error("Cannot use premium account", 0);
		}
	}
else
	{
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, 0, 0, 0, $_GET["proxy"],$pauth);
	is_page($page);
	
	is_present($page,"Due to a violation of our terms of use, the file has been removed from the server.");
	is_present($page,"This file is suspected to contain illegal content and has been blocked.");
	is_present($page,"The file could not be found.", "The file could not be found. Please check the download link.");
	is_present($page,"The uploader has removed this file from the server.");
	is_present($page,"This file has been removed from the server, because the file has not been accessed in a long time.");
	is_present($page,"is momentarily not available", "This server is momentarily not available.  We are aware of this and are working to get this resolved.");
	is_present($page,"unavailable due to hardware-problems", "Server unavailable due to hardware-problems");
	is_present($page, "is already downloading a file","Your IP-address is already downloading a file, Please wait until the download is completed.");
					
	$post = array();
	$post["dl.start"] = "Free";
					
	$Href = trim(cut_str($page, '<form action="', '"'));
	$refimg = $Href;
	$Url = parse_url($Href);
					
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : "") ,$LINK , 0, $post, 0, $_GET["proxy"],$pauth);
	is_page($page);
		
	is_present($page, "is not allowed to use the free-service anymore today","No more free downloads from this IP today");
	is_present($page, "This file exceeds your download-limit","Download limit exceeded");
	is_present($page, "is already downloading a file","Your IP-address is already downloading a file, Please wait until the download is completed.");
					
	if (stristr($page, "Would you like to download more?"))
		{
		$minutes = trim(cut_str($page, "Alternatively you can wait ", " minutes."));
		if ($minutes)
			{
			html_error("Download limit exceeded. You have to wait <font color=black>$minutes</font> minute(s) until the next download.", 0);
			}
		else
			{
			html_error("Download limit exceeded.", 0);
			}
		}
				
	if(stristr($page, "Too many users downloading right now") || stristr($page, "Too many connections"))
		{
		html_error("Too many users downloading right now", 0);
		}
	/*
	if(preg_match('/Happy Hour/', $page)){
		$rhh = 'active';
	}
	*/
	$countDown = trim(cut_str($page, "var c=", ";"));
	
	//preg_match('/Security Question.*<p>(.*)?<br>/', $page, $security_q);
	preg_match('%<form name="dlf?".*</form>%s', $page, $form_content);
	$middle_str = str_replace("\\", "", preg_replace('/(\' *\+.*?(\r\n)*.*?\'|display:none;)/s', '', $form_content[0]));
	$code = '<center>'.trim($middle_str);
	/*
	$code = str_replace('name="dl"', 'name="dlf"', $code);
	if($rhh != 'active'){
		//$code = str_replace('.png"', '.png" style="background-color:#FFF"', $code);	
		$access_image_url = "http://rs".trim(cut_str($code,'<img src="http://rs','">'));
		
		$Url = parse_url($access_image_url);
		$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $refimg, 0, 0, 0, $_GET["proxy"],$pauth);
		
		$headerend = strpos($page,"\r\n\r\n");
		$pass_img = substr($page,$headerend+4);
		write_file($download_dir."rscaptchaimg.jpg", $pass_img);
		
		if ($access_image_url == "http://rs")
		  {
		  html_error('Error getting access image url', 0);
		  }	
		  
		if ($images_via_php === true)
			{
			$code = str_replace($access_image_url, $PHP_SELF."?image=http://rs".urlencode(trim(cut_str($code, '<img src="http://rs', '">')))."&referer=".urlencode($refimg), $code);
			}
		$code = str_replace($access_image_url, "{$download_dir}rscaptchaimg.jpg?".rand(1000, 100000), $code);
	}else{
		$code = preg_replace('%<script type="text/javascript">|<!--|</script>|var text = \'|document\.write\(text\);|//-->|\';[^"]%s', '', $code);
	}
	*/
	$FileAddr = trim(cut_str($code, '<form name="dlf" action="', '"'));
	//preg_match('/<form name="dlf?.*(http:.*?)"/', $code, $FAddr);
	//$FileAddr = $FAddr[1];
	$Href = parse_url($FileAddr);
	$FileName = basename($Href["path"]);
				
	if (!$FileAddr)
		{
		html_error("Error getting download link", 0);
		}
				
	$code = str_replace($FileAddr, $PHP_SELF, $code);
	$code = preg_replace('/<input type=image.*?".*?>/', '<input type=submit value=Download  onclick="return check()">', $code);
	$code = preg_replace('%<div><img.*Advanced download settings</div>%s', '', $code);
	
	preg_match_all("/http:\/\/rs(.*).rapidshare.com\/(.*)".$FileName."/iU", $code, $matches);

	if (!$matches)
		{
		html_error("Error getting available server's list", 0);
		}
					
	for ($i = 0; $i < count($matches[0]); $i++)
		{
		$Url = parse_url($matches[0][$i]);
		$code = str_replace("document.dlf.action='".$matches[0][$i], "document.dlf.host.value='".$Url["host"], $code);
		}
	
	$code = str_replace("</form>", $nn, $code);
	
	$code.= "<input type=\"hidden\" name=\"filename\" value=\"".urlencode($FileName)."\">$nn<input type=\"hidden\" name=\"link\" value=\"".urlencode($LINK)."\">$nn<input type=\"hidden\" name=\"referer\" value=\"".urlencode($Referer)."\">$nn<input type=\"hidden\" name=\"saveto\" value=\"".$_GET["path"]."\">$nn<input type=\"hidden\" name=\"host\" value=\"".$Href["host"]."\">$nn<input type=\"hidden\" name=\"path\" value=\"".urlencode($Href["path"])."\">$nn";
			
	$code.= ($_GET["add_comment"] == "on" ? "<input type=\"hidden\" name=\"comment\" value=\"".urlencode($_GET["comment"])."\">$nn" : "")."<input type=\"hidden\" name=\"email\" value=\"".($_GET["domail"] ? $_GET["email"] : "")."\">$nn<input type=\"hidden\" name=\"partSize\" value=\"".($_GET["split"] ? $_GET["partSize"] : "")."\">$nn";
	$code.= "<input type=\"hidden\" name=\"method\" value=\"".$_GET["method"]."\">$nn<input type=\"hidden\" name=\"proxy\" value=\"".($_GET["useproxy"] ? $_GET["proxy"] : "")."\">$nn".($pauth ? "<input type=\"hidden\" name=\"pauth\" value=\"".$pauth."\">$nn" : "");
	$code.= "</form></center>";
	
	$js_code = "<script language=\"JavaScript\">".$nn."function check() {".$nn."var imagecode=document.dlf.accesscode.value;".$nn."var path=document.dlf.path.value;".$nn;
  $js_code.= 'if (imagecode == "") { window.alert("You didn\'t enter the image verification code"); return false; }'.$nn.'else {'.$nn.'document.dlf.path.value=path+escape("?accesscode="+imagecode);'.$nn.'return true; }'.$nn.'}'.$nn.'</script>'.$nn;
  
	
	if (!$countDown)
		{
		print $code.$nn.$nn.$js_code."$nn</body>$nn</html>";
		}
	else
		{
		insert_new_timer($countDown, rawurlencode($code), "Download-Ticket reserved.", $js_code);
		}
	}
?>