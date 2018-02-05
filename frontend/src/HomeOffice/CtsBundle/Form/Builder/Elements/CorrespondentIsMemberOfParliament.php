<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CorrespondentIsMemberOfParliament
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait CorrespondentIsMemberOfParliament
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function correspondentIsMemberOfParliament(FormBuilderInterface $builder)
    {
        /** @var CtsCase $case */
        $case = $builder->getData();
        $member = method_exists($case, 'getCorrespondentIsMemberOfParliament') ? $case->getCorrespondentIsMemberOfParliament() : null;
        $builder->add('correspondentIsMemberOfParliament', 'choice', [
            'label'      => 'Is the correspondent a Member of Parliament?',
            'choices'    => ['1' => 'Yes', '0' => 'No'],
            'multiple'   => false,
            'expanded'   => true,
            'attr'       => ['class' => 'memberParliamentTrigger inline'],
            'label_attr' => ['class' => 'block-label inline'],
            'data'       => $member == false ? 0 : 1
        ]);

        return $this;
    }
}
