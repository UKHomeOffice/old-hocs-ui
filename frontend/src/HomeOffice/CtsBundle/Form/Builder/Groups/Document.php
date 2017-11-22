<?php

namespace HomeOffice\CtsBundle\Form\Builder\Groups;

use HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\CtsBundle\Form\DataTransformer\CaseToDocumentTransformer;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class Document
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Groups
 */
trait Document
{
    use Elements\DocumentType;
    use Elements\DocumentDescription;
    use Elements\File;
    use Elements\AddDocument;

    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    protected function document(FormBuilderInterface $builder)
    {
        $this
            ->documentType($builder)
            ->documentDescription($builder)
            ->file($builder)
            ->addDocument($builder)
        ;

        $builder->addModelTransformer(new CaseToDocumentTransformer());

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param string               $name
     *
     * @return static
     */
    public function documentForm(FormBuilderInterface $builder, $name = 'document')
    {
        $builder->add($name, 'Document', [
            'label'  => '',
            'data'   => $builder->getData(),
            'mapped' => false,
        ]);

        return $this;
    }
}
