<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use HomeOffice\AlfrescoApiBundle\Service\Channel;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class OriginalChannel
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait OriginalChannel
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $choices
     *
     * @return static
     */
    protected function originalChannel(FormBuilderInterface $builder, array $choices = [])
    {
        $choices = empty($choices) ? [Channel::EMAIL, Channel::POST, Channel::PHONE, Channel::NO10] : $choices;

        $builder->add('channel', 'choice', [
            'label'      => 'Original channel',
            'choices'    => array_combine($choices, $choices),
            'multiple'   => false,
            'expanded'   => true,
            'attr'       => ['class' => 'inline'],
            'label_attr' => ['class' => 'block-label inline']
        ]);

        return $this;
    }
}
