<?php

namespace ifteam\SignCommand;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\utils\TextFormat;
use pocketmine\block\Block;
use pocketmine\tile\Sign;

class SignCommand extends PluginBase implements Listener {
	public function onEnable() {
		$this->initMessage ();
		$this->getServer ()->getPluginManager ()->registerEvents ( $this, $this );
	}
	public function SignPlace(SignChangeEvent $event) {
		if ($event->getLine ( 0 ) != $this->get ( "sign-set-message" ) and $event->getLine ( 0 ) != $this->get ( "sign-message" ))
			return;
		$block = $event->getBlock ();
		if ($event->getLine ( 1 ) == null) {
			$event->getPlayer ()->sendMessage ( TextFormat::RED . $this->get ( "command-zero" ) );
			return;
		}
		if (isset ( explode ( "/", $event->getLine ( 1 ), 2 ) [1] )) {
			$event->setLine ( 0, $this->get ( "sign-message" ) );
		} else {
			$event->setLine ( 0, $this->get ( "sign-message" ) );
		}
	}
	public function onTouch(PlayerInteractEvent $event) {
		$block = $event->getBlock ();
		if ($event->getBlock ()->getId () == Block::SIGN_POST or $event->getBlock ()->getId () == Block::WALL_SIGN) {
			$tile = $event->getBlock ()->getLevel ()->getTile ( $event->getBlock () );
			
			if ($tile instanceof Sign) {
				$text = $tile->getText ();
				
				if ($text [0] == $this->get ( "sign-message" )) {
					$this->getServer ()->getCommandMap ()->dispatch ( $event->getPlayer (), $text [1] );
				}
			}
		}
	}
	public function initMessage() {
		$this->saveResource ( "messages.yml", false );
		$this->messages = (new Config ( $this->getDataFolder () . "messages.yml", Config::YAML ))->getAll ();
	}
	public function get($var) {
		return $this->messages [$this->messages ["default-language"] . "-" . $var];
	}
}

?>