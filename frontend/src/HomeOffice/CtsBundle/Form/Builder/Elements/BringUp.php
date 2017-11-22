<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;
use HomeOffice\AlfrescoApiBundle\Service\HmpoResponse as ServiceHmpoResponse;

/**
 * Class BringUp
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait BringUp
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function bringUp(FormBuilderInterface $builder)
    {
        $builder->add('bringUp', 'choice', [
            'label'      => 'Would you like to set a bring up date?',
            'choices'    => [true => 'Yes', false => 'No'],
            'mapped'     => false,
            'multiple'   => false,
            'expanded'   => true,
            'attr'       => ['class' => 'inline bringUpDateTrigger'],
            'label_attr' => ['class' => 'block-label inline'],
            'data'       => $builder->getData()->getBringUpDate() ? true : false,
        ]);

        return $this;
    }
}
