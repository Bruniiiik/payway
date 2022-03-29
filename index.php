<?php

$url = "http://payway.bubileg.cz/api/echo";

$signature = "";
$date = date("YmdHis");
$bpb = "-----BEGIN PUBLIC KEY-----
MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBAK9i4eHStEr9M/Iix2WbQvB+i71H/eb6
da9M+/HvIBXywE+Q+bpTq2IGNK+EMWvVsQ0wNfLiBVez+vzA4r6JdC8CAwEAAQ==
-----END PUBLIC KEY-----";
$pk = "-----BEGIN PRIVATE KEY-----
MIIBVQIBADANBgkqhkiG9w0BAQEFAASCAT8wggE7AgEAAkEAobBrXQkEZR5rnfTP
VNohF+OA3TcjIIQGz5/oJQ01kO3GwT3IJy7//B5Kh5CG/Q+r1YDbhIoiQKmdlmA3
AkmbRQIDAQABAkAy6rmEnLhTl5bQMS2xTNerDCuifiCDts/mRjb2pAhAUQBol0bi
I9nMLDV3b1CLkyEg8/TiT3DooKfdJNqoiQ4BAiEAzAQUhT7rhXr8iOnwHTfY+LgS
+rl8Gfg+pMZwrdfxYAUCIQDK417OCoo7w17p+9cKvgxLBFvrL0cNXifVcAqhFqZy
QQIhAJ3KihSElaSneqpqRUxT5Xx44jUJQPLVDZ5j3MKYQhgBAiEAsu1EXcdz03Lg
UNAV/NZQNxRYEBh4u/ROgvA1n40/K0ECIDYcM7TtV5JaUWm7EyxxTc7OvuRhpTM6
lm9/v2mMeceS
-----END PRIVATE KEY-----";
$data = array(
    'merchantId' => "64dd71b9",
    'dttm' => $date
);
openssl_sign($data["merchantId"] . "|" . $data["dttm"], $signature, $pk);
$data["signature"] = base64_encode($signature);

$content = curl_init($url);
curl_setopt($content, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($content, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
curl_setopt($content, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($content);
curl_close($content);

echo $result;

$result = json_decode($result, true);
$pb = openssl_get_publickey($bpb);
$ok = openssl_verify($result["resultCode"] . "|" . $result["resultMessage"] . "|" . $result["dttm"], base64_decode($result["signature"]), $pb);

echo $ok;
var_dump($ok);