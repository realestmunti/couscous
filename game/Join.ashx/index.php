<?php
header("Content-Type: text/plain");
$port = (isset($_GET["port"]) ? $_GET["port"] : 53640); // sets client port
$username = (isset($_GET["username"]) ? $_GET["username"] : "Player"); // sets client player
$server = (isset($_GET["server"]) ? $_GET["server"] : "127.0.0.1"); // sets client ip
$userId = (isset($_GET["userId"]) ? $_GET["userId"] : "1"); // sets client id
$avatar = (isset($_GET["avatar"]) ? $_GET["avatar"] : "http://www.fleian.xyz/asset/CharacterFetch.ashx?userId=" . $userId); // sets client avatar
?>
local Visit = game:service("Visit")
local Players = game:service("Players")
local NetworkClient = game:service("NetworkClient")

if not NetworkClient then
	NetworkClient = Instance.new("NetworkClient")
end

local function onConnectionRejected()
	game:SetMessage("This game is not available. Please try another")
end

local function onConnectionFailed(_, id, reason)
	game:SetMessage("Failed to connect to the Game. (ID=" .. id .. ", " .. reason .. ")")
end

local function onConnectionAccepted(peer, replicator)
	local worldReceiver = replicator:SendMarker()
	local received = false
	
	local function onWorldReceived()
		received = true
	end
	
	worldReceiver.Received:connect(onWorldReceived)
	game:SetMessageBrickCount()
	
	while not received do
		workspace:ZoomToExtents()
		wait(0.5)
	end
	
	local player = Players.LocalPlayer
	game:SetMessage("Requesting character")
	
	replicator:RequestCharacter()
	game:SetMessage("Waiting for character")
	
	while not player.Character do
		player.Changed:wait()
	end
	
	game:ClearMessage()
	game:service("Visit")
end

NetworkClient.ConnectionAccepted:connect(onConnectionAccepted)
NetworkClient.ConnectionRejected:connect(onConnectionRejected)
NetworkClient.ConnectionFailed:connect(onConnectionFailed)

game:SetMessage("Connecting to Server")

local success, errorMsg = pcall(function ()
	local player = Players.LocalPlayer
	
	if not player then
		player = Players:createLocalPlayer(0)
		player.Name = "<?php echo $username ?>"
		player.userId = <?php echo $userId ?> 
		player:SetSuperSafeChat(false)
		player.CharacterAppearance = "<?php echo $avatar ?>"
		if game.GuiRoot and game.GuiRoot.MainMenu then
			game.GuiRoot.MainMenu["Tools"]:remove() game.GuiRoot.MainMenu["Insert"]:remove()
		end
	end
	
	NetworkClient:connect("<?php echo $server ?>", <?php echo $port ?>, 0, 20)
end)

if not success then
	game:SetMessage(errorMsg)
end
