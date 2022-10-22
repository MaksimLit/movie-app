<?php
declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait TimestampTrait
 *
 * Note:
 * Entities using this must have HasLifecycleCallbacks annotation.
 */
trait TimestampTrait
{
    use CreatedAtTrait;
    use UpdatedAtTrait;
}
