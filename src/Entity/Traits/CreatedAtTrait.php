<?php
declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait CreatedAtTrait
 *
 * Note:
 * Entities using this must have HasLifecycleCallbacks annotation.
 */
trait CreatedAtTrait
{
    #[ORM\Column]
    protected ?\DateTime $createdAt;

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @throws \Exception
     */
    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new \DateTime('now', new \DateTimeZone('UTC'));
    }
}
