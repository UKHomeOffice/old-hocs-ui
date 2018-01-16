<?php
namespace HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceType;
use Symfony\Component\Form\FormBuilderInterface;
/**
 * Class BusinessUnit
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait BusinessUnit
{
    /**
     * @param FormBuilderInterface $builder
     * @param string|null          $selected
     *
     * @return static
     */
    public function businessUnit(FormBuilderInterface $builder, $selected = null)
    {
        $choices = array_merge(['All' => 'All'], CaseCorrespondenceType::getCorrespondenceArray());
        $builder->add('businessUnit', 'choice', [
            'choices'     => $choices,
            'placeholder' => 'All',
            'label'       => 'Select business unit',
            'label_attr'  => ['class' => 'form-label'],
            'data'        => null,
            'attr'        => [
                'class'            => 'chosen form-control form-allocate-unit',
                'data-placeholder' => 'Please select a unit',
            ],
        ]);
        return $this;
    }
}