<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Draft;

use HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class BaseFoi
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Draft
 */
abstract class BaseFoi extends AbstractDraftType
{
    use Groups\Document;
    use Groups\DocumentRemoval;
    use Groups\LinkedCase;
    use Elements\CorrespondentForename;
    use Elements\CorrespondentSurname;
    use Groups\MarkupTopics;
    use Elements\HoCaseOfficer;
    use Groups\Consultations;
    use Elements\Save;

    /**
     * @inheritdoc
     */
    public function buildElements(FormBuilderInterface $builder, array $options)
    {
        $this
            ->documentForm($builder)
            ->documentRemoval($builder)
            ->linkedCaseForm($builder)
            ->correspondentForename($builder, true)
            ->correspondentSurname($builder, true)
            ->markupTopics($builder, $this->getTopicsService())
            ->hoCaseOfficer($builder)
            ->consultations($builder)
            ->save($builder)
        ;
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    protected function getMinisterList()
    {
        return $this->getListService()->getMinisterArray();
    }
}
