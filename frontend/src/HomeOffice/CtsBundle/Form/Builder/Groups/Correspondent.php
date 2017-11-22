<?php

namespace HomeOffice\CtsBundle\Form\Builder\Groups;

use HomeOffice\AlfrescoApiBundle\Service\CorrespondentType;
use HomeOffice\CtsBundle\Form\Builder\Elements;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class Correspondent
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Groups
 */
trait Correspondent
{
    use Elements\TypeOfCorrespondent;
    use Elements\ReplyToCorrespondent;
    use Elements\CorrespondentTitle;
    use Elements\CorrespondentForename;
    use Elements\CorrespondentSurname;
    use Elements\CorrespondentPostCode;
    use Elements\CorrespondentAddressLineOne;
    use Elements\CorrespondentAddressLineTwo;
    use Elements\CorrespondentAddressLineThree;
    use Elements\CorrespondentCountry;
    use Elements\CorrespondentTelephone;
    use Elements\CorrespondentEmail;
    use Elements\TypeOfRepresentative;
    use Elements\ConsentAttached;

    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     * @deprecated use Correspondent::correspondent($builder) instead
     */
    protected function correspondentGroup(FormBuilderInterface $builder)
    {
        return $this->correspondent($builder);
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function correspondent(FormBuilderInterface $builder)
    {
        $this
            ->typeOfCorrespondent($builder)
            ->replyToCorrespondent($builder)
            ->correspondentTitle($builder)
            ->correspondentForename($builder)
            ->correspondentSurname($builder)
            ->correspondentPostCode($builder)
            ->correspondentAddressLineOne($builder)
            ->correspondentAddressLineTwo($builder)
            ->correspondentAddressLineThree($builder)
            ->correspondentCountry($builder)
            ->correspondentTelephone($builder)
            ->correspondentEmail($builder)
        ;

        if (property_exists($builder->getData(), 'typeOfRepresentative')) {
            $this->typeOfRepresentative($builder);
        }

        if (property_exists($builder->getData(), 'consentAttached')) {
            $this->consentAttached($builder);
        }

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event)  {
            // Remove type of representative and consent attached for applicants
            if ($event->getForm()->get('typeOfCorrespondent')->getData() == CorrespondentType::APPLICANT) {
                if (property_exists($event->getData(), 'typeOfRepresentative')) {
                    $event->getData()->setTypeOfRepresentative('');
                }

                if (property_exists($event->getData(), 'consentAttached')) {
                    $event->getData()->setConsentAttached(false);
                }
            }
        });

        return $this;
    }
}
