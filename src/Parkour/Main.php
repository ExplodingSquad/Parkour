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
		$this->getServer()->getLogger()->info(TextFormat::BLUE."Parkour Has Been Enable");
		$this->getServer()->getLogger()->info(TextFormat::BLUE."By: Driesboy");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		@mkdir($this->getDataFolder());
		    $this->saveDefaultConfig();
	    $this->data = new Config($this->getDataFolder()."Data.yml", Config::YAML, array());
	    $this->config = new Config($this->getDataFolder()."Config.yml", Config::YAML, array());
	}
	
	public function onDisable(){
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
				$this->data->set($name,array($player->x,$player->y,$player->z,$player->getLevel()->getName()));
				$this->data->save();
				$player->sendMessage("Saved");
			}
		}
	}
     public function onVoidLoop(PlayerMoveEvent $event){
          if($event->getTo()->getFloorY() < 1){
             	$player = $event->getPlayer();
             	$name = $event->getPlayer()->getName();             	
             	$name = strtolower($name);
             	$pos = $this->data->get($name);
				if(is_array($pos)){
						$player->sendMessage("Teleporting to Checkpoint...");
						$level = $this->getServer()->getLevelByName($pos[3]);
						$player->teleport(new Position($pos[0],$pos[1],$pos[2],$level));
					}else $player->sendMessage("Error");
          }
     }
}
