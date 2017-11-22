<?php

namespace HomeOffice\CtsBundle\Form\Builder\Elements;

use Symfony\Component\Form\FormBuilderInterface;
use HomeOffice\AlfrescoApiBundle\Service\HmpoResponse as ServiceHmpoResponse;

/**
 * Class BringUp
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Elements
 */
trait NewFile
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function newFile(FormBuilderInterface $builder)
    {
        $builder->add('newFile', 'choice', [
            'label'      => 'Would you like to upload a new document?',
            'choices'    => [true => 'Yes', false => 'No'],
            'mapped'     => false,
            'multiple'   => false,
            'expanded'   => true,
            'attr'       => ['class' => 'inline newFileTrigger'],
            'label_attr' => ['class' => 'block-label inline'],
            'data'       => false,
        ]);

        return $this;
    }
}
