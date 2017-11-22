<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Draft;

use HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class COL
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Draft
 */
class COL extends AbstractDraftType
{
    use Elements\ExaminerSecurityCheck;
    use Elements\PassportStatus;
    use Elements\BringUpDate;
    use Elements\Defer;
    use Groups\Document;
    use Groups\DocumentRemoval;
    use Groups\Allocate;
    use Elements\Save;

    /**
     * @inheritdoc
     */
    protected function buildElements(FormBuilderInterface $builder, array $options)
    {
        $this
            ->documentForm($builder, 'document')
            ->documentRemoval($builder)
            ->examinerSecurityCheck($builder)
            ->passportStatus($builder)
            ->documentForm($builder, 'documentPassport')
            ->bringUpDate($builder)
            ->defer($builder)
            ->save($builder)
        ;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'COLDraft';
    }
}
