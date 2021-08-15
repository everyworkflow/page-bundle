<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\PageBundle;

use EveryWorkflow\PageBundle\DependencyInjection\PageExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EveryWorkflowPageBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new PageExtension();
    }
}
