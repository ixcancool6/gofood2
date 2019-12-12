<?php


include ("function.php");

function nama()
	{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://ninjaname.horseridersupply.com/indonesian_name.php");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$ex = curl_exec($ch);
	// $rand = json_decode($rnd_get, true);
	preg_match_all('~(&bull; (.*?)<br/>&bull; )~', $ex, $name);
	return $name[2][mt_rand(0, 14) ];
	}
function register($no)
	{
	$nama = nama();
	$email = str_replace(" ", "", $nama) . mt_rand(100, 999);
	$data = '{"name":"' . nama() . '","email":"' . $email . '@gmail.com","phone":"+' . $no . '","signed_up_country":"ID"}';
	$register = request("/v5/customers", "", $data);
	//print_r($register);
	if ($register['success'] == 1)
		{
		return $register['data']['otp_token'];
		}
	  else
		{
		return false;
		}
	}
function verif($otp, $token)
	{
	$data = '{"client_name":"gojek:cons:android","data":{"otp":"' . $otp . '","otp_token":"' . $token . '"},"client_secret":"83415d06-ec4e-11e6-a41b-6c40088ab51e"}';
	$verif = request("/v5/customers/phone/verify", "", $data);
	if ($verif['success'] == 1)
		{
		return $verif['data']['access_token'];
		}
	  else
		{
		return false;
		}
	}
	function login($no)
	{
	$nama = nama();
	$email = str_replace(" ", "", $nama) . mt_rand(100, 999);
	$data = '{"phone":"+'.$no.'"}';
	$register = request("/v4/customers/login_with_phone", "", $data);
	print_r($register);
	if ($register['success'] == 1)
		{
		return $register['data']['login_token'];
		}
	  else
		{
		return false;
		}
	}
function veriflogin($otp, $token)
	{
	$data = '{"client_name":"gojek:cons:android","client_secret":"83415d06-ec4e-11e6-a41b-6c40088ab51e","data":{"otp":"'.$otp.'","otp_token":"'.$token.'"},"grant_type":"otp","scopes":"gojek:customer:transaction gojek:customer:readonly"}';
	$verif = request("/v4/customers/login/verify", "", $data);
	if ($verif['success'] == 1)
		{
		return $verif['data']['access_token'];
		}
	  else
		{
		return false;
		}
	}

	//Claim Function for 10-20k
function claim20k($token)
	{
	$data = '{"promo_code":"GOFOODSANTAI19"}';
	$claim = request("/go-promotions/v1/promotions/enrollments", $token, $data);
	if ($claim['success'] == 1)
		{
		return true;
		}
	  else
		{
		return false;
		}
	}

	function claim15k($token)
	{
	$data = '{"promo_code":"GOFOODSANTAI11"}';
	$claim = request("/go-promotions/v1/promotions/enrollments", $token, $data);
	if ($claim['success'] == 1)
		{
		return true;
		}
	  else
		{
		return false;
		}
	}

	function claim10k($token)
	{
	$data = '{"promo_code":"GOFOODSANTAI08"}';
	$claim = request("/go-promotions/v1/promotions/enrollments", $token, $data);
	if ($claim['success'] == 1)
		{
		return true;
		}
	  else
		{
		return false;
		}
	}

	function claimPraktis($token)
	{
	$data = '{"promo_code":"GOFOODPRAKTIS19"}';
	$claim = request("/go-promotions/v1/promotions/enrollments", $token, $data);
	if ($claim['success'] == 1)
		{
		return true;
		}
	  else
		{
		return false;
		}
	}

echo "Create by Akm Tamvan and improved by IXCN. Scare?\n";
echo "Choose Login or Register? Login = 1 & Register = 2: ";
$type = trim(fgets(STDIN));
if($type == 2){
echo "It's Register Way\n";
echo "Input 62 For ID and 1 For US Phone Number\n";
echo "Enter Number: ";
$nope = trim(fgets(STDIN));
$register = register($nope);
if ($register == false)
	{
	echo "Failed to Get OTP, Use Unregistered Number!\n";
	}
  else
	{
	echo "Enter Your OTP: ";
	// echo "Enter Number: ";
	$otp = trim(fgets(STDIN));
	$verif = verif($otp, $register);
	if ($verif == false)
		{
		echo "Failed to Registering Your Number!\n";
		}
	  else
		{
		echo "Ready to Claim\n";
		$claim20 = claim20k($verif);
		sleep(5);
		$claim15 = claim15k($verif);
		sleep(5);
		$claimPraktis = claimPraktis($verif);
		sleep(5);
		$claim10 = claim10k($verif);
		  
		if ($claim20 == true)
			{
			echo "Success to claim 20k Voucher GOFOODSANTAI19.\n";
			echo "Congratulations you are so lucky !\n";
		}elseif ($claim15 == true)
		{
			echo "Success to claim 15k Voucher GOFOODSANTAI11.\n";
			echo "Well not bad, you had a decent luck !\n";
		}elseif ($claim10 == true)
		{
			echo "Success to claim 10k Voucher GOFOODSANTAI08.\n";
			echo "Better luck next time !\n";
		}else
		{
			echo "Either you had no luck or the voucher is expired.\n";
		}

		if ($claimPraktis == true)
		{
			echo "Succes to claim 20k Voucher from GOFOODPRAKTIS19.\n";
		}else{
			echo "fail to claim Voucher from GOFOODPRAKTIS19. \n";
		}
	}
}
}else if($type == 1){
echo "It's Login Way\n";
echo "Input 62 For ID and 1 For US Phone Number\n";
echo "Enter Number: ";
$nope = trim(fgets(STDIN));
$login = login($nope);
if ($login == false)
	{
	echo "Failed to Get OTP!\n";
	}
  else
	{
	echo "Enter Your OTP: ";
	// echo "Enter Number: ";
	$otp = trim(fgets(STDIN));
	$verif = veriflogin($otp, $login);
	if ($verif == false)
		{
		echo "Failed to Login with Your Number!\n";
		}
	  else
		{
		echo "Ready to Claim\n";
		$claim20 = claim20k($verif);
		$claim15 = claim15k($verif);
		$claim10 = claim10k($verif);
		if ($claim20 == true)
			{
			echo "Success to claim 20k Voucher.\n";
			echo "Congratulations you are so lucky !\n";
		}elseif ($claim15 == true)
		{
			echo "Success to claim 15k Voucher.\n";
			echo "Well not bad, you had a decent luck !\n";
		}elseif ($claim10 == true)
		{
			echo "Success to claim 10k Voucher.\n";
			echo "Better luck next time !\n";
		}else
		{
			echo "Either you had no luck or the voucher is expired.\n";
		}
		}
	}	
}
?>
