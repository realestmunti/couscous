<?php
$bodycolors = explode("-", $_GET["userId"], 6);

if (count($bodycolors) != 6)
	die(json_encode(["message" => "Not enough or invalid"]));

foreach($bodycolors as $value) {
    if (!is_numeric($value))
		die(json_encode(["message" => "Not numeric"]));
}
header("Content-Type: application/xml; charset=utf-8");
?><?xml version="1.0" encoding="UTF-8" ?>
<roblox xmlns:xmime="http://www.w3.org/2005/05/xmlmime" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://www.fleian.xyz/roblox.xsd" version="4">
	<External>null</External>
	<External>nil</External>
	<Item class="BodyColors">
		<Properties>
			<int name="HeadColor"><?=$bodycolors[0]?></int>
			<int name="LeftArmColor"><?=$bodycolors[1]?></int>
			<int name="LeftLegColor"><?=$bodycolors[2]?></int>
			<string name="Name">Body Colors</string>
			<int name="RightArmColor"><?=$bodycolors[3]?></int>
			<int name="RightLegColor"><?=$bodycolors[4]?></int>
			<int name="TorsoColor"><?=$bodycolors[5]?></int>
			<bool name="archivable">true</bool>
		</Properties>
	</Item>
</roblox>