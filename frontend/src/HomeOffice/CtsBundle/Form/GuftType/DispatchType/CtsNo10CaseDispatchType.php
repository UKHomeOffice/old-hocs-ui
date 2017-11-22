<?php

namespace HomeOffice\CtsBundle\Form\GuftType\DispatchType;

use HomeOffice\CtsBundle\Form\Builder\Groups\Document;
use HomeOffice\CtsBundle\Form\Builder\Groups\DocumentRemoval;
use HomeOffice\CtsBundle\Form\GuftType\Components\CtsCaseTransitions;
use HomeOffice\CtsBundle\Form\GuftType\GuftFormType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CtsNo10CaseDispatchType
 *
 * @package HomeOffice\CtsBundle\Form\GuftType\DispatchType
 */
class CtsNo10CaseDispatchType extends GuftFormType
{
    use CtsCaseTransitions;
    use Document;
    use DocumentRemoval;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildTransitionsForm($builder);

        if ($builder->getData()->getCorrespondenceType() === 'DTEN') {
             $this->documentForm($builder);
             $this->documentRemoval($builder);
        }

        if ($builder->getData()->getCorrespondenceType() === 'UTEN') {
            $builder->add('replyToNumberTenCopy', 'checkbox', [
                'label' => 'Copy sent to No. 10'
            ]);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'CtsNo10CaseDispatch';
    }

}
