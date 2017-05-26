<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


	if ($_SERVER['REQUEST_METHOD']=='POST' && $_REQUEST['login'] == "entra"){
        $usuario =  $_REQUEST["usuario"];
		$pass =  $_REQUEST["pass"];
    	}

	$usuario = explode("@", $usuario);
	$grupo = explode(".", $usuario[1]);
	$grupo = $grupo[0];
	$usuario= $usuario[0];




	$ldaphost = 'ldap://localhost';
	$ldapport = 389;

	$ldapconn = ldap_connect($ldaphost, $ldapport)
	or die("Could not connect to $ldaphost");
	    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
	    ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
	//ldap_set_option($ds, LDAP_OPT_DEBUG_LEVEL, 7);
	if ($ldapconn) 
	{
	    $username = "cn=$usuario,cn=$grupo,ou=daw2,dc=daw2,dc=net";
	    $upasswd = $pass;

	    $ldapbind = ldap_bind($ldapconn, $username, $upasswd);


	    if ($ldapbind) 
		{
			

		$ldaptree = $username;

		$result = ldap_search($ldapconn,$ldaptree, "(cn=*)") or die ("Error in search query: ".ldap_error($ldapconn));
		$data = ldap_get_entries($ldapconn, $result);
	       
		// SHOW ALL DATA
		echo '<h1>Benvingut ' . $data[0]["cn"][0] . '</h1><pre>';

		echo "dn is: ". $data[0]["dn"] ."<br />";
		echo "User: ". $data[0]["cn"][0] ."<br />";
		echo "Uid number: ". $data[0]["uidnumber"][0] ."<br />";
		echo "Gid number: ". $data[0]["gidnumber"][0] ."<br />";
		echo "SN: ". $data[0]["sn"][0] ."<br />";
		echo "Home directory: ". $data[0]["homedirectory"][0] ."<br />";
		echo "Hash Password: ". $data[0]["userpassword"][0] ."<br />";
		echo '</pre>';
	       	



		}
    else 
        { 
			header("Location: index.php?error='El usuario es incorrecto'");
			echo "No existe el usuario";
			exit();
		}


}


?>



<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Login Form</title>
  
  
  
      <link rel="stylesheet" href="css/style.css">

  
</head>

<body>
  <body>

	<form method="POST" action ="canviapass.php?usuari=<?php echo $data[0]["dn"]; ?> ">
		<input type = "submit" name= "canviapass" id = "canviapass" value = "modifica password">
	</form>
</body>
  
  
</body>
</html>
