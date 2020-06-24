<?php
require 'dbc.php';

function getCookiesMail($email) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://clipmails.com/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);

    $headers = array();
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:76.0) Gecko/20100101 Firefox/76.0';
    $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8';
    $headers[] = 'Accept-Language: en-US,en;q=0.5';
    $headers[] = 'Cookie: PHPSESSID=dvcl5t269kvtrqjoe59mgs3sf3;';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($response, 0, $header_size);
    $body = substr($response, $header_size);

    curl_close($ch);

    preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $header, $outCookie);
    $cookies = '';
    foreach($outCookie[1] as $outCookies) {
        $cookies .= $outCookies.'; ';
    }

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://clipmails.com/user.php?user='.urlencode($email));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);

    $headers = array();
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:76.0) Gecko/20100101 Firefox/76.0';
    $headers[] = 'Accept: */*';
    $headers[] = 'Accept-Language: en-US,en;q=0.5';
    $headers[] = 'X-Requested-With: XMLHttpRequest';
    $headers[] = 'Referer: https://clipmails.com/';
    $headers[] = 'Cookie: PHPSESSID=dvcl5t786kvtrqjoe59mgs3sf3; '.$cookies;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($response, 0, $header_size);
    $body = substr($response, $header_size);

    curl_close($ch);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://clipmails.com/actions.php?action=saveEMails');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);

    $headers = array();
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:76.0) Gecko/20100101 Firefox/76.0';
    $headers[] = 'Accept: */*';
    $headers[] = 'Accept-Language: en-US,en;q=0.5';
    $headers[] = 'X-Requested-With: XMLHttpRequest';
    $headers[] = 'Referer: https://clipmails.com/';
    $headers[] = 'Cookie: PHPSESSID=dvcl5t269kvtrqjoe59mgs3sf3; '.$cookies;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($response, 0, $header_size);
    $body = substr($response, $header_size);

    curl_close($ch);
    
    preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $header, $outCookie);
    foreach($outCookie[1] as $outCookies) {
        $cookies .= $outCookies.'; ';
    }

    return 'PHPSESSID=dvcl5t269kvtrqjoe59mgs3sf3; '.$cookies;
}

// Instalasi
$username_dbc = '';
$password_dbc = '';
$total = 1000;

$client = new DeathByCaptcha_HttpClient($username_dbc, $password_dbc);
$data = array(
    'googlekey' => '6LedY2IUAAAAAK-55KzamdPZeFh6fbZnDcRhzDLE',
    'pageurl' => 'https://www.pointblank.id/member/signup'
);
$json = json_encode($data);
$extra = [
    'type' => 4,
    'token_params' => $json,
];

for($i = 1; $i <= $total; $i++) {
    echo "Mendapatkan captcha..\n";
    if ($captcha = $client->decode(null, $extra)) {
        sleep(DeathByCaptcha_Client::DEFAULT_TIMEOUT);
    
        if ($text = $client->get_text($captcha['captcha'])) {
            $id = "tokin".rand(1111, 9999);
            $ps = $id."S@1";
            $listDomain = 'temp-email.info';
            $email = $id."@".$listDomain;
            $cooks = getCookiesMail($email);

            echo "Berhasil mendapatkan captcha!\n";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://www.pointblank.id/member/signup");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            $response = curl_exec($ch);

            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $header_size);
            $body = substr($response, $header_size);
            curl_close($ch);

            preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $header, $outCookie);
            $cookies = '';
            foreach($outCookie[1] as $outCookies) {
                $cookies .= $outCookies.'; ';
            }

            echo "Validasi ID : ";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://www.pointblank.id/member/IdCheck?id=$id");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0',
                'Accept: application/json, text/javascript, */*; q=0.01',
                'Accept-Language: en-US,en;q=0.5',
                'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
                'X-Requested-With: XMLHttpRequest',
                'Referer: https://www.pointblank.id/member/signup',
                'Cookie: '.$cookies
            ]);
            $result = curl_exec($ch);
            curl_close($ch);
            if(json_decode($result)->resultCode == "0") {
                echo "Bisa digunakan\n";
                echo "Validasi dan Send OTP ke Email : ";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://www.pointblank.id/member/email/check?email=$email");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0',
                    'Accept: application/json, text/javascript, */*; q=0.01',
                    'Accept-Language: en-US,en;q=0.5',
                    'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
                    'X-Requested-With: XMLHttpRequest',
                    'Referer: https://www.pointblank.id/member/signup',
                    'Cookie: '.$cookies
                ]);
                $result = curl_exec($ch);
                if(json_decode($result)->resultCode == "0") {
                    echo "Email bisa digunakan, dan menunggu verif email..\n";
                    
                    $get = true;
                    $otp = '';
                    $iis = 1;
                    while(true) {
                        if($iis == 60) {
                            $get = false;
                            break;
                        }
                        $ch = curl_init();
    
                        curl_setopt($ch, CURLOPT_URL, 'https://clipmails.com/mail.php?unseen=1');
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_HEADER, 1);
                    
                        $headers = array();
                        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:76.0) Gecko/20100101 Firefox/76.0';
                        $headers[] = 'Accept: */*';
                        $headers[] = 'Accept-Language: en-US,en;q=0.5';
                        $headers[] = 'X-Requested-With: XMLHttpRequest';
                        $headers[] = 'Referer: https://clipmails.com/';
                        $headers[] = 'Cookie: '.$cooks;
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    
                        $response = curl_exec($ch);
                        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                        $header = substr($response, 0, $header_size);
                        $body = substr($response, $header_size);
                    
                        curl_close($ch);

                        if($body != "") {
                            if(preg_match('#PT. Zepetto Interactive Indonesia#', $body)) {
                                preg_match('#">(.*?)</strong></td>#', $body, $outs);
                                $otp = trim($outs[1]);
                                break;
                            }
                        }
                        $iis++;
                    }
                    if($get == false) {
                        echo  "Gagal mengambil otp email..\n\n";
                        continue;
                    }
                    if($otp != '') {
                        echo "OTP ditemukan [ $otp ]\n";
                        echo "Menvalidasi email : ";
                        
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, "https://www.pointblank.id/member/email/otp/process?email=$email&code=$otp");
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0',
                            'Accept: application/json, text/javascript, */*; q=0.01',
                            'Accept-Language: en-US,en;q=0.5',
                            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
                            'X-Requested-With: XMLHttpRequest',
                            'Referer: https://www.pointblank.id/member/signup',
                            'Cookie: '.$cookies
                        ]);
                        $result = curl_exec($ch);
                        if(json_decode($result)->resultCode == "0") {
                            $resultData = json_decode($result)->resultData;
                            echo "Sukses\n";

                            echo "Memulai register : ";
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, "https://www.pointblank.id/member/process");
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, "_viewstate=$resultData&userid=$id&password=$ps&repassword=$ps&email=".urlencode($email)."&code=$otp&g-recaptcha-response=$text");
                            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0',
                                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                                'Accept-Language: en-US,en;q=0.5',
                                'Content-Type: application/x-www-form-urlencoded',
                                'Referer: https://www.pointblank.id/member/signup',
                                'Cookie: '.$cookies
                            ]);
                            $result = curl_exec($ch);
                            if(preg_match('#complete#', $result)) {
                                echo "Berhasil.\n";
                                file_put_contents('list.txt', "$id|$ps\n", FILE_APPEND | LOCK_EX);
                                file_put_contents('data_full.txt', "Email : $email\nID : $id\nPassword : $ps\n-\n", FILE_APPEND | LOCK_EX);
                                echo "Memulai pengambilan kode..\n";
                                $ch = curl_init();

                                curl_setopt($ch, CURLOPT_URL, 'https://pointblank.id/login/process');
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch, CURLOPT_POST, 1);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, "loginFail=0&userid=$id&password=$ps");
                                curl_setopt($ch, CURLOPT_HEADER, 1);
                                
                                $headers = array();
                                $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:76.0) Gecko/20100101 Firefox/76.0';
                                $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8';
                                $headers[] = 'Accept-Language: en-US,en;q=0.5';
                                $headers[] = 'Content-Type: application/x-www-form-urlencoded';
                                $headers[] = 'Referer: https://pointblank.id/login/form';
                                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                                $response = curl_exec($ch);
                                $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                                $header = substr($response, 0, $header_size);
                                $body = substr($response, $header_size);

                                curl_close($ch);
                                if(strpos($body, '<a href="javascript:void(0);" class="my_account_btn">')) {
                                    echo "Login : OK\n";
                                    preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $header, $outCookie);
                                    $cookies = '';
                                    foreach($outCookie[1] as $outCookies) {
                                        $cookies .= $outCookies.'; ';
                                    }

                                    $ch = curl_init();

                                    curl_setopt($ch, CURLOPT_URL, 'https://pointblank.id/event/email/process');
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

                                    $headers = array();
                                    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:76.0) Gecko/20100101 Firefox/76.0';
                                    $headers[] = 'Accept: application/json, text/javascript, */*; q=0.01';
                                    $headers[] = 'Accept-Language: en-US,en;q=0.5';
                                    $headers[] = 'Content-Type: application/x-www-form-urlencoded;charset=UTF-8';
                                    $headers[] = 'X-Requested-With: XMLHttpRequest';
                                    $headers[] = 'Referer: https://pointblank.id/event/email';
                                    $headers[] = 'Cookie: '.$cookies;
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                                    $result = curl_exec($ch);
                                    if (curl_errno($ch)) {
                                        echo 'Error:' . curl_error($ch);
                                    }
                                    curl_close($ch);
                                    $result_json = json_decode($result);
                                    if(isset($result_json->voucher)) {
                                        echo "Kode Voucher : ".$result_json->voucher."\n";
                                        file_put_contents('voucher.txt', "{$result_json->voucher}\n", FILE_APPEND | LOCK_EX);
                                    } else {
                                        echo "GAGAL MENGAMBIL !\n";
                                    }
                                } else {
                                    echo "Login : Gagal:(\n";
                                    continue;
                                }
                                if($total > 1) {
                                    echo "\n";
                                }
                            } else {
                                echo "Gagal. [ Error : ".$result." ]\n";
                                file_put_contents('failed.txt', "Email : $email\nID : $id\nPassword : $ps\n-\n", FILE_APPEND | LOCK_EX);
                            }
                        } else {
                            echo "Gagal. [ Error : ".$result." ]\n";
                        }
                    } else {
                        echo "OTP tidak ditemukan. Email : $email\n";
                    }
                } else {
                    echo "Email tidak bisa digunakan. [ Error : ".$result." ]\n";
                }
            } else {
                echo "Tidak bisa digunakan. [ Error : ".$result." ]\n";
            }
        }
    }   
}