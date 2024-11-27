<?php
$structure = $_GET["userId"];
if ($structure == "")
	die(json_encode(["message" => "Structure is empty"]));

$structure = explode("_", $structure);
if (count($structure) > 1)
    for ($i = 1; $i < count($structure); $i++) {
        if (!stripos($structure[$i], "-"))
            $structure[$i] = "http://www.fleian.xyz/asset/?id=".$structure[$i];
    }
header("Content-Type: text/plain; charset=utf-8");
die("http://www.fleian.xyz/asset/BodyColors.ashx?userId=".implode(";", $structure));