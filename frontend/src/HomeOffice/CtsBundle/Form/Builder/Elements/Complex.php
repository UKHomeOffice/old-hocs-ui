<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class Complex
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait Complex
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function complex(FormBuilderInterface $builder)
    {
        $builder->add('complex', 'checkbox', [
            'label' => 'Complex',
        ]);

        return $this;
    }
}
