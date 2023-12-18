<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use App\Utills\ProductGroups;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity('Sku',message: 'Sku field value should be unique')]
class Product
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['group:get'])]
    private ?int $id = null;

    #[ORM\Column(length: 50,unique: true)]
    #[Assert\NotBlank(message: 'Sku value should not be blank')]
    private ?string $Sku = null;

    #[ORM\Column(length: 250)]
    #[Assert\NotBlank(message:'Product name value should not be blank')]
    #[Assert\Length(max: 249,maxMessage: 'Product value is too long. It should have 249 characters or less')]
    private ?string $Product_name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(message: 'Description name value should not be blank')]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups([ProductGroups::GET_PRODUCTS])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    #[Groups([ProductGroups::GET_PRODUCTS])]
    private ?\DateTimeImmutable $updated_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSku(): ?string
    {
        return $this->Sku;
    }

    public function setSku(string $Sku): static
    {
        $this->Sku = $Sku;

        return $this;
    }

    public function getProductName(): ?string
    {
        return $this->Product_name;
    }

    public function setProductName(string $Product_name): static
    {
        $this->Product_name = $Product_name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }


    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }
    #[ORM\PrePersist]
    public function setCreatedAt(): static
    {
        $this->created_at =  new \DateTimeImmutable();

        return $this;
    }


    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): static
    {
        $this->updated_at = new \DateTimeImmutable();

        return $this;
    }
}
