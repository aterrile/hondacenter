<?php

if(count($_POST)>0){

	$user = new PersonData();
	$user->no = $_POST["no"];
	$user->name = $_POST["name"];
	$user->lastname = $_POST["lastname"];
	$user->address1 = $_POST["address1"];
    $user->ciudad = $_POST["ciudadCliente"];
    $user->comuna = $_POST["comunaCliente"];
    $user->giro = $_POST["giroCliente"];
	$user->email1 = $_POST["email1"];
	$user->phone1 = $_POST["phone1"];
	$user->credit_limit = 0;


	$user->is_active_access = 0;
	$user->has_credit = 0;
	$user->password = "";

	$user->add_client();

print "<script>window.location='index.php?view=clients';</script>";


}


?>
