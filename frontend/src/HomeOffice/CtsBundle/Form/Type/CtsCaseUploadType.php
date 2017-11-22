<?php

namespace HomeOffice\CtsBundle\Form\Type;

use HomeOffice\AlfrescoApiBundle\Entity\CtsTsoFeed;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CtsCaseUploadType extends AbstractType
{
 
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file', 'file')
             
        ->add('uploadButton', 'submit', array(
            'label' => 'Upload feed',
            'attr' => array(
            ),
        ));
    }
 
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'HomeOffice\AlfrescoApiBundle\Entity\CtsTsoFeed',
            'empty_data' => new CtsTsoFeed()
        ));
    }
 
    public function getName()
    {
        return 'ctsCaseUpload';
    }
}
