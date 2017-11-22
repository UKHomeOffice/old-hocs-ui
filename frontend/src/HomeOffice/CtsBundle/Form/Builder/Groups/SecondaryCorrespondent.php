<?php

namespace HomeOffice\CtsBundle\Form\Builder\Groups;

use HomeOffice\AlfrescoApiBundle\Service\CorrespondentType;
use HomeOffice\CtsBundle\Form\Builder\Elements;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class SecondaryCorrespondent
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Groups
 */
trait SecondaryCorrespondent
{
    use Elements\SecondaryTypeOfCorrespondent;
    use Elements\SecondaryCorrespondentReplyTo;
    use Elements\SecondaryCorrespondentTitle;
    use Elements\SecondaryCorrespondentForename;
    use Elements\SecondaryCorrespondentSurname;
    use Elements\SecondaryCorrespondentPostCode;
    use Elements\SecondaryCorrespondentAddressLineOne;
    use Elements\SecondaryCorrespondentAddressLineTwo;
    use Elements\SecondaryCorrespondentAddressLineThree;
    use Elements\SecondaryCorrespondentCountry;
    use Elements\SecondaryCorrespondentTelephone;
    use Elements\SecondaryCorrespondentEmail;
    use Elements\SecondaryCorrespondentTypeOfRepresentative;
    use Elements\SecondaryCorrespondentConsentAttached;

    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function secondaryCorrespondent(FormBuilderInterface $builder)
    {
        $this
            ->secondaryTypeOfCorrespondent($builder)
            ->secondaryCorrespondentReplyTo($builder)
            ->secondaryCorrespondentTitle($builder)
            ->secondaryCorrespondentForename($builder)
            ->secondaryCorrespondentSurname($builder)
            ->secondaryCorrespondentPostCode($builder)
            ->secondaryCorrespondentAddressLineOne($builder)
            ->secondaryCorrespondentAddressLineTwo($builder)
            ->secondaryCorrespondentAddressLineThree($builder)
            ->secondaryCorrespondentCountry($builder)
            ->secondaryCorrespondentTelephone($builder)
            ->secondaryCorrespondentEmail($builder)
            ->secondaryCorrespondentTypeOfRepresentative($builder)
            ->secondaryCorrespondentConsentAttached($builder)
        ;

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event)  {
            // Remove type of representative and consent attached for applicants
            if ($event->getForm()->get('secondaryTypeOfCorrespondent')->getData() == CorrespondentType::APPLICANT) {
                $event->getData()->setSecondaryCorrespondentTypeOfRepresentative('');
                $event->getData()->setSecondaryCorrespondentConsentAttached(false);
            }
        });

        return $this;
    }
}
