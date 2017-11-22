<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\CorrespondentType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TertiaryCorrespondentConsentAttached
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait TertiaryCorrespondentConsentAttached
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return $this
     */
    public function tertiaryCorrespondentConsentAttached(FormBuilderInterface $builder)
    {
        $builder->add('thirdPartyCorrespondentConsentAttached', 'checkbox', [
            'label' => 'Consent attached',
        ]);

        return $this;
    }
}
