<?php
if (!defined('RAPIDLEECH'))
  {
  require_once("index.html");
  exit;
  }

if (($_GET["premium_acc"] == "on" && $_GET["premium_user"] && $_GET["premium_pass"]) || ($_GET["premium_acc"] == "on" && $premium_acc["netload"]["user"] && $premium_acc["netload"]["pass"]))
  {
	$post = array();
	$post["txtuser"] = $_GET["premium_user"] ? $_GET["premium_user"] : $premium_acc["netload"]["user"];
	$post["txtpass"] = $_GET["premium_pass"] ? $_GET["premium_pass"] : $premium_acc["netload"]["pass"];
	$post["txtcheck"] = "login";
	$post["txtlogin"] = "";
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, "/", 0, 0, $post, 0, $_GET["proxy"],$pauth);
	is_page($page);
	preg_match_all('/Set-Cookie: (.*)/i', $page, $cook);
	$cookies = implode(";", $cook[1]);
	preg_match('/(PHPSESSID=.*?;).*(cookie_user=.*?;)/si', $cookies, $finalcook);
	$cookie = $finalcook[1].$finalcook[2];

	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, $cookie, 0, 0, $_GET["proxy"],$pauth);
	
	if(preg_match('/Location: *(.+)/', $page, $redir))
	  {
		$redirect = 'http://netload.in'.$redir[1];
		$Url = parse_url($redirect);
		$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, $cookie, 0, 0, $_GET["proxy"],$pauth);
	  }
	  
	preg_match('/Orange_Link.*href="(.*)"/i', $page, $org_link);
	$Href = $org_link[1];
	$Url = parse_url($Href);
	$FileName = !$FileName ? basename($Url["path"]) : $FileName;
	insert_location("$PHP_SELF?filename=".urlencode($FileName)."&host=".$Url["host"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($link).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "")."&auth=".$auth.($pauth ? "&pauth=$pauth" : ""));
  }
else
  {
$nl = $_POST['nl'];
if($nl == "ok"){
	$post = array();
	$post["file_id"] = $_POST["file_id"];
	$post["captcha_check"] = $_POST["captcha_check"];
	$post["start"] = '';
	$Referer = $_POST["link"];
	$cookie = $_POST["cookie"];

	$Url = parse_url($Referer);
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, $cookie, $post, 0, $_GET["proxy"],$pauth);
	is_page($page);

	if(preg_match('/countdown\(([0-9]+),\'change\(\)\'\)/', $page, $count)){
		$countnum = $count[1]/100;
		insert_timer($countnum, "<b>Timer 2:</b>");
	}else{
		exit('Error[getCountNum]');
	}
	
	if(preg_match('/href="(.+?)".+Click here for the download/', $page, $orng)){
		$orange_link = $orng[1];
	}else{
		exit('Error[getOrangeLink]');
	}
	preg_match('%<h2>download: *(.*)</h2>%', $page, $name);
	
	$Url = parse_url($orange_link);
	$FileName = $name[1];
  
  insert_location("$PHP_SELF?filename=".urlencode($FileName)."&host=".$Url["host"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($link).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "")."&auth=".$auth.($pauth ? "&pauth=$pauth" : ""));

}else{
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, 0, 0, 0, $_GET["proxy"],$pauth);
	is_page($page);
	if(preg_match('/Location: *(.+)/', $page, $redir)){
		  $redirect = 'http://netload.in'.$redir[1];
		  
		  $Url = parse_url($redirect);
		  $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, 0, 0, 0, $_GET["proxy"],$pauth);
	}
	if(preg_match_all('/Set-Cookie: *(.+);/', $page, $cook)){
		$cookie = implode(';', $cook[1]);
	}else{
		exit('<center>Cookie not found.</center>');
	}

	if(preg_match('/href="(index\.php\?.+captcha.+?)"/', $page, $freedl)){
		$flink = str_replace("amp;","",$freedl[1]);
		$free_link = "http://netload.in/".$flink;
	}else{
		exit('Error[getFreeLink]');
	}

	$Url = parse_url($free_link);
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $free_link, $cookie, 0, 0, $_GET["proxy"],$pauth);
	is_page($page);

	if(preg_match('/countdown\(([0-9]+),\'change\(\)\'\)/', $page, $count)){
		$countnum = $count[1]/100;
		insert_timer($countnum, "<b>Timer 1:</b>");
	}else{
		exit('Error[getCountNum]');
	}
	preg_match('/file_id.+value="(.+?)"/', $page, $fileid);
	$file_id = $fileid[1];
	
	preg_match('/action="(index\.php\?id.+?)"/', $page, $linkid);
	$link = "http://netload.in/".$linkid[1];
		
	if(preg_match('%(share/includes/captcha\.php\?.+?)"%', $page, $img)){
		$img_link = "http://netload.in/".$img[1];
		
		$capimg = $PHP_SELF."?image=".urlencode($img_link)."&referer=".urlencode($free_link)."&cookie=".urlencode($cookie);
		/*
		$Url = parse_url($img_link);
		$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $free_link, $cookie, 0, 0, $_GET["proxy"],$pauth);
		
		$headerend = strpos($page,"\r\n\r\n");
		$pass_img = substr($page,$headerend+4);
		write_file($download_dir."netload_captcha.png", $pass_img);
		*/
	}else{
		exit('Error[getIMG-Link]');
	}
	
	print 	"<form method=\"post\" action=\"$PHP_SELF\">$nn";
	print	"<b>Please enter code:</b><br>$nn";
	print	"<img src=\"$capimg\">$nn";
	print	"<input name=\"file_id\" type=\"hidden\" value=\"$file_id\" />";
	print	"<input name=\"link\" value=\"$link\" type=\"hidden\">$nn";
	print	"<input name=\"cookie\" value=\"$cookie\" type=\"hidden\">$nn";
	print	"<input name=\"nl\" value=\"ok\" type=\"hidden\">$nn";
	print	"<input name=\"captcha_check\" type=\"text\" >";
	print	"<input name=\"Submit\" value=\"Submit\" type=\"submit\"></form>";
}
  }
?>