<?php


namespace App\Service;


use App\Entity\Client;
use App\Entity\Item;

class ItemCreation
{

    /**
     * @var Item
     */
    protected Item $item;

    /**
     * @var Client
     */
    protected Client $client;

    /**
     * ItemCreation constructor.
     * @param Client $client
     * @param array $itemContent
     */
    public function __construct(Client $client, array $itemContent)
    {
        $this->client = $client;
        $this->item = new Item();
        $this->item->setEan($itemContent["ean"]);
    }


    /**
     * @return Item
     */
    public function getItem(): Item
    {
        return $this->item;
    }

    /**
     * @param Item $item
     */
    public function setItem(Item $item): void
    {
        $this->item = $item;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

}