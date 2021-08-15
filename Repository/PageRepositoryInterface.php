<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\PageBundle\Repository;

use EveryWorkflow\EavBundle\Repository\BaseEntityRepositoryInterface;
use EveryWorkflow\PageBundle\Entity\PageEntityInterface;
use MongoDB\UpdateResult;

interface PageRepositoryInterface extends BaseEntityRepositoryInterface
{
    /**
     * @param PageEntityInterface $entity
     * @param array $otherFilter
     * @param array $otherOptions
     * @return UpdateResult
     */
    public function savePageEntity(
        PageEntityInterface $entity,
        array $otherFilter = [],
        array $otherOptions = []
    ): UpdateResult;
}
