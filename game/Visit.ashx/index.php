<?php
header("Content-Type: text/plain");
?>
local RunService = game:service("RunService") 
local Player = game.Players:createLocalPlayer(0)
function replicate(NewPlayer)  
	NewPlayer:LoadCharacter()  
	RunService:run()
	game:service("Visit")
	while wait() do  
		if NewPlayer.Character.Humanoid.Health == 0 then  
			wait(5)  
			NewPlayer:LoadCharacter() 
		elseif NewPlayer.Character.Parent  == nil then  
			wait(5)  
			NewPlayer:LoadCharacter()  
		end  
	end  
end  
replicate(Player)