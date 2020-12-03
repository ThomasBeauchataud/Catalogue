<?php


namespace App\Service\Item;


use App\Entity\Client;
use App\Entity\Item;
use Exception;

class ItemCreationList
{

    const FIELDS = ["ean", "cip", "name", "cip7"];

    /**
     * @var Client
     */
    protected Client $client;

    /**
     * @var ItemCreation[]
     */
    protected array $itemCreations;

    /**
     * ItemCreationList constructor.
     * @param Client $client
     * @param string|null $content
     * @throws Exception
     */
    public function __construct(Client $client, ?string $content)
    {
        $this->client = $client;
        $content = $this->validateAndFormatContent($content);
        $this->itemCreations = array();
        foreach ($content as $itemRequestId => $itemContent) {
            $this->itemCreations[] = new ItemCreation($itemRequestId, $itemContent);
        }
    }


    /**
     * @return array
     */
    public function getItemCreations(): array
    {
        return $this->itemCreations;
    }

    /**
     * @return array
     */
    public function getResult(): array
    {
        return array_map(function (ItemCreation $itemCreation) {
            return array("request_id" => $itemCreation->getItemRequestId(), "item_id" => $itemCreation->getId());
        }, $this->itemCreations);
    }

    /**
     * @param Item[] $items
     */
    public function updateItemsId(array $items): void
    {
        var_dump($items);
        for ($i = 0; $i < count($this->itemCreations); $i++) {
            foreach ($items as $item) {
                if ($item->getName() == $this->itemCreations[$i]->getName()) {
                    $this->itemCreations[$i]->setId($item->getId());
                    $this->itemCreations[$i]->setReferentId($item->getReferentId());
                }
            }
        }
    }

    /**
     * @param string|null $content
     * @return array
     * @throws Exception
     */
    protected function validateAndFormatContent(?string $content): array
    {
        if ($content == null || !is_array(json_decode($content, true))) {
            throw new Exception("Invalid body format");
        }
        $content = json_decode($content, true);
        foreach ($content as $itemRequestId => $itemContent) {
            foreach (self::FIELDS as $field) {
                if (!array_key_exists($field, $itemContent)) {
                    throw new Exception("Missing $field field for the request item $itemRequestId");
                }
            }
        }
        return $content;
    }

}