<?php

namespace HomeOffice\CtsBundle\Form\GuftType\Components;

use Symfony\Component\Form\FormBuilderInterface;
use HomeOffice\AlfrescoApiBundle\Service\Title;
use HomeOffice\AlfrescoApiBundle\Service\Country;

/**
 * Class CorrespondentDetails
 *
 * @package HomeOffice\CtsBundle\Form\GuftType\Components
 */
trait CorrespondentDetails
{
    /**
     * @param FormBuilderInterface $builder
     */
    public function buildCorrespondentDetailsForm(FormBuilderInterface $builder)
    {
        $builder
            ->add('correspondentTitle', 'choice', [
                'choices'     => Title::getTitlesArray(),
                'required'    => false,
                'empty_value' => '',
                'label'       => 'Title',
                'attr' => [
                    'class' => 'chosen',
                    'data-placeholder' => '-',
                ],
            ])
            ->add('correspondentForename', 'text', [
                'label'    => 'Forename',
                'required' => false,
            ])
            ->add('correspondentSurname', 'text', [
                'required' => false,
                'label'    => 'Surname'
            ])
            ->add('correspondentPostcode', 'text', [
                'required' => false,
                'label'    => 'Postcode'
            ])
            ->add('correspondentAddressLine1', 'text', [
                'attr'     => ['placeholder' => 'Address line 1'],
                'label'    => 'Address',
                'required' => false,
            ])
            ->add('correspondentAddressLine2', 'text', [
                'attr'     => ['placeholder' => 'Address line 2'],
                'label'    => ' ',
                'required' => false,
            ])
            ->add('correspondentAddressLine3', 'text', [
                'attr'     => ['placeholder' => 'Address line 3'],
                'label'    => ' ',
                'required' => false,
            ])
            ->add('correspondentCountry', 'choice', [
                'choices' => Country::getCountriesArray(),
                'label'   => 'Country',
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

       if ($builder->getData()->getShortName() === 'CtsDcuTreatOfficialCase') {
            $builder
                ->add('replyToNumberTenCopy', 'checkbox', [
                    'required'  => false,
                    'label' => 'Send copy to No. 10'
                ]);
        }
    }
}
