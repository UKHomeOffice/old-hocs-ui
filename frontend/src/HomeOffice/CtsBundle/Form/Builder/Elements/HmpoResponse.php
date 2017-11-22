<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;
use HomeOffice\AlfrescoApiBundle\Service\HmpoResponse as HmpoResponseService;

/**
 * Class HmpoResponse
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait HmpoResponse
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $choices
     *
     * @return static
     */
    public function hmpoResponse(FormBuilderInterface $builder, array $choices = [])
    {
        $choices = empty($choices) ? HmpoResponseService::getAll(true) : array_combine($choices, $choices);

        $builder->add('hmpoResponse', 'choice', [
            'label'      => 'Response channel',
            'choices'    => $choices,
            'multiple'   => false,
            'expanded'   => true,
            'attr'       => ['class' => 'inline hmpo-response'],
            'label_attr' => ['class' => 'block-label inline'],
        ]);

        return $this;
    }
}
