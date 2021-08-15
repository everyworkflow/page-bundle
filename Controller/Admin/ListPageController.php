<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\PageBundle\Controller\Admin;

use EveryWorkflow\CoreBundle\Annotation\EWFRoute;
use EveryWorkflow\PageBundle\DataGrid\PageDataGridInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ListPageController extends AbstractController
{
    protected PageDataGridInterface $pageDataGrid;

    public function __construct(
        PageDataGridInterface $pageDataGrid
    ) {
        $this->pageDataGrid = $pageDataGrid;
    }

    /**
     * @EWFRoute(
     *     admin_api_path="cms/page",
     *     name="admin.cms.page",
     *     priority=10,
     *     methods="GET"
     * )
     */
    public function __invoke(Request $request): JsonResponse
    {
        $dataGrid = $this->pageDataGrid->setFromRequest($request);

        return (new JsonResponse())->setData($dataGrid->toArray());
    }
}
