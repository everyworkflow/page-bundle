<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\PageBundle\Controller\Admin;

use EveryWorkflow\CoreBundle\Annotation\EWFRoute;
use EveryWorkflow\PageBundle\Entity\PageEntityInterface;
use EveryWorkflow\PageBundle\Repository\PageRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SavePageController extends AbstractController
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
     *     name="admin.cms.page.save",
     *     methods="POST"
     * )
     * @throws \JsonException
     */
    public function __invoke(string $uuid, Request $request): JsonResponse
    {
        $submitData = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        if ($uuid === 'create') {
            /** @var PageEntityInterface $item */
            $item = $this->pageRepository->getNewEntity($submitData);
        } else {
            $item = $this->pageRepository->findById($uuid);
            foreach ($submitData as $key => $val) {
                $item->setData($key, $val);
            }
        }
        $result = $this->pageRepository->savePageEntity($item);

        if ($result->getUpsertedId()) {
            $item->setData('_id', $result->getUpsertedId());
        }

        return (new JsonResponse())->setData([
            'message' => 'Successfully saved changes.',
            'item' => $item->toArray(),
        ]);
    }
}
