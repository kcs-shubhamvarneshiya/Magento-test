<?php
    namespace Rysun\Category\Block;

    use Magento\Catalog\Block\Product\Context;

    class View extends \Magento\Framework\View\Element\Template {
        public $category;
        public $categoryRepository;
        public $frameworkRegistry;
        
        public function __construct(
            Context $context,
            array $data,
            \Magento\Catalog\Model\Category $category,
            \Magento\Catalog\Model\CategoryRepository $categoryRepository,
            \Magento\Framework\Registry $frameworkRegistry
        ) {
            $this->category = $category;
            $this->categoryRepository = $categoryRepository;
            $this->frameworkRegistry = $frameworkRegistry;
            parent::__construct($context, $data);
        }

        public function getCurrentCategory() {
            return $this->frameworkRegistry->registry('current_category');
        }
    }
?>