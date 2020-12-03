<?php


namespace App\Service\Item;


use App\Repository\ItemRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ItemManagementHandler implements MessageSubscriberInterface
{

    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $em;

    /**
     * @var ItemRepository
     */
    protected ItemRepository $itemRepository;

    /**
     * @var ValidatorInterface
     */
    protected ValidatorInterface $validator;

    /**
     * ItemManagementHandler constructor.
     * @param EntityManagerInterface $em
     * @param ItemRepository $itemRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $em, ItemRepository $itemRepository, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->itemRepository = $itemRepository;
        $this->validator = $validator;
    }


    /**
     * @param ItemCreationList $itemCreationList
     * @return array
     * @throws Exception
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function handleItemCreationList(ItemCreationList $itemCreationList): array
    {
        $names = array();
        foreach ($itemCreationList->getItemCreations() as $itemCreation) {
            $similarItems = $this->itemRepository->findSimilar($itemCreation);
            if (count($similarItems) > 0) {
                continue;
            }
            $names[] = $itemCreation->getName();
            $this->em->persist($itemCreation->getItem());
            $this->em->flush();
            $this->em->getConnection()->prepare('CALL update_default_referent_id()')->execute();
        }
        $items = $this->itemRepository->findByNames($names);
        $itemCreationList->updateItemsId($items);
        return $itemCreationList->getResult();
    }

    /**
     * @inheritDoc
     */
    public static function getHandledMessages(): iterable
    {
        yield ItemCreationList::class => ["method" => "handleItemCreationList"];
    }

}