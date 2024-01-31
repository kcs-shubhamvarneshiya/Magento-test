<?php

namespace Capgemini\BloomreachThematic\Helper;

use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Framework\Exception\InvalidArgumentException;
use Magento\Framework\Registry;

class Output
{
    private Registry $coreRegistry;

    public function __construct(Registry $coreRegistry)
    {
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * @param CategoryInterface|null $category
     * @return string
     * @throws InvalidArgumentException
     */
    public function prepareDescription(?CategoryInterface $category = null): string
    {
        $category = $this->ensureCorrectCategory($category);
        $name = $category->getName();
        $contentPlacement1 = $category->getData('content_placement_1');
        $contentPlacement2 = $category->getData('content_placement_2');

        return <<<HTML
<style>
    #html-body [data-pb-style=MP6TDQL]{
        justify-content:flex-start;
        display:flex;
        flex-direction:column;
        background-position:left top;
        background-size:cover;
        background-repeat:no-repeat;
        background-attachment:scroll;
        border-style:none;
        border-width:1px;
        border-radius:0;
        margin:0 0 10px;
        padding:10px
    }
</style>
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div data-enable-parallax="0"
         data-parallax-speed="0.5"
         data-background-images="{}"
         data-background-type="image"
         data-video-loop="true"
         data-video-play-only-visible="true"
         data-video-lazy-load="true"
         data-video-fallback-src=""
         data-element="inner"
         data-pb-style="MP6TDQL">
        <div data-content-type="text" data-appearance="default" data-element="main">
            <p>
                <span style="color: #454545;
                            font-family: proxima-nova, sans-serif;
                            letter-spacing: 0.25px;
                            background-color: #f8f8f8;">$contentPlacement1</span>
            </p>
        </div>
        <h1 data-content-type="heading" data-appearance="default" data-element="main">$name</h1>
        <div data-content-type="text" data-appearance="default" data-element="main">
            <p>
                <span style="color: #454545;
                            font-family: proxima-nova, sans-serif;
                            letter-spacing: 0.25px;
                            background-color: #f8f8f8;">$contentPlacement2</span>
            </p>
        </div>
    </div>
</div>
HTML;
    }

    /**
     * @param CategoryInterface|null $category
     * @return string|null
     * @throws InvalidArgumentException
     */
    public function getPageName(?CategoryInterface $category = null): ?string
    {
        $category = $this->ensureCorrectCategory($category);

        return $category->getName();
    }

    /**
     * @param CategoryInterface|null $category
     * @return CategoryInterface
     * @throws InvalidArgumentException
     */
    private function ensureCorrectCategory(?CategoryInterface $category = null): CategoryInterface
    {
        $category = $category ?? $this->coreRegistry->registry('current_category');

        if (!$category instanceof CategoryInterface || $category->getParentId() != 1) {

            throw new InvalidArgumentException(__('Category value is of incorrect type.'));
        }

        return $category;
    }
}
