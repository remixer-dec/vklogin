<?php 
define("APPID","123456"); 									// ID приложения (замените на свой)
define("APPKEY","qWeRtYiOpAsd");							// Secret key приложения (замените на свой)
define("VKONLY",true); 									//при TRUE просмотр страниц невозможен без авторизации вк
define("WHITELIST",false); 								// при TRUE доступ разрешается только для ID из массива
if(!isset($whitelist)){
$whitelist = 
  [
    38367479,
    1
  ];
}
function errgen($e="ошибка доступа / access denied",$d=true){ 	//генерация ошибок
	echo "<center id='aerrmsg'>$e</center><center id='vk_auth' onclick='location.search=\"\"'>назад / back</center>";
	if($d) die();
}
function checkwl($uid,$d=true){								//проверка ID в белом списке
	$uid=intval($uid);
	global $whitelist;
	if(WHITELIST){
		if(in_array($uid,$whitelist)){
			return true;
		}
		else{
			return false;
			if($d) errgen();
		}
	}
	else return true;
}

function echoforlogged($s,$e){ 								// вывод данных только для залогиненых пользователей. Если включен белый список, он учитывается
	if(vklogged()){
		if(checkwl($_COOKIE['vkuid'],false)){
			echo $s;
		}
		else echo ($e);
	}
	else echo ($e);
}

function vklogged(){ 										// проверка на подлинность входа
	 if($_COOKIE["vkhash"]==md5(APPID.$_COOKIE['vkuid'].APPKEY)){
	 	return true;
	 	}
	 	else return false;
}
if(isset($_GET['logout'])){									//выход из системы 
	 setcookie("vkhash", "", time()-3600);
	 setcookie("vkuid", "", time()-3600);
	 header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]"); 
	 die();
}
if(!isset($_COOKIE['vkhash']) or isset($_GET['vklogin'])){ 
	
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
		<meta charset=utf-8>
		<title>VK Login</title>
		<style>
			body{
				margin:0;
				padding:0;
				background:url("https://rawgit.com/subtlepatterns/SubtlePatterns/gh-pages/p5.png");
				width:100%;
				height:100%;
			}
			#vk_auth{
				width:200px;
				height:140px;
				margin:300px auto;
				text-align:center;
			}
			#aerrmsg,#asuccess{
				color:white;
				background:#D91E18 ;
				font-family: "Trebuchet MS", Open Sans, monospace;
				text-transform:uppercase;
				height:21px;
				font-size:20px;
				line-height:1;
			}
			#asuccess{
				background:#3FC380;
			}
		</style>
<?php
  if(!isset($_GET['vkhash']) and VKONLY==true or isset($_GET['vklogin'])){
?>
		<script type="text/javascript" src="//vk.com/js/api/openapi.js?117"></script>
		<script type="text/javascript">
			 VK.init({apiId: <?php echo APPID; ?>});
		</script>
	</head>
	<body>
		<div id="vk_auth"></div>
		<script type="text/javascript">
			VK.Widgets.Auth("vk_auth", {width: "200px", onAuth: function(data) {
			location.href="?vkhash="+data.hash+"&vkuid="+data.uid;
			} });
		</script>
	</body>
</html>
<? 
die();
}
else if(isset($_GET['vkhash'])){
	if($_GET['vkhash']==md5(APPID.$_GET['vkuid'].APPKEY)){
		 checkwl($_GET['vkuid']);
		 setcookie("vkhash",$_GET['vkhash'],strtotime('+7 days'));
		 setcookie("vkuid",$_GET['vkuid'],strtotime('+7 days'));
		die("<center id='asuccess'> вы успешно вошли / login success</center>
		<script>setTimeout(function(){document.location.replace(window.location.pathname)},600)</script>
		");
	}
	else errgen("ошибка авторизации / auth error");
}
} else{
	if(vklogged()){
		checkwl($_COOKIE['vkuid']);
		echo "<!-- logged via vk -->";
	} else if(VKONLY) errgen("ошибка авторизации / auth error");
}
 ?>
