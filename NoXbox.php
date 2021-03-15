<?php

/**
 * @name NoXbox
 * @main JUNKR\NoXbox
 * @author JUN-KR
 * @version 1.0.0
 * @api 3.14.0
 * @description -
 */

namespace JUNKR;

use pocketmine\entity\Skin;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\network\mcpe\protocol\PlayerListPacket;
use pocketmine\network\mcpe\protocol\types\PlayerListEntry;
use pocketmine\network\mcpe\protocol\types\SkinAdapterSingleton;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class NoXbox extends PluginBase implements Listener{

    public function onEnable(){
        $this->getserver()->getPluginManager()->registerEvents($this, $this);
    }

    public function onJoin(PlayerJoinEvent $ev){
        $pk = [];

        foreach(Server::getInstance()->getOnlinePlayers() as $player){
            $add = new PlayerListPacket();
            $add->type = PlayerListPacket::TYPE_REMOVE;
            $add->entries = [PlayerListEntry::createRemovalEntry($player->getUniqueId())];
            $pk[] = $add;

            $add->type = PlayerListPacket::TYPE_ADD;
            $add->entries = [PlayerListEntry::createAdditionEntry($player->getUniqueId(), $player->getId(), $player->getName(), SkinAdapterSingleton::get()->toSkinData($player->getSkin()))];
            $pk[] = $add;
        }

        foreach($pk as $pks){
            Server::getInstance()->broadcastPacket(Server::getInstance()->getOnlinePlayers(), $pks);
        }
    }

}