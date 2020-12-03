<?php


namespace App\Entity;


use App\Repository\ItemRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 */
class Item
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $referentId;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private string $ean;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private string $cip;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private string $cip7;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Unique()
     */
    private string $name;

    /**
     * @ORM\Column(type="date")
     */
    private DateTimeInterface $creationDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $privateMagento;

    /**
     * Item constructor.
     */
    public function __construct()
    {
        $this->referentId = 0;
        $this->privateMagento = false;
        $this->creationDate = new DateTime();
    }


    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getReferentId(): int
    {
        return $this->referentId;
    }

    /**
     * @param int $referentId
     */
    public function setReferentId(int $referentId): void
    {
        $this->referentId = $referentId;
    }

    /**
     * @return string
     */
    public function getEan(): string
    {
        return $this->ean;
    }

    /**
     * @param string $ean
     */
    public function setEan(string $ean): void
    {
        $this->ean = $ean;
    }

    /**
     * @return string
     */
    public function getCip(): string
    {
        return $this->cip;
    }

    /**
     * @param string $cip
     */
    public function setCip(string $cip): void
    {
        $this->cip = $cip;
    }

    /**
     * @return string
     */
    public function getCip7(): string
    {
        return $this->cip7;
    }

    /**
     * @param string $cip7
     */
    public function setCip7(string $cip7): void
    {
        $this->cip7 = $cip7;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreationDate(): DateTimeInterface
    {
        return $this->creationDate;
    }

    /**
     * @param DateTimeInterface $creationDate
     */
    public function setCreationDate(DateTimeInterface $creationDate): void
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @return bool
     */
    public function isPrivateMagento(): bool
    {
        return $this->privateMagento;
    }

    /**
     * @param bool $privateMagento
     */
    public function setPrivateMagento(bool $privateMagento): void
    {
        $this->privateMagento = $privateMagento;
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        $content = array(
            "cip" => $this->cip,
            "ean" => $this->ean,
            "creation_date" => $this->creationDate->format("Y-m-d"),
            "name" => $this->name,
            "id" => $this->id
        );
        if ($this->privateMagento) {
            $content["referentId"] = $this->referentId;
        }
        return $content;
    }

}
