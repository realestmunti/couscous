<?php
header("Content-Type: text/plain");
$port = (isset($_GET["port"]) ? $_GET["port"] : 53640); // sets server port
$placeId = (isset($_GET["placeId"]) ? $_GET["placeId"] : "1818"); // sets server map
$egghunt = (isset($_GET["egghunt"]) ? $_GET["egghunt"] : "false"); // sets server egghunt
?>
local placeId, sleeptime, port, url, timeout = <?php echo $placeId  ?>, 0, <?php echo $port ?>, "http://www.fleian.xyz", 0

------------------- UTILITY FUNCTIONS --------------------------

function waitForChild(parent, childName)
	while true do
		local child = parent:findFirstChild(childName)
		if child then
			return child
		end
		parent.ChildAdded:wait()
	end
end

-- returns the player object that killed this humanoid
-- returns nil if the killer is no longer in the game
function getKillerOfHumanoidIfStillInGame(humanoid)

	-- check for kill tag on humanoid - may be more than one - todo: deal with this
	local tag = humanoid:findFirstChild("creator")

	-- find player with name on tag
	if tag then
		local killer = tag.Value
		if killer.Parent then -- killer still in game
			return killer
		end
	end

	return nil
end

-- This code might move to C++
function characterRessurection(player)
	if player.Character then
		local humanoid = player.Character.Humanoid
		humanoid.Died:connect(function() wait(5) 
			if player then
				player:LoadCharacter()
			end
		end)
	end
end

-- send kill and death stats when a player dies
function onDied(victim, humanoid)
	local killer = getKillerOfHumanoidIfStillInGame(humanoid)
	local victorId = 0
	if killer then
		victorId = killer.userId
		print("STAT: kill by " .. victorId .. " of " .. victim.userId)
		game:HttpGet(url .. "/game/Knockouts.ashx?UserID=" .. victorId)
	end
	print("STAT: death of " .. victim.userId .. " by " .. victorId)
	game:HttpGet(url .. "/game/Wipeouts.ashx?UserID=" .. victim.userId)
end

-----------------------------------END UTILITY FUNCTIONS -------------------------

-----------------------------------"CUSTOM" SHARED CODE----------------------------------

settings().Network.PhysicsSend = 1 -- 1==RoundRobin
pcall(function() settings().Diagnostics:LegacyScriptMode() end)

-----------------------------------START GAME SHARED SCRIPT------------------------------

local assetId = placeId -- might be able to remove this now

local scriptContext = game:GetService('ScriptContext')
scriptContext.ScriptsDisabled = true

pcall(function() game:SetPlaceID(assetId, true) end)

-- establish this peer as the Server
local ns = game:GetService("NetworkServer")

if url~=nil then
	pcall(function() game:GetService("Players"):SetAbuseReportUrl(url .. "/AbuseReport/InGameChatHandler.ashx") end)
	pcall(function() game:GetService("ScriptInformationProvider"):SetAssetUrl(url .. "/asset/") end)
	pcall(function() game:GetService("ContentProvider"):SetBaseUrl(url .. "/") end)
	pcall(function() game:GetService("Players"):SetChatFilterUrl(url .. "/game/ChatFilter.ashx") end)
end

pcall(function() game:GetService("NetworkServer"):SetIsPlayerAuthenticationRequired(false) end)

shared["__time"] = 0
game:GetService("RunService").Stepped:connect(function (time) shared["__time"] = time end)




game:GetService("Players").PlayerAdded:connect(function(player)
	print("Player " .. player.userId .. " added")
	characterRessurection(player)
	player.Changed:connect(function(name)
		if name=="Character" then
			characterRessurection(player)
		end
	end)
end)


game:GetService("Players").PlayerRemoving:connect(function(player)
	print("Player " .. player.userId .. " leaving")
end)
	
-- load the game
game:Load(url .. "/asset/?id=" .. placeId)

if Egghunt == true then
	workspace:InsertContent(url .. "/asset/?id=539990")  
else
	print("Egghunt is false! Not running...")
end 

-- Now start the connection
ns:Start(port, sleeptime) 

if timeout then
	scriptContext:SetTimeout(timeout)
end
scriptContext.ScriptsDisabled = false

------------------------------END START GAME SHARED SCRIPT--------------------------

-- StartGame -- 
game:GetService("RunService"):Run()