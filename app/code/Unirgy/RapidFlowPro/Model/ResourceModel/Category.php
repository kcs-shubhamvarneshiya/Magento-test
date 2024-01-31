<?php
namespace Unirgy\RapidFlowPro\Model\ResourceModel;

use Magento\Backend\Model\UrlInterface;
use Magento\Framework\Db\Select;
use Magento\Framework\Exception\LocalizedException;
use Unirgy\RapidFlowPro\Model\ResourceModel\Category\AbstractCategory;
use Unirgy\RapidFlow\Exception;
use Unirgy\RapidFlow\Helper\Data as HelperData;

class Category
    extends AbstractCategory
{
    protected $_csvRows;

    protected function _construct()
    {
        parent::_construct();

        $this->_logger = $this->_context->logger;
        $this->_scopeConfig = $this->_context->scopeConfig;
        $this->_modelProductImage = $this->_context->modelProductImage;
    }

    public function export()
    {
        $tune = $this->_scopeConfig->getValue('urapidflow/finetune');
        if (!empty($tune['export_page_size']) && $tune['export_page_size'] > 0) {
            $this->_pageRowCount = (int)$tune['export_page_size'];
        }
        if (!empty($tune['page_sleep_delay'])) {
            $this->_pageSleepDelay = (int)$tune['page_sleep_delay'];
        }

        $profile = $this->_profile;
        $logger = $profile->getLogger();

        $this->_entityTypeId = $this->_getEntityType($this->_entityType, 'entity_type_id');
        $this->_prepareEntityIdField();
        $entityId = $this->_entityIdField;

        $this->_profile->activity('Preparing data');

        $this->_prepareAttributes($profile->getAttributeCodes());
        $this->_prepareSystemAttributes();
        $this->_prepareCategories();

        $storeId = $profile->getStoreId();
        $this->_storeId = $storeId;

        $baseUrl = $this->_storeManager->getStore($storeId)->getBaseUrl(UrlInterface::URL_TYPE_WEB);
        $mediaUrl = $this->_storeManager->getStore($storeId)->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        $mediaDir = $this->_filesystem->getDirectoryRead('media')->getAbsolutePath();
        $imgModel = $this->_modelProductImage;

        $this->_upPrependRoot = $profile->getData('options/export/urlpath_prepend_root');

        // main product table
        $table = $this->_t(self::TABLE_CATALOG_CATEGORY_ENTITY);

        $rootCatId = $this->_getRootCatId();
        $rootPath = $rootCatId ? '1/' . $rootCatId : '1';

        if ($this->_upPrependRoot) {
            $nameAttrId = $this->_attr('name', 'attribute_id');
            $rootCatPathsSel = $this->_read->select()
                ->from(['w' => $this->_t(self::TABLE_STORE_WEBSITE)], [])
                ->join(['g' => $this->_t(self::TABLE_STORE_GROUP)], 'g.group_id=w.default_group_id', [])
                ->join(['e' => $table], "e.{$entityId}=g.root_category_id", ["concat('1/',e.{$entityId})"])
                ->join(['name' => $table . '_varchar'],
                       "name.{$entityId}=e.{$entityId} and name.attribute_id={$nameAttrId} and name.value<>'' and name.value is not null and name.store_id=0",
                       ['value'])
                ->group("e.{$entityId}");
            if ($storeId) {
                $rootCatPathsSel->where("e.{$entityId}=?", $rootCatId);
            }
            $this->_rootCatPaths = $this->_read->fetchPairs($rootCatPathsSel);
        }

        // start select
        $upAttrId = $this->_attr('url_path', 'attribute_id');

        $select = $this->_read->select()->from(['e' => $table])->order('path');

        $select->joinLeft(['up' => $table . '_varchar'],
                          "up.{$entityId}=e.{$entityId} and up.attribute_id={$upAttrId} and up.value<>'' and up.value is not null and up.store_id=0",
                          []);

        if ($this->_upPrependRoot && !empty($this->_rootCatPaths)) {
            $_rcPaths = [];
            foreach ($this->_rootCatPaths as $_rcPath => $_rcName) {
                $_rcPaths[] = $this->_read->quoteInto('path=?', $_rcPath);
                $_rcPaths[] = $this->_read->quoteInto('path like ?', $_rcPath . '/%');
            }
            $select->where(implode(' OR ', $_rcPaths));
        } else {
            $select->where(
                $this->_read->quoteInto('path=?', $rootPath)
                . $this->_read->quoteInto(' OR path like ?', $rootPath . '/%')
            );
        }

        if ($storeId != 0) {
            $select->joinLeft(['ups' => $table . '_varchar'],
                              "ups.{$entityId}=e.{$entityId} AND ups.attribute_id={$upAttrId}
                              AND ups.value<>'' AND up.value IS NOT NULL AND ups.store_id='{$storeId}'",
                              []);
            $select->columns(['url_path' => 'IFNULL(ups.value, up.value)']);
        } else {
            $select->columns(['url_path' => 'up.value']);
        }

        $this->_attrJoined = [$upAttrId];

        $columns = $profile->getColumns();

        $defaultSeparator = $profile->getData('options/csv/multivalue_separator');
        if (!$defaultSeparator) {
            $defaultSeparator = '; ';
        }

        $this->_fields = [];
        $this->_fieldsCodes = [];
        if ($columns) {
            foreach ($columns as $i => &$f) {
                if (empty($f['alias'])) {
                    $f['alias'] = $f['field'];
                }
                if (!empty($f['default']) && is_array($f['default'])) {
                    $f['default'] = implode(!empty($f['separator']) ? $f['separator'] : $defaultSeparator, $f['default']);
                }
                $this->_fields[$f['alias']] = $f;
                $this->_fieldsCodes[$f['field']] = true;
            }
            unset($f);
        } else {
            foreach ($this->_attributesByCode as $k => $a) {
                $this->_fields[$k] = ['field' => $k, 'title' => $k, 'alias' => $k];
                $this->_fieldsCodes[$k] = true;
            }
        }

        $condProdIds = $profile->getConditionsProductIds();
        if (is_array($condProdIds)) {
            $select->where("{$entityId} in (?)", $condProdIds);
        }

        $countSelect = clone $select;
        $countSelect->reset(Select::FROM)->reset(Select::COLUMNS)->from(['e' => $table], ['count(*)']);
        $count = $this->_read->fetchOne($countSelect);
        unset($countSelect);
        $profile->setRowsFound($count)->setStartedAt(\Unirgy\RapidFlow\Helper\Data::now())
            ->sync(true, ['rows_found', 'started_at'], false);
        $profile->activity('Exporting');
#memory_get_usage();
        // open export file
        $profile->ioOpenWrite();

        // write headers to the file
        $headers = [];
        foreach ($this->_fields as $k => $f) {
            $headers[] = !empty($f['alias']) ? $f['alias'] : $k;
        }
        $profile->ioWriteHeader($headers);

        // batch size
        // repeat until data available
        // data will loaded page by page to conserve memory
        for ($page = 0; ; $page++) {
            // set limit for current page
            $select->limitPage($page + 1, $this->_pageRowCount);
            // retrieve product entity data and attributes in filters
            $rows = $this->_read->fetchAll($select);
            if (!$rows) {
                break;
            }
            // fill $this->_entities associated by product id
            $this->_entities = [];
            foreach ($rows as $p) {
                $this->_entities[$p[$entityId]][0] = $p;
            }
            unset($rows);

            $this->_entityIds = array_keys($this->_entities);
            $this->_attrValueIds = [];
            $this->_attrValuesFetched = [];
            $this->_fetchAttributeValues($storeId, true);
            $this->_csvRows = [];
//            memory_get_usage(true);

            $this->_eventManager->dispatch('urapidflow_catalog_category_export_before_format', [
                'vars' => [
                    'profile' => $this->_profile,
                    'products' => &$this->_entities,
                    'fields' => &$this->_fields,
                ]
            ]);

            // format product data as needed
            foreach ($this->_entities as $id => $p) {
                if ($p[0]['entity_id']==$rootCatId) continue;
                $csvRow = [];
                $value = null;
                foreach ($this->_fields as $k => $f) {
                    $attr = $f['field'];
                    $inputType = $this->_attr($attr, 'frontend_input');

                    // retrieve correct value for current row and field
                    if ($v = $this->_attr($attr, 'force_value')) {
                        $value = $v;
                    } elseif (!empty($this->_fieldAttributes[$attr])) {
                        $a = $this->_fieldAttributes[$attr];
                        $value = isset($p[$storeId][$a]) ? $p[$storeId][$a] : (isset($p[0][$a]) ? $p[0][$a] : null);
                    } else {
                        $value = isset($p[$storeId][$attr]) ? $p[$storeId][$attr] : (isset($p[0][$attr]) ? $p[0][$attr] : null);
                    }

                    // replace raw numeric values with source option labels
                    if (($inputType === 'select' || $inputType === 'multiselect') && ($options = $this->_attr($attr,
                                                                                                            'options'))
                    ) {

                        if (!is_array($value)) {
                            $value = @explode(',', $value);
                        }
                        foreach ($value as &$v) {
                            if ($v === '') {
                                continue;
                            }
                            if (!isset($options[$v])) {
                                $profile->addValue('num_warnings');
                                $logger->warning(__("Unknown option '%1' for category '%2' attribute '%3'", $v,
                                                    $p[0]['url_path'], $attr));
                                continue;
                            }
                            $v = $options[$v];
                        }
                        unset($v);
                    }

                    // combine multiselect values
                    if (is_array($value)) {
                        $value = implode(!empty($f['separator']) ? $f['separator'] : $defaultSeparator, $value);
                    }

                    // process special cases of loaded attributes
                    switch ($attr) {
                        // product url
                        case 'url_path':
                            if (empty($value)) {
                                $value = isset($this->_categories[$id]['url_path']) ? $this->_categories[$id]['url_path'] : $this->catBuildPath($p[0]);
                                //$value = $this->catBuildPath($p[0], $this->_categories);
                            }
                            if (!empty($f['format']) && $f['format'] === 'url') {
                                $value = $baseUrl . $value;
                            } else {
                                $value = $this->_upPrependRoot($p[0], $value);
                            }
                            break;

                        case 'const.value':
                            $value = isset($f['default']) ? $f['default'] : '';
                            break;
                    }

                    switch ($this->_attr($attr, 'backend_type')) {
                        case 'decimal':
                            if (null !== $value && !empty($f['format'])) {
                                $value = sprintf($f['format'], $value);
                            }
                            break;

                        case 'datetime':
                            if (!\Unirgy\RapidFlow\Helper\Data::is_empty_date($value)) {
                                $value = date(!empty($f['format']) ? $f['format'] : 'Y-m-d H:i:s', strtotime($value??''));
                            }
                            break;
                    }

                    switch ($this->_attr($attr, 'frontend_input')) {
                        case 'media_image':
                            if ($value === 'no_selection') {
                                $value = '';
                            }
                            if (!empty($value) && !empty($f['format']) && $f['format'] === 'url') {
                                try {
                                    $path = $imgModel->setBaseFile($value)->getBaseFile();
                                    $path = str_replace($mediaDir . DIRECTORY_SEPARATOR, '', $path);
                                    $value = $mediaUrl . str_replace(DIRECTORY_SEPARATOR, '/', $path);
                                } catch (\Exception $e) {
                                    $value = '';
                                }
                            }
                            break;
                    }

                    if ((null === $value || $value === '') && !empty($f['default'])) {
                        $value = $f['default'];
                    }

                    $csvRow[] = $value;
                }

                $this->_csvRows[] = $this->_convertEncoding($csvRow);
//                $profile->ioWrite($csvRow);
                $profile->addValue('rows_processed');//->addValue('rows_success');
            } // foreach ($this->_entities as $id=>&$p)

            $this->_eventManager->dispatch('urapidflow_catalog_category_export_before_output', [
                'vars' => [
                    'profile' => $this->_profile,
                    'products' => &$this->_entities,
                    'fields' => &$this->_fields,
                    'rows' => &$this->_csvRows,
                ]
            ]);

            foreach ($this->_csvRows as $row) {
                $profile->ioWrite($row);
                $profile->addValue('rows_success');
            }

            $profile->setMemoryUsage(memory_get_usage(true))->setMemoryPeakUsage(memory_get_peak_usage(true))
                ->setSnapshotAt(\Unirgy\RapidFlow\Helper\Data::now())->sync();

            $this->_checkLock();

            // stop repeating if this is the last page
            if (count($this->_entities) < $this->_pageRowCount) {
                break;
            }
            if ($this->_pageSleepDelay) {
                sleep($this->_pageSleepDelay);
            }
        } // while (true)
        $profile->ioClose();
    }

    public function import()
    {
        $tune = $this->_scopeConfig->getValue('urapidflow/finetune');
        if (!empty($tune['import_page_size']) && $tune['import_page_size'] > 0) {
            $this->_pageRowCount = (int)$tune['import_page_size'];
        }
        if (!empty($tune['page_sleep_delay'])) {
            $this->_pageSleepDelay = (int)$tune['page_sleep_delay'];
        }

        $profile = $this->_profile;
        $this->_prepareEntityIdField();

        $dryRun = $profile->getData('options/import/dryrun');
        $this->_allowImportById = $profile->getData('options/import/allow_entity_id');

        if ($this->_storeManager->isSingleStoreMode()) {
            $storeId = 0;
        } else {
            $storeId = $profile->getStoreId();
        }
        $this->_storeId = $storeId;
        $this->_entityTypeId = $this->_getEntityType($this->_entityType, 'entity_type_id');
        $this->_attributeSetId = $this->_getEntityType($this->_entityType, 'default_attribute_set_id');

        $useTransactions = $profile->getUseTransactions();

        $this->_profile->activity(__('Fetching number of rows'));

        $profile->ioOpenRead();
        $count = -1;
        while ($profile->ioRead()) {
            $count++;
        }
        $profile->setRowsFound($count)->setStartedAt(\Unirgy\RapidFlow\Helper\Data::now())
            ->sync(true, array('rows_found', 'started_at'), false);
        $profile->activity(__('Preparing data'));
        $profile->ioSeekReset();

        $this->_importPrepareColumns();
        $this->_prepareAttributes(array_keys($this->_fieldsCodes));
        $this->_prepareSystemAttributes();
        $this->_importValidateColumns();
        $this->_prepareCategories();

        $eventVars = [
            'profile' => &$this->_profile,
            'old_data' => &$this->_entities,
            'new_data' => &$this->_newData,
            'url_paths' => &$this->_urlPaths,
            'attr_value_ids' => &$this->_attrValueIds,
            'valid' => &$this->_valid,
            'insert_entity' => &$this->_insertEntity,
            'change_attr' => &$this->_changeAttr,
        ];

        $this->_profile->activity(__('Importing'));

        $this->_isLastPage = false;

        // data will loaded page by page to conserve memory
        for ($page = 0; ; $page++) {
            $this->_startLine = 2 + $page * $this->_pageRowCount;
            try {
                $this->_checkLock();

                if ($useTransactions && !$dryRun) {
                    $this->_write->beginTransaction();
                }

                $this->_importResetPageData();
                $this->_importFetchNewData();
                $this->_importFetchOldData();
                $this->_fetchAttributeValues($storeId, true);
                $this->_importProcessNewData();

                $this->_checkLock();

                $this->_eventManager->dispatch('urapidflow_category_import_after_fetch', ['vars' => $eventVars]);
                $this->_importValidateNewData();
                $this->_eventManager->dispatch('urapidflow_category_import_after_validate', ['vars' => $eventVars]);
                $this->_importProcessDataDiff();
                $this->_eventManager->dispatch('urapidflow_category_import_after_diff', ['vars' => $eventVars]);

                if (!$dryRun) {
                    $this->_importSaveEntities();
                    $this->_importGenerateAttributeValues();
                    $this->_importSaveAttributeValues();
                    $this->_eventManager->dispatch('urapidflow_category_import_after_save', ['vars' => $eventVars]);
                }

                $profile->setMemoryUsage(memory_get_usage(true))->setMemoryPeakUsage(memory_get_peak_usage(true))
                    ->setSnapshotAt(\Unirgy\RapidFlow\Helper\Data::now())->sync();

                if ($useTransactions && !$dryRun) {
                    $this->_write->commit();
                }

                if(!$dryRun){
                    $this->_updateUrlRewrites();
                }
            } catch (\Exception $e) {
                if ($useTransactions && !$dryRun) {
                    $this->_write->rollBack();
                }
#print_r($e);
                throw $e;
            }
            if ($this->_isLastPage) {
                break;
            }
            if ($this->_pageSleepDelay) {
                sleep($this->_pageSleepDelay);
            }
        }

        $profile->ioClose();
    }

    public function fetchSystemAttributes()
    {
        $this->_entityTypeId = $this->_getEntityType($this->_entityType, 'entity_type_id');
        $this->_prepareSystemAttributes();
        return $this->_attributesByCode;
    }

    protected function _importFetchNewData()
    {
        $defaultSeparator = $this->_profile->getData('options/csv/multivalue_separator');
        if (!$defaultSeparator) {
            $defaultSeparator = ';';
        }

        $this->_newData = [];

        // $i1 should be preserved during the loop
        for ($i1 = 0; $i1 < $this->_pageRowCount; $i1++) {
            $error = false;
            $row = $this->_profile->ioRead();
            if (!$row) {
                // last row
                $this->_isLastPage = true;
                return true;
            }
            $empty = true;
            foreach ($row as $v) {
                if (trim($v) !== '') {
                    $empty = false;
                    break;
                }
            }
            if ($empty) {
                $this->_profile->addValue('rows_empty');
                continue;
            }
            $this->_profile->addValue('rows_processed');
            $this->_profile->getLogger()->setLine($this->_startLine + $i1);
            if (empty($row[$this->_pathIdx]) || !empty($this->_newData[$row[$this->_pathIdx]])) {
                $this->_profile->addValue('rows_errors')->addValue('num_errors');
                $this->_profile->getLogger()->setColumn($this->_pathIdx + 1)
                    ->error(empty($row[$this->_pathIdx]) ? __('Empty URL Path') : __('Duplicate URL Path'));
                continue;
            }
            if ($this->_allowImportById) {
                $catKey = trim($row[$this->_entityIdIdx]);
            } else {
                $catKey = trim($row[$this->_pathIdx]);
            }
            $this->_pathLine[$catKey] = $this->_startLine + $i1;
            $this->_newData[$catKey] = $this->_newDataTemplate;
            $this->_defaultUsed[$catKey] = $this->_newDataTemplate;

            $error = false;
            foreach ($row as $col => $v) {
                if ($v !== '' && !isset($this->_fieldsIdx[$col])) {
                    $this->_profile->addValue('num_warnings');
                    $this->_profile->getLogger()->setColumn($col + 1)
                        ->warning(__('Column is out of boundaries, ignored'));
                    continue;
                }
                $k = $this->_fieldsIdx[$col];
                if ($k === false || $k === 'const.value') {
                    continue;
                }
                /*
                if ($k=='url_path' && $this->_upPrependRoot) {
                    $_up = @explode('/', $v, 2);
                    array_shift($_up);reset($_up);
                    $v = current($_up);
                }
                */
                $input = $this->_attr($k, 'frontend_input');
                $multiselect = $input === 'multiselect';
                $separator = trim(!empty($this->_fields[$k]['separator']) ? $this->_fields[$k]['separator'] : $defaultSeparator);
                try {
                    $v = $this->_convertEncoding($v);
                } catch (Exception $e) {
                    $this->_profile->addValue('num_warnings');
                    $this->_profile->getLogger()->setColumn($col + 1)->warning($e);
                    #error = true;
                }
                if ($v !== '') {
                    // options and multiselect
                    if ($input === 'select') {
                        $v = trim($v);
                    } elseif ($multiselect) {
                        $values = @explode($separator, $v);
                        $v = [];
                        foreach ($values as $v1) {
                            $v[] = $v1;
                        }
                    }
                }
                if (!isset($this->_defaultUsed[$catKey][$k]) || ($v !== '' && $v !== [])) {
                    $this->_newData[$catKey][$k] = $v;
                    unset($this->_defaultUsed[$catKey][$k]);
                }
            }
            if ($error) {
                unset($this->_newData[$catKey]);
            }
        }
        return false;
    }

    protected function _importProcessDataDiff()
    {
        //        $oldValues = [];

        $profile = $this->_profile;
        $forceUrlRewritesRefresh = $profile->getData('options/import/force_urlrewrite_refresh');
        // find changed data
        foreach ($this->_newData as $catKey => $p) {
            if (!$this->_valid[$catKey]) {
                continue;
            }

            $this->_profile->getLogger()->setLine($this->_pathLine[$catKey]);

            // check if the category is new
            if ($this->_allowImportById) {
                $urlPath = $p['url_path'];
                $isNew = !$this->catRowIdBySeqId($catKey);
            } else {
                $urlPath = $catKey;
                $isNew = empty($this->_urlPaths[$urlPath]) || $this->_urlPaths[$urlPath] === true;
            }

            $isUpdated = false;

            // create new category
            if ($isNew) {
                $this->_insertEntity[$catKey] = [
                    'attribute_set_id' => $this->_attributeSetId,
                    'created_at' => $this->_rapidFlowHelper->now(),
                    'updated_at' => $this->_rapidFlowHelper->now(),
                    'position' => isset($p['position']) ? $p['position'] : 0,
                    'url_path' => $urlPath
                ];
                if($this->_rapidFlowHelper->hasMageFeature(self::ROW_ID)){
                    $this->_insertEntity[$catKey]['entity_id'] = $this->_getNextCategorySequence((bool)$this->_profile->getData('options/import/dryrun'));
                    $this->_insertEntity[$catKey]['created_in'] = 1;
                    $this->_insertEntity[$catKey]['updated_in'] = \Magento\Staging\Model\VersionManager::MAX_VERSION;
                }
                $cId = null;
                $parentPath = $this->_parentPath[$catKey];
                $this->_changeChildrenCount[$catKey] = 0;
                if ($parentPath) {
                    if (!isset($this->_changeChildrenCount[$parentPath])) {
                        $count = !empty($this->_childrenCount[$parentPath]) ? $this->_childrenCount[$parentPath] : 0;
                        $this->_changeChildrenCount[$parentPath] = $count + 1;
                    } else {
                        $this->_changeChildrenCount[$parentPath]++;
                    }
                }
                if (!empty($this->_parentPathExtra[$urlPath])) {
                    foreach ($this->_parentPathExtra[$urlPath] as $parentPath) {
                        if (!isset($this->_changeChildrenCount[$parentPath])) {
                            $count = !empty($this->_childrenCount[$parentPath]) ? $this->_childrenCount[$parentPath] : 0;
                            $this->_changeChildrenCount[$parentPath] = $count + 1;
                        } else {
                            $this->_changeChildrenCount[$parentPath]++;
                        }
                    }
                }
            } else {
                if ($this->_allowImportById) {
                    $cId = $this->catRowIdBySeqId($catKey);
                } else {
                    $cId = $this->_urlPaths[$urlPath];
                }
                // $urlPath = $this->_entities[$cId][0]['url_path'];
                // $this->_psrLogLoggerInterface->log("cId: ".$cId, null, 'rf.log', true);
                // $this->_psrLogLoggerInterface->log("urlPath: ".$urlPath, null, 'rf.log', true);
                if (isset($p['position']) && $p['position'] !== '' && $p['position'] != $this->_entities[$cId][0]['position']) {
                    $this->_updateEntity[$cId]['position'] = $p['position'];
                    $isUpdated = true;
                }
            }

            // walk the attributes
            foreach ($p as $k => $newValue) {
                $this->_profile->getLogger()->setColumn(isset($this->_fieldsCodes[$k]) ? $this->_fieldsCodes[$k] + 1 : -1);

                $oldValue = !$cId ? null : (
                    isset($this->_entities[$cId][$this->_storeId][$k]) ? $this->_entities[$cId][$this->_storeId][$k] : (
                        isset($this->_entities[$cId][0][$k]) ? $this->_entities[$cId][0][$k] : null
                    )
                );
                $attr = $this->_attr($k);

                // convert options text to values
                /*
                if (!empty($attr['frontend_input'])) {
                    switch ($attr['frontend_input']) {
                    case 'select':
                        $lower = strtolower($newValue);
                        if ($newValue=='' && !isset($attr['options_bytext'][$lower])) {
                            $newValue = null;
                        } elseif (!is_null($newValue)) {
                            $newValue = $attr['options_bytext'][$lower];
                        }
                        break;

                    case 'multiselect':
                        $v1 = [];
                        foreach ((array)$newValue as $k1=>$v) {
                            $v1[] = $attr['options_bytext'][strtolower($v)];
                        }
                        $newValue = $v1;
                        break;
                    }
                }
                */

                // some validation happens here as well
                $this->_cleanupValues($attr, $oldValue, $newValue);

                if (empty($attr['attribute_id']) || empty($attr['backend_type']) || $attr['backend_type'] == 'static') {
                    continue;
                }
                // existing attribute values
                $isValueChanged = false;
                if (is_array($newValue)) {
                    $isValueChanged = array_diff($newValue, $oldValue) || array_diff($oldValue, $newValue);
                } else {
                    $isValueChanged = $newValue !== $oldValue;
                }
                // add updated attribute values
                $empty = $newValue === '' || null === $newValue || $newValue === [];
                if (($isNew && !$empty) || $isValueChanged) {

#$profile->getLogger()->log('DIFF', $p['url_path'].'/'.$k.': '.print_r($oldValue,1).';'.print_r($newValue,1));
//                    $oldValues[$urlPath][$k] = $oldValue;
                    if ($k === 'url_path' && $this->_upPrependRoot) { // strip prepended root category from path
                        $_up = @explode('/', $newValue, 2);
                        array_shift($_up);
                        reset($_up);
                        $newValue = current($_up);
                    }

                    if($k === 'url_key' || $k === 'is_anchor'){ // in these cases, url rewrites need to be updated
                        $this->_urlsToUpdate[$catKey] = true;
                    }
                    $this->_changeAttr[$catKey][$k] = $newValue;
                    if (!$isNew) {
                        $this->_profile->getLogger()->setColumn(isset($this->_fieldsCodes[$k]) ? $this->_fieldsCodes[$k] + 1 : -1)
                            ->success(null, null, $newValue, $oldValue);
                    }
                    $isUpdated = true;
                }
            } // foreach ($p as $k=>$newValue)
            if ($forceUrlRewritesRefresh) {
                $this->_urlsToUpdate[$catKey] = true;
            }

            if ($isUpdated) {
                $this->_profile->addValue('rows_success');
                /*
                $logger->setColumn(0);
                if (!empty($oldValues[$p['sku']])) $logger->success('OLD: '.print_r($oldValues[$p['sku']],1));
                if (!empty($this->_changeStock[$p['sku']])) $logger->success('STOCK: '.print_r($this->_changeStock[$p['sku']],1));
                if (!empty($this->_changeWebsite[$p['sku']])) $logger->success('WEBSITE: '.print_r($this->_changeWebsite[$p['sku']],1));
                if (!empty($this->_changeAttr[$p['sku']])) $logger->success('ATTR: '.print_r($this->_changeAttr[$p['sku']],1));
                */
                if (!$isNew) {
                    $this->_updateEntity[$cId]['updated_at'] = HelperData::now();
                }
            } else {
                $this->_profile->addValue('rows_nochange');
            }
        } // foreach ($this->_newData as $p)
        /*
        echo '<table><tr><td>';
        var_dump($oldValues);
        echo '</td><td>';
        var_dump($this->_changeAttr);
        echo '</td></tr></table>';
        exit;
        */
    }

    protected function _importValidateNewData()
    {
        $logger = $this->_profile->getLogger();
        $autoCreateOptions = $this->_profile->getData('options/import/create_options');
        $actions = $this->_profile->getData('options/import/actions');
        $allowSelectIds = $this->_profile->getData('options/import/select_ids');

        // find changed data
        foreach ($this->_newData as $catKey => $p) {
            $urlPath = $catKey;
            $logger->setLine($this->_pathLine[$catKey]);
            // check if the product is new
            if ($this->_allowImportById) {
                $urlPath = $p['url_path'];
                $isNew = !$this->catRowIdBySeqId($catKey);
            } else {
                $isNew = empty($this->_urlPaths[$urlPath]);
            }

            if (($isNew && $actions === 'update') || (!$isNew && $actions === 'create')) {
                $this->_profile->addValue('rows_nochange');
                $this->_valid[$catKey] = false;
                continue;
            }

            // validate required attributes
            $this->_valid[$catKey] = true;

            // check missing required columns
            foreach ($this->_attributesByCode as $k => $attr) {
                if (isset($p[$k]) || empty($attr['is_required']) || !$isNew) {
                    continue;
                }
                $this->_profile->addValue('num_errors');
                $logger->setColumn(1);
                $logger->error(__("Missing required value for '%1'", $k));
                $this->_valid[$catKey] = false;
            }

            if ($isNew) {
                if (strpos((string)$urlPath, '/') === false) {
                    $this->_parentPath[$catKey] = '';
                    $this->_urlPaths[$urlPath] = true;
                } else {
                    $parentPath = preg_replace('#/[^/]+$#', '', $urlPath);
                    $parentPathOrig = $parentPath;
                    if (empty($this->_urlPaths[$parentPath])) {
                        $parentPath = $this->_addPathSuffix($parentPath);
                    }
                    if (empty($this->_urlPaths[$parentPath])) {
                        $this->_profile->addValue('num_errors');
                        $logger->setColumn($this->_pathIdx + 1);
                        $logger->error(__("Invalid parent path '%1'", $parentPathOrig));
                        $this->_valid[$catKey] = false;
                    } else {
                        $this->_parentPath[$catKey] = $parentPath;
                        $this->_urlPaths[$urlPath] = true;
                    }
                    while (preg_match('#/[^/]+$#', $parentPath)
                        && ($parentPath = preg_replace('#/[^/]+$#', '', $parentPath))
                    ) {
                        $parentPathOrig = $parentPath;
                        if (empty($this->_urlPaths[$parentPath])) {
                            $parentPath = $this->_addPathSuffix($parentPath);
                        }
                        if (!empty($this->_urlPaths[$parentPath])) {
                            if (empty($this->_parentPathExtra[$urlPath])) {
                                $this->_parentPathExtra[$urlPath] = [];
                            }
                            $this->_parentPathExtra[$urlPath][] = $parentPath;
                        }
                    }
                }
            }

            // walk the attributes
            foreach ($p as $k => $newValue) {
                $attr = $this->_attr($k);
                $logger->setColumn(isset($this->_fieldsCodes[$k]) ? $this->_fieldsCodes[$k] + 1 : -1);

                $empty = is_null($newValue) || $newValue === '' || $newValue === [];
                $required = !empty($attr['is_required']);
                $selectable = !empty($attr['frontend_input']) && ($attr['frontend_input'] === 'select' || $attr['frontend_input'] === 'multiselect');

                if ($empty && $required) {
                    $this->_profile->addValue('num_errors');
                    $logger->error(__("Missing required value for '%1'", $k));
                    $this->_valid[$catKey] = false;
                    continue;
                }

                if ($selectable && !$empty) {
                    foreach ((array)$newValue as $i => $v) {
                        $vLower = strtolower(trim($v));
                        if (isset($this->_defaultUsed[$catKey][$k])) {
                            // default used, no mapping required
                        } elseif (isset($attr['options_bytext'][$vLower])) {
                            if (is_array($newValue)) {
                                $this->_newData[$catKey][$k][$i] = $attr['options_bytext'][$vLower];
                            } else {
                                $this->_newData[$catKey][$k] = $attr['options_bytext'][$vLower];
                            }
                        } elseif ($allowSelectIds && isset($attr['options'][$v])) {
                            // select id used, no mapping required
                        } else {
                            if ($autoCreateOptions && !empty($attr['attribute_id']) && (empty($attr['source_model'])
                                    || $attr['source_model'] === 'Magento\Eav\Model\Entity\Attribute\Source\Table')) {
                                $this->_importCreateAttributeOption($attr, $v);

                                $this->_profile->addValue('num_warnings');
                                $logger->warning(__("Created a new option '%1' for attribute '%2'", $v, $k));
                            } else {
#var_dump($attr); exit;
                                $this->_profile->addValue('num_errors');
                                $logger->error(__("Invalid option '%1'", $v));
                                $this->_valid[$catKey] = false;
                            }
                        }
                    } // foreach ((array)$newValue as $v)
                }
            } // foreach ($p as $k=>$newValue)

            if (!$this->_valid[$catKey]) {
                $this->_profile->addValue('rows_errors');
            }
        } // foreach ($this->_newData as $p)
        unset($p);
    }

}
