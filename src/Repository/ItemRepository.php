<?php


namespace App\Repository;


use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{

    /**
     * ItemRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    /**
     * @return Item[]
     */
    public function findPrivate(): array
    {
        return $this->findAll();
    }

    /**
     * @return Item[]
     */
    public function findPublic(): array
    {
        return $this->findBy(["privateMagento" => false]);
    }

    /**
     * @param Item $item
     * @return Item[]
     */
    public function findSimilar(Item $item): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.ean = :ean')
            ->orWhere('i.cip = :cip')
            ->orWhere('i.cip7 = :cip7')
            ->orWhere('i.name = :name')
            ->setParameter(':ean', $item->getEan())
            ->setParameter(':cip', $item->getCip())
            ->setParameter(':name', $item->getName())
            ->setParameter(':cip7', $item->getCip7())
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array $names
     * @return array
     */
    public function findByNames(array $names): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.name IN (:names)')
            ->setParameter(':names', $names)
            ->getQuery()
            ->getResult();
    }

}
