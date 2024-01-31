<?php

namespace Lyonscg\Contact\Model\Config\Source;

use Amasty\Customform\Api\FormRepositoryInterface;
use Magento\Framework\Data\OptionSourceInterface;

class Form implements OptionSourceInterface
{
    /**
     * @var FormRepositoryInterface
     */
    private $formRepository;

    public function __construct(
        FormRepositoryInterface $formRepository
    ) {
        $this->formRepository = $formRepository;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        $res = [];
        $res[] = ['value' => '', 'label' => __('Please, select the form')];
        try {
            $forms = $this->formRepository->getList();
            foreach ($forms as $form) {
                $res[] = ['value' => $form->getFormId(), 'label' => $form->getTitle()];
            }
        } catch (\Exception $e) {}
        return $res;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return $this->getOptions();
    }
}
