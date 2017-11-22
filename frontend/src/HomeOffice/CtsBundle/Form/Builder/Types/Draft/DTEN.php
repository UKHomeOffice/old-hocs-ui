<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Draft;

use HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DTEN
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Draft
 */
class DTEN extends AbstractDraftType
{
    use Groups\Document;
    use Groups\DocumentRemoval;
    use Groups\Allocate;
    use Elements\Save;

    /**
     * @inheritdoc
     */
    public function buildElements(FormBuilderInterface $builder, array $options)
    {
        $this
            ->documentForm($builder)
            ->documentRemoval($builder)
            ->allocate($builder, $this->getListService())
            ->save($builder)
        ;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'DTENDraft';
    }
}
