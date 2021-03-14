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

    private $pk = [];

    public function onEnable(){
        $this->getserver()->getPluginManager()->registerEvents($this, $this);
    }

    public function onJoin(PlayerJoinEvent $ev){
        $player = $ev->getPlayer();

        $add = new PlayerListPacket();
        $add->type = PlayerListPacket::TYPE_REMOVE;
        $add->entries = [PlayerListEntry::createRemovalEntry($player->getUniqueId())];
        $this->pk[] = $add;

        $add->type = PlayerListPacket::TYPE_ADD;
        $add->entries = [PlayerListEntry::createAdditionEntry($player->getUniqueId(), $player->getId(), $player->getDisplayName(), SkinAdapterSingleton::get()->toSkinData($player->getSkin()))];
        $this->pk[] = $add;

        foreach($this->pk as $pk){
            Server::getInstance()->broadcastPacket(Server::getInstance()->getOnlinePlayers(), $pk);
        }
    }

}