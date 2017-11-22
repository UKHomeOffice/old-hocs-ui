<?php

namespace HomeOffice\CtsBundle\Form\GuftType\ApproveType;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\CtsBundle\Form\Builder\Groups\Document;
use HomeOffice\CtsBundle\Form\Builder\Groups\DocumentRemoval;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseTransitions;
use HomeOffice\CtsBundle\Form\GuftType\GuftFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CtsUkviCaseApproveType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType\ApproveType
 */
class CtsUkviCaseApproveType extends GuftFormType
{
    use CtsCaseTransitions;
    use Document;
    use DocumentRemoval;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildTransitionsForm($builder);

        /** @var CtsCase $case */
        $case = $builder->getData();
        if ($case->getCaseStatus() == 'Approvals') {
            $this->documentForm($builder);
            $this->documentRemoval($builder);
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase',
            'attr'               => ['novalidate' => 'novalidate'],
            'cascade_validation' => true,
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CtsUkviCaseApprove';
    }
}
