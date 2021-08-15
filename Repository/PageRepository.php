<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\PageBundle\Repository;

use EveryWorkflow\PageBundle\Entity\PageEntity;
use EveryWorkflow\PageBundle\Entity\PageEntityInterface;
use EveryWorkflow\CoreBundle\Annotation\RepoDocument;
use EveryWorkflow\CoreBundle\Helper\CoreHelperInterface;
use EveryWorkflow\EavBundle\Factory\EntityFactoryInterface;
use EveryWorkflow\EavBundle\Form\EntityAttributeFormInterface;
use EveryWorkflow\EavBundle\Repository\AttributeRepositoryInterface;
use EveryWorkflow\EavBundle\Repository\BaseEntityRepository;
use EveryWorkflow\MongoBundle\Document\HelperTrait\StatusHelperTraitInterface;
use EveryWorkflow\MongoBundle\Factory\DocumentFactoryInterface;
use EveryWorkflow\MongoBundle\Model\MongoConnectionInterface;
use EveryWorkflow\UrlRewriteBundle\Document\UrlRewriteDocumentInterface;
use EveryWorkflow\UrlRewriteBundle\Repository\UrlRewriteRepositoryInterface;
use MongoDB\UpdateResult;

/**
 * @RepoDocument(doc_name=PageEntity::class)
 */
class PageRepository extends BaseEntityRepository implements PageRepositoryInterface
{
    protected string $collectionName = 'page_entity_collection';
    protected array $indexNames = ['url_path'];
    protected string $entityCode = 'page';

    protected UrlRewriteRepositoryInterface $urlRewriteRepository;

    public function __construct(
        UrlRewriteRepositoryInterface $urlRewriteRepository,
        MongoConnectionInterface $mongoConnection,
        DocumentFactoryInterface $documentFactory,
        CoreHelperInterface $coreHelper,
        EntityFactoryInterface $entityFactory,
        AttributeRepositoryInterface $attributeRepository,
        EntityAttributeFormInterface $entityAttributeForm,
        $entityAttributes = []
    ) {
        parent::__construct(
            $mongoConnection,
            $documentFactory,
            $coreHelper,
            $entityFactory,
            $attributeRepository,
            $entityAttributeForm,
            $entityAttributes
        );
        $this->urlRewriteRepository = $urlRewriteRepository;
    }

    /**
     * @throws \Exception
     */
    public function savePageEntity(
        PageEntityInterface $entity,
        array $otherFilter = [],
        array $otherOptions = []
    ): UpdateResult {
        $urlPath = $entity->getData('url_path');
        if ($urlPath) {
            $urlRewrite = $this->urlRewriteRepository->getNewDocument([
                UrlRewriteDocumentInterface::KEY_URL => $entity->getData('url_path'),
                UrlRewriteDocumentInterface::KEY_TYPE => $this->entityCode,
                UrlRewriteDocumentInterface::KEY_TYPE_KEY => $entity->getData('url_path'),
                StatusHelperTraitInterface::KEY_STATUS => StatusHelperTraitInterface::STATUS_ENABLE,
            ]);
            if ($entity->getData('meta_title')) {
                $urlRewrite->setData('meta_title', $entity->getData('meta_title'));
            }
            if ($entity->getData('meta_description')) {
                $urlRewrite->setData('meta_description', $entity->getData('meta_description'));
            }
            if ($entity->getData('meta_keyword')) {
                $urlRewrite->setData('meta_keyword', $entity->getData('meta_keyword'));
            }
            $this->urlRewriteRepository->save($urlRewrite);
        }
        return $this->saveEntity($entity, $otherFilter, $otherOptions);
    }
}
