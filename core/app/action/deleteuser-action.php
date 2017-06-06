<?php

if(isset($_GET["id"])){
	$delUser = UserData::delById($_GET["id"]);
}

Core::redir("./?view=users");


?>