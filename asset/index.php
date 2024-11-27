<?php
header("Content-Type: application/json");
$id = $_GET['ID'] ?? $_GET['id'] ?? die(json_encode(["message" => "Unable to process request."])); // get asset id
if (is_numeric($id)) {
    $id = $id*1; // we do this instead of (int)$id because it can exceed (2^31)-1
    header("Pragma: cache");
    header("Cache-Control: max-age=120");
	
	if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/asset/uploaded/" . $id)) {
		header("Content-Type: application/octet-stream");
		die(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/asset/uploaded/" . $id));
	}
    $curl = curl_init("https://assetdelivery.roblox.com/v1/asset/?" . $_SERVER["QUERY_STRING"]);
    curl_setopt_array($curl, [
        CURLOPT_ENCODING => "",
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => "Roblox/WinInet"
    ]);
    
    $data = curl_exec($curl);
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    if ($code != 200) {
        http_response_code($code);
        header("Content-Type: application/json");
        die($data);
    }
    
    header("Content-Type: application/octet-stream");
    die($data);
}

?>