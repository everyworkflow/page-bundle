<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\PageBundle\Controller\Admin;

use EveryWorkflow\CoreBundle\Annotation\EWFRoute;
use EveryWorkflow\PageBundle\Repository\PageRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetPageController extends AbstractController
{
    protected PageRepositoryInterface $pageRepository;

    public function __construct(PageRepositoryInterface $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    /**
     * @EWFRoute(
     *     admin_api_path="cms/page/{uuid}",
     *     defaults={"uuid"="create"},
     *     name="admin.cms.page.view",
     *     methods="GET"
     * )
     * @throws \Exception
     */
    public function __invoke(string $uuid, Request $request): JsonResponse
    {
        $data = [];

        if ($uuid !== 'create') {
            $item = $this->pageRepository->findById($uuid);
            if ($item) {
                $data['item'] = $item->toArray();
            }
        }

        $data['data_form'] = $this->pageRepository->getForm()->toArray();

        return (new JsonResponse())->setData($data);
    }
}
