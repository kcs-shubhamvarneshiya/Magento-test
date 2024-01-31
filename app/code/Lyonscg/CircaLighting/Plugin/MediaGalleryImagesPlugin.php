<?php

namespace Lyonscg\CircaLighting\Plugin;

use Magento\Catalog\Model\Product;
use Magento\Framework\Data\Collection;

class MediaGalleryImagesPlugin
{
    /**
     * @param Product $subject
     * @return array
     */
    public function beforeGetMediaGalleryImages(Product $subject): array
    {
        if ($subject->hasData('media_gallery_images')) {
            $mediaGalleryImages = $subject->getData('media_gallery_images');
            if (!is_object($mediaGalleryImages) || !$mediaGalleryImages instanceof Collection) {
                $subject->unsetData('media_gallery_images');
            }
        }

        return [];
    }
}
