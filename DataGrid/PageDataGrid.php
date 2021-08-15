<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\PageBundle\DataGrid;

use EveryWorkflow\CoreBundle\Model\DataObjectFactoryInterface;
use EveryWorkflow\CoreBundle\Model\DataObjectInterface;
use EveryWorkflow\DataFormBundle\Model\FormInterface;
use EveryWorkflow\DataGridBundle\Factory\ActionFactoryInterface;
use EveryWorkflow\DataGridBundle\Model\Action\ButtonAction;
use EveryWorkflow\DataGridBundle\Model\Collection\ArraySourceInterface;
use EveryWorkflow\DataGridBundle\Model\DataGrid;
use EveryWorkflow\DataGridBundle\Model\DataGridConfigInterface;
use EveryWorkflow\PageBundle\Repository\PageRepositoryInterface;

class PageDataGrid extends DataGrid implements PageDataGridInterface
{
    protected DataObjectFactoryInterface $dataObjectFactory;
    protected PageRepositoryInterface $pageRepository;
    protected ActionFactoryInterface $actionFactory;

    public function __construct(
        DataObjectInterface $dataObject,
        DataGridConfigInterface $dataGridConfig,
        FormInterface $form,
        ArraySourceInterface $source,
        DataObjectFactoryInterface $dataObjectFactory,
        PageRepositoryInterface $pageRepository,
        ActionFactoryInterface $actionFactory,
    ) {
        parent::__construct($dataObject, $dataGridConfig, $form, $source);
        $this->dataObjectFactory = $dataObjectFactory;
        $this->pageRepository = $pageRepository;
        $this->actionFactory = $actionFactory;
    }

    public function getConfig(): DataGridConfigInterface
    {
        $config = parent::getConfig();

        /** @var string[] $allColumns */
        $allColumns = array_map(static fn ($field) => $field->getName(), $this->getForm()->getFields());
        foreach ($allColumns as $key => $column) {
            if (str_starts_with($column, 'meta_')) {
                unset($allColumns[$key]);
            }
        }

        $config->setIsFilterEnabled(true)
            ->setIsColumnSettingEnabled(true)
            ->setActiveColumns($allColumns)
            ->setSortableColumns($allColumns)
            ->setFilterableColumns($allColumns);

        $config->setHeaderActions([
            $this->actionFactory->create(ButtonAction::class, [
                'path' => '/cms/page/create',
                'label' => 'Create new',
            ]),
        ]);

        $config->setRowActions([
            $this->actionFactory->create(ButtonAction::class, [
                'path' => '/cms/page/{_id}/edit',
                'label' => 'Edit',
            ]),
            $this->actionFactory->create(ButtonAction::class, [
                'path' => '/cms/page/{_id}/delete',
                'label' => 'Delete',
            ]),
        ]);

        $config->setBulkActions([
            $this->actionFactory->create(ButtonAction::class, [
                'path' => '/cms/page/enable/{_id}',
                'label' => 'Enable',
            ]),
            $this->actionFactory->create(ButtonAction::class, [
                'path' => '/cms/page/disable/{_id}',
                'label' => 'Disable',
            ]),
        ]);

        return $config;
    }

    public function getForm(): FormInterface
    {
        return $this->pageRepository->getForm();
    }
}
