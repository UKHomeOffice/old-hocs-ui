<?php

namespace HomeOffice\CtsBundle\Form\Builder\Groups;

use HomeOffice\AlfrescoApiBundle\Service\CorrespondentType;
use HomeOffice\CtsBundle\Form\Builder\Elements;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class TertiaryCorrespondent
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Groups
 */
trait TertiaryCorrespondent
{
    use Elements\TertiaryTypeOfCorrespondent;
    use Elements\TertiaryCorrespondentReplyTo;
    use Elements\TertiaryCorrespondentTitle;
    use Elements\TertiaryCorrespondentForename;
    use Elements\TertiaryCorrespondentSurname;
    use Elements\TertiaryCorrespondentPostCode;
    use Elements\TertiaryCorrespondentAddressLineOne;
    use Elements\TertiaryCorrespondentAddressLineTwo;
    use Elements\TertiaryCorrespondentAddressLineThree;
    use Elements\TertiaryCorrespondentCountry;
    use Elements\TertiaryCorrespondentTelephone;
    use Elements\TertiaryCorrespondentEmail;
    use Elements\TertiaryCorrespondentTypeOfRepresentative;
    use Elements\TertiaryCorrespondentConsentAttached;

    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function tertiaryCorrespondent(FormBuilderInterface $builder)
    {
        $this
            ->tertiaryTypeOfCorrespondent($builder)
            ->tertiaryCorrespondentReplyTo($builder)
            ->tertiaryCorrespondentTitle($builder)
            ->tertiaryCorrespondentForename($builder)
            ->tertiaryCorrespondentSurname($builder)
            ->tertiaryCorrespondentPostCode($builder)
            ->tertiaryCorrespondentAddressLineOne($builder)
            ->tertiaryCorrespondentAddressLineTwo($builder)
            ->tertiaryCorrespondentAddressLineThree($builder)
            ->tertiaryCorrespondentCountry($builder)
            ->tertiaryCorrespondentTelephone($builder)
            ->tertiaryCorrespondentEmail($builder)
            ->tertiaryCorrespondentTypeOfRepresentative($builder)
            ->tertiaryCorrespondentConsentAttached($builder)
        ;

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event)  {
            // Remove type of representative and consent attached for applicants
            if ($event->getForm()->get('thirdPartyTypeOfCorrespondent')->getData() == CorrespondentType::APPLICANT) {
                $event->getData()->setThirdPartyCorrespondentTypeOfRepresentative('');
                $event->getData()->setThirdPartyCorrespondentConsentAttached(false);
            }
        });

        return $this;
    }
}
