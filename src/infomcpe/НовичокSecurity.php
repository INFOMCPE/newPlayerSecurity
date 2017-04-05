<?php

namespace infomcpe;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\utils\Utils; 
use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat as TF;
use pocketmine\scheduler\CallbackTask;

class НовичокSecurity extends PluginBase implements Listener {
    const Prefix = "§f[§aНовичокSecurity§f]§e ";

    public function onEnable(){
            $this->saveDefaultConfig();
            $this->session = $this->getServer()->getPluginManager()->getPlugin("SessionAPI");
            $this->pureperms = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
            $this->timeplayed = $this->getServer()->getPluginManager()->getPlugin("TimePlayed");
            if ($this->getServer()->getPluginManager()->getPlugin("PluginDownloader")) {
            $this->getServer()->getScheduler()->scheduleAsyncTask(new CheckVersionTask($this, 339));
            if($this->session == NULL){
               if($this->getServer()->getPluginManager()->getPlugin("PluginDownloader")->getDescription()->getVersion() >= '1.4'){
                   $this->getServer()->getPluginManager()->getPlugin("PluginDownloader")->installByID('SessionAPI');
               }
            }
        }
         $this->getServer()->getPluginManager()->registerEvents($this, $this);
   }
   public function onDamage(\pocketmine\event\entity\EntityDamageEvent $event) {
       $player = $event->getEntity();
           if($player instanceof \pocketmine\Player){
               if($event instanceof \pocketmine\event\entity\EntityDamageByEntityEvent){
                   $damager = $event->getDamager();
               }
           if($this->timeplayed->getTime($player) <= $this->getConfig()->get("timе-newcomer") ){
               if(@!is_null($damager)){
                   if($damager instanceof \pocketmine\Player){
                       $damager->sendMessage(НовичокSecurity::Prefix."Этот игрок под НовичокSecurity");
                   }
                   $player->sendMessage(НовичокSecurity::Prefix."Вас пытаеться ударить ".$damager->getName());
               }else{
                   $player->sendMessage(НовичокSecurity::Prefix."Вы новичок вас не кто не ударит) ".TF::AQUA."(Даже земля)");
               }

           }
           if(@!is_null($damager)){
           if ($this->timeplayed->getTime($damager) <= $this->getConfig()->get("timе-newcomer") ){
               $event->setCancelled();
               $damager->sendMessage(НовичокSecurity::Prefix."Вы новичок вам нельзя быться");
               
           }            
           }
           }
       
   }
   
   
}
