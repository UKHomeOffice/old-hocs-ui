<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ReviewedBy
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait ReviewedBy
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function reviewedBy(FormBuilderInterface $builder)
    {
        $builder->add('reviewedBy', 'choice', [
            'choices'    => [
                'Perm sec' => 'Perm sec',
                'SpAds'    => 'SpAds',
                ''         => 'Any',
            ],
            'multiple'   => false,
            'expanded'   => true,
            'attr'       => ['class' => 'inline'],
            'label'      => 'Reviewed By',
            'label_attr' => ['class' => 'block-label']
        ]);

        return $this;
    }
}
