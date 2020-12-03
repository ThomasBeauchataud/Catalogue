<?php


namespace App\Service\Item;


use App\Entity\Item;

class ItemCreation extends Item
{

    /**
     * @var int
     */
    protected int $itemRequestId;

    /**
     * @var Item
     */
    protected Item $item;

    /**
     * ItemCreation constructor.
     * @param int $itemRequestId
     * @param array $itemContent
     */
    public function __construct(int $itemRequestId, array $itemContent)
    {
        parent::__construct();
        $this->itemRequestId = $itemRequestId;
        $this->setEan($itemContent["ean"]);
        $this->setCip($itemContent["cip"]);
        $this->setName($itemContent["name"]);
        $this->setCip7($itemContent["cip7"]);
    }


    /**
     * @return int
     */
    public function getItemRequestId(): int
    {
        return $this->itemRequestId;
    }

    /**
     * @return Item
     */
    public function getItem(): Item
    {
        $item = new Item();
        $item->setEan($this->getEan());
        $item->setCip($this->getCip());
        $item->setName($this->getName());
        $item->setCip7($this->getCip7());
        return $item;
    }

}