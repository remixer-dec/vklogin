<?php
switch($_GET['v']){
    case '1':
        require_once("vklogin.php");
        echo "This content is not avilable without login";
    break;
    case '2':
        define("VKONLY",false); 
        require_once("vklogin.php");
        echo "This content is avilable without login<br>";
        echoforlogged("This content is not avilable without login<br><br><a href='http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]&logout'>logout</a>","Please  <a href='http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]&vklogin'>log in</a> to see this text");
    break;
    case '3':
        define("VKONLY",false);
        define("WHITELIST",true); 
        require_once("vklogin.php");
        $whitelist=[1,2,3,4];
        if(vklogged()){
            echo "this text is visible only for logged users<br>";
            if(checkwl($_COOKIE['vkuid'])){
                echo "this text is visible only for whitelisted users";
            }
            else{
                echo "you are not in the whitelist";
            }
        }
        else{
            echo "You have to be <a href='http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]&vklogin'>logged</a> to see this text";
        }
    break;
    default:
        define("VKONLY",false); 
        require_once("vklogin.php");
        echo "<a href='?v=1'>demo 1</a><br>";
        echo "<a href='?v=2'>demo 2</a><br>";
        echo "<a href='?v=3'>demo 3</a><br>";
    break;
}
?>