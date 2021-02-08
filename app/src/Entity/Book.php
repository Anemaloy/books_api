<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book implements \JsonSerializable, TranslatableInterface
{
    use TranslatableTrait;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Author::class, inversedBy="books")
     */
    private $author;

    public function __construct()
    {
        $this->author = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Author[]
     */
    public function getAuthor(): Collection
    {
        return $this->author;
    }

    public function addAuthors(Author $author): self
    {
        if (!$this->author->contains($author)) {
            $this->author[] = $author;
        }

        return $this;
    }

    public function removeAuthors(Author $author): self
    {
        $this->author->removeElement($author);

        return $this;
    }

    public function __call($method, $arguments)
    {
        return $this->proxyCurrentLocaleTranslation($method, $arguments);
    }

    public function __get($name)
    {
        switch ($name) {
            case 'name':
                return $this->getName();
        }
    }

    public function __set($name, $value)
    {
        switch ($name) {
            case 'name':
                return $this->setName($value);
        }
    }

    public function jsonSerialize()
    {
        return [
            "id" => $this->getId(),
            "name" => $this->translate()->getName(),
            'author' => $this->getAuthor(),
        ];
    }
}
