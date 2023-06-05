<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

# FUNCTIONS


function randomString($chars=10) { //generate random string
	$characters = '0123456789abcdef';
	$randstring = '';
	for ($i = 0; $i < $chars; $i++) { $randstring .= $characters[rand(0, strlen($characters) -1)]; }
	return $randstring;
}
// Old Encrypt Function
function mc_encrypt($encrypt,$key){
	global $config;
    $encrypt = serialize($encrypt);
    $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
    $key = pack('H*', $key);
    $mac = hash_hmac('sha256', $encrypt, substr(bin2hex($key), -32));
    $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt.$mac, MCRYPT_MODE_CBC, $iv);
    $encoded = base64_encode($passcrypt).'|'.base64_encode($iv);
    return $encoded;
}

// Old Decrypt Function
function mc_decrypt($decrypt,$key){
    $decrypt = explode('|', $decrypt.'|');
    $decoded = base64_decode($decrypt[0]);
    $iv = base64_decode($decrypt[1]);
    if(strlen($iv)!==mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)){ return false; }
    $key = pack('H*', $key);
    $decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_CBC, $iv));
    $mac = substr($decrypted, -64);
    $decrypted = substr($decrypted, 0, -64);
    $calcmac = hash_hmac('sha256', $decrypted, substr(bin2hex($key), -32));
    if($calcmac!==$mac){ return false; }
    $decrypted = unserialize($decrypted);
    return $decrypted;
}

// New encryption/decryption with OpenSSL
function encrypt_decrypt($action, $string, $key, $iv) {
	//global $config;
	$secret_key = $key;
	$secret_iv = $iv;

    $output = false;
    $encrypt_method = "AES-256-CBC";

    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if ( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if( $action == 'decrypt' ) {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}



$latestversion = 1.15;
$status = 'ok';

# LOAD CONFIGURAGION FILE
if(file_exists("../config.php")) {
	require('../config.php');
	if(isset($DBconfig)) $config = $DBconfig;
}

else { $status = 'noconfig'; }

if($status == "ok") {
    if(!is_writable("../config.php")) $status = 'configunw';
}

if($status == 'ok') {
    # INITIALIZE MEDOO
    require('../vendor/classes/class.medoo.php');
    $database = new medoo($config);
    $currentversion = $database->get("config", "value", [ "name" => "db_version" ]);

    // UPGRADE to 1.1
    if($currentversion == 1.0) {
		require('upgrade-scripts/1.0-1.1.php');

        $status = 'updated';
        $currentversion = $database->get("config", "value", [ "name" => "db_version" ]);
    }

    // UPGRADE TO 1.2
    if($currentversion == 1.1) {
		require('upgrade-scripts/1.1-1.2.php');

		$status = 'updated';
        $currentversion = $database->get("config", "value", [ "name" => "db_version" ]);
    }


    // UPGRADE TO 1.3
    if($currentversion == 1.2) {
		require('upgrade-scripts/1.2-1.3.php');

		$status = 'updated';
        $currentversion = $database->get("config", "value", [ "name" => "db_version" ]);
    }


    // UPGRADE TO 1.4
    if($currentversion == 1.3) {
		require('upgrade-scripts/1.3-1.4.php');

		$status = 'updated';
        $currentversion = $database->get("config", "value", [ "name" => "db_version" ]);
    }



    // UPGRADE TO 1.5
    if($currentversion == 1.4) {
		require('upgrade-scripts/1.4-1.5.php');

		$status = 'updated';
        $currentversion = $database->get("config", "value", [ "name" => "db_version" ]);
    }


    // UPGRADE TO 1.6
    if($currentversion == 1.5) {
		require('upgrade-scripts/1.5-1.6.php');

		$status = 'updated';
        $currentversion = $database->get("config", "value", [ "name" => "db_version" ]);
    }


    // UPGRADE TO 1.7
    if($currentversion == 1.6) {
		require('upgrade-scripts/1.6-1.7.php');

		$status = 'updated';
        $currentversion = $database->get("config", "value", [ "name" => "db_version" ]);
    }

	// UPGRADE TO 1.8
	if($currentversion == 1.7) {
		require('upgrade-scripts/1.7-1.8.php');

		$status = 'updated';
		$currentversion = $database->get("config", "value", [ "name" => "db_version" ]);
	}

	// UPGRADE TO 1.9
	if($currentversion == 1.8) {
		require('upgrade-scripts/1.8-1.9.php');

		$status = 'updated';
		$currentversion = $database->get("config", "value", [ "name" => "db_version" ]);
	}

	// UPGRADE TO 1.10
	if($currentversion == 1.9) {
		require('upgrade-scripts/1.9-1.10.php');

		$status = 'updated';
		$currentversion = $database->get("config", "value", [ "name" => "db_version" ]);
	}

	// UPGRADE TO 1.11
	if($currentversion == 1.10) {
		require('upgrade-scripts/1.10-1.11.php');

		$status = 'updated';
		$currentversion = $database->get("config", "value", [ "name" => "db_version" ]);
	}

	// UPGRADE TO 1.12
	if($currentversion == 1.11) {
		require('upgrade-scripts/1.11-1.12.php');

		$status = 'updated';
		$currentversion = $database->get("config", "value", [ "name" => "db_version" ]);
	}

	// UPGRADE TO 1.13
	if($currentversion == 1.12) {
		require('upgrade-scripts/1.12-1.13.php');

		$status = 'updated';
		$currentversion = $database->get("config", "value", [ "name" => "db_version" ]);
	}

	// UPGRADE TO 1.14
	if($currentversion == 1.13) {
		require('upgrade-scripts/1.13-1.14.php');

		$status = 'updated';
		$currentversion = $database->get("config", "value", [ "name" => "db_version" ]);
	}

	// UPGRADE TO 1.15
	if($currentversion == 1.14) {
		require('upgrade-scripts/1.14-1.15.php');

		$status = 'updated';
		$currentversion = $database->get("config", "value", [ "name" => "db_version" ]);
	}

	// UPGRADE TO 1.16
	if($currentversion == 1.15) {
		require('upgrade-scripts/1.15-1.16.php');

		$status = 'updated';
		$currentversion = $database->get("config", "value", [ "name" => "db_version" ]);
	}


}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>onTrack Update</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="shortcut icon" href="../template/assets/icon.png"/>
        <link rel="apple-touch-icon image_src" href="../template/assets/icon-large.png"/>
        <link href="../template/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
		<link href="../template/assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    </head>
  <body class="login-page">
    <div class="login-box">
      <div class="login-logo">
        <b>on</b>Track Update
      </div><!-- /.login-logo -->
      <div class="login-box-body">

          <?php if($status == "ok"): ?>
                        <p class="login-box-msg">Nothing to do, database is already at latest version.</p>
          <?php endif; ?>
          <?php if($status == "noconfig"): ?>
                        <p class="login-box-msg">Configuration file is missing.</p>
          <?php endif; ?>
          <?php if($status == "configunw"): ?>
                        <p class="login-box-msg">Configuration file (config.php) must be writable for the upgrade process to complete.</p>
          <?php endif; ?>
          <?php if($status == "updated"): ?>
                        <p class="login-box-msg">Update complete!<br>Please delete the "install" folder.</p>
          <?php endif; ?>

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->



    <!-- jQuery 2.1.3 -->
    <script src="../template/assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="../template/assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

  </body>


</html>
