<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\CorrespondentType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SecondaryCorrespondentConsentAttached
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait SecondaryCorrespondentConsentAttached
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return $this
     */
    public function secondaryCorrespondentConsentAttached(FormBuilderInterface $builder)
    {
        $builder->add('secondaryCorrespondentConsentAttached', 'checkbox', [
            'label' => 'Consent attached',
        ]);

        return $this;
    }
}
