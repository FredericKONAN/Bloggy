<?php

namespace App\Entity\Traits;
use Doctrine\ORM\Mapping as ORM;

Trait Timestampable
{

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PrePersist]
    public function OnPrePersist():void
    {
        $this->setCreatedAt(new \DateTimeImmutable());
        $this->setUpdatedAt( new \DateTimeImmutable());
    }

    #[ORM\PreUpdate]
    public function OnPreUpdate():void
    {
        $this->setUpdatedAt( new \DateTimeImmutable());
    }


}