<?php
namespace Parkour;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\utils\TextFormat;
use pocketmine\Player;
use pocketmine\tile\Sign;
use pocketmine\level\Position;
use pocketmine\level\Level;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\event\player\PlayerMoveEvent;

class Main extends PluginBase implements Listener{
	
	private $config;
	private $pos;

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		@mkdir($this->getDataFolder());
		    $this->saveDefaultConfig();
                    $this->getResource("config.yml");
	    $this->config = new Config($this->getDataFolder()."Data.yml", Config::YAML, array());
	}
	
	public function onPlayerTouch(PlayerInteractEvent $event){
		$player = $event->getPlayer();
		$b = $event->getBlock();
		$name = $event->getPlayer()->getName();
		$name = strtolower($name);
		if($b->getID() == 63 || $b->getID() == 68){ 
			$sign = $player->getLevel()->getTile($b);
			if(!($sign instanceof Sign)){
				return;
			}
			$sign = $sign->getText();
			if(TextFormat::clean($sign[0]) === '[Checkpoint]'){
				$this->config->set($name,array($player->x,$player->y,$player->z,$player->getLevel()->getName()));
				$this->config->save();
				$player->sendMessage("Saved");
			}elseif(TextFormat::clean($sign[0]) === '[To Checkpoint]'){
				$pos = $this->config->get($name);
				if(is_array($pos)){
					if(count($pos) === 4){
						$player->sendMessage("Teleporting to Checkpoint...");
						$level = $this->getServer()->getLevelByName($pos[3]);
						if($level) $player->teleport(new Position($pos[0],$pos[1],$pos[2],$level));
						else{
							$player->sendMessage("Level is not loaded");
						}
					}else $player->sendMessage("Save Corrupted");
				}else $player->sendMessage("No Save Found");
			}
		}
	}
     public function onVoidLoop(PlayerMoveEvent $event){
          if($event->getTo()->getFloorY() < 0){
                $enableConf = $this->getConfig()->get("enableConf");
                $X = $this->getConfig()->get("X");
                $Y = $this->getConfig()->get("Y");
                $Z = $this->getConfig()->get("Z");
                $Level = $this->getConfig()->get("Level");
                $player = $event->getPlayer();
	        $name = $event->getPlayer()->getName();
	        $b = $event->getBlock();
             if($enableConf === false){
             	$b = $event->getBlock();
             	$name = $event->getPlayer()->getName();
				$pos = $this->config->get($name);
				if(is_array($pos)){
					if(count($pos) === 4){
						$player->sendMessage("Teleporting to Checkpoint...");
						$level = $this->getServer()->getLevelByName($pos[3]);
						if($level) $player->teleport(new Position($pos[0],$pos[1],$pos[2],$level));
						else{
							$player->sendMessage("Level is not loaded");
						}
					}else $player->sendMessage("Save Corrupted");
				}else $player->sendMessage("No Save Found");
			}
             }else{
             	$name = $event->getPlayer()->getName();
				$pos = $this->config->get($name);
				if(is_array($pos)){
					if(count($pos) === 4){
						$player->sendMessage("Teleporting to Checkpoint...");
						$level = $this->getServer()->getLevelByName($pos[3]);
						if($level) $player->teleport(new Position($pos[0],$pos[1],$pos[2],$level));
						else{
							$player->sendMessage("Level is not loaded");
						}
					}else $player->sendMessage("Save Corrupted");
				}else $player->sendMessage("No Save Found");
             }
     }
}
