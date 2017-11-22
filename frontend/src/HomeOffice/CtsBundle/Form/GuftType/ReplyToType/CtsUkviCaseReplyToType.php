<?php

namespace HomeOffice\CtsBundle\Form\GuftType\ReplyToType;

use HomeOffice\AlfrescoApiBundle\Service\Country;
use HomeOffice\AlfrescoApiBundle\Service\Title;
use HomeOffice\CtsBundle\Form\GuftType\CtsCaseReplyToType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CtsUkviCaseReplyToType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType\ReplyToType
 */
class CtsUkviCaseReplyToType extends CtsCaseReplyToType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        if ($builder->getData()->getCorrespondenceType() === 'IMCM') {
                $builder->add('correspondentIsMemberOfParliament', 'choice', [
                    'label' => 'Is the correspondent a Member of Parliament?',
                    'choices' => ['1' => 'Yes', '0' => 'No'],
                    'multiple' => false,
                    'expanded' => true,
                    'attr' => ['class' => 'memberParliamentTrigger inline'],
                    'label_attr' => ['class' => 'block-label inline'],
                ]);
        }

        $builder
            ->add('mpRef', 'text', [
                'label'    => 'MP reference',
                'required' => false,
            ])
            ->add('replyToPostcode', 'text', [
                'label'    => 'Postcode',
                'required' => false,
            ])
            ->add('replyToAddressLine1', 'text', [
                'label'    => 'Address',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Address line 1',
                ],
            ])
            ->add('replyToAddressLine2', 'text', [
                'label'    => 'Address',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Address line 2',
                ],
            ])
            ->add('replyToAddressLine3', 'text', [
                'label'    => 'Address',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Address line 3',
                ],
            ])
            ->add('replyToCountry', 'choice', [
                'choices' => Country::getCountriesArray(),
                'label'   => 'Country',
                'attr' => [
                    'class' => 'chosen',
                ],
            ])
            ->add('replyToTelephone', 'text', [
                'required' => false,
                'label'    => 'Telephone',
            ])
            ->add('replyToEmail', 'text', [
                'required' => false,
                'label'    => 'Email',
            ])
            ->add('replyToNumberTenCopy', 'checkbox', [
                'required' => false,
                'label'    => 'Send copy to No. 10',
            ]);

        $builder
            ->add('correspondentTitle', 'choice', [
                'empty_value' => '',
                'choices'     => Title::getTitlesArray(),
                'label'       => 'Title',
                'attr' => [
                    'class' => 'chosen',
                    'data-placeholder' => '-',
                ],
            ])
            ->add('correspondentForename', 'text', [
                'required' => false,
                'label' => 'Forename',
            ])
            ->add('correspondentSurname', 'text', [
                'required' => false,
                'label'    => 'Surname',
            ])
            ->add('correspondentPostcode', 'text', [
                'required' => false,
                'label'    => 'Postcode',
            ])
            ->add('correspondentAddressLine1', 'text', [
                'label'    => 'Address',
                'required' => false,
                'attr'     => ['placeholder' => 'Address line 1'],
            ])
            ->add('correspondentAddressLine2', 'text', [
                'label'    => 'Address',
                'required' => false,
                'attr'     => ['placeholder' => 'Address line 2'],
            ])
            ->add('correspondentAddressLine3', 'text', [
                'label'    => 'Address',
                'required' => false,
                'attr'     => ['placeholder' => 'Address line 3'
                ],
            ])
            ->add('correspondentCountry', 'choice', [
                'choices' => Country::getCountriesArray(),
                'label'    => 'Country',
                'attr' => [
                    'class' => 'chosen',
                ],
            ])
            ->add('correspondentTelephone', 'text', [
                'required' => false,
                'label'    => 'Telephone',
            ])
            ->add('correspondentEmail', 'text', [
                'required' => false,
                'label'    => 'Email',
            ]);

        $builder
            ->add('thirdPartyCorrespondentTitle', 'choice', [
                'empty_value' => '-',
                'choices'     => Title::getTitlesArray(),
                'label'       => 'Title',
                'attr' => [
                    'class' => 'chosen',
                ],
            ])
            ->add('thirdPartyCorrespondentForename', 'text', [
                'required' => false,
                'label'    => 'Forename',
            ])
            ->add('thirdPartyCorrespondentSurname', 'text', [
                'required' => false,
                'label'    => 'Surname',
            ])
            ->add('thirdPartyCorrespondentPostcode', 'text', [
                'required' => false,
                'label'    => 'Postcode',
            ])
            ->add('thirdPartyCorrespondentAddressLine1', 'text', [
                'label'    => 'Address',
                'required' => false,
                'attr'     => ['placeholder' => 'Address line 1'],
            ])
            ->add('thirdPartyCorrespondentAddressLine2', 'text', [
                'label'    => 'Address',
                'required' => false,
                'attr'     => ['placeholder' => 'Address line 2'],
            ])
            ->add('thirdPartyCorrespondentAddressLine3', 'text', [
                'label'    => 'Address',
                'required' => false,
                'attr'     => ['placeholder' => 'Address line 3'],
            ])
            ->add('thirdPartyCorrespondentCountry', 'choice', [
                'choices' => Country::getCountriesArray(),
                'label'    => 'Country',
                'attr' => [
                    'class' => 'chosen',
                ],
            ])
            ->add('thirdPartyCorrespondentTelephone', 'text', [
                'required' => false,
                'label'    => 'Telephone',
            ])
            ->add('thirdPartyCorrespondentEmail', 'text', [
                'required' => false,
                'label'    => 'Email',
            ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CtsUkviCaseReplyTo';
    }
}
