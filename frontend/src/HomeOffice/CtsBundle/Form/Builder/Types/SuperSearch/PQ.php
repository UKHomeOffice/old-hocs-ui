<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\SuperSearch;

use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceType;
use HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\CtsBundle\Form\Builder\Types\SuperSearch;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PQ
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\SuperSearch
 */
class PQ extends SuperSearch
{
    use Elements\CaseType;
    use Elements\Uin;
    use Elements\Keyword;
    use Elements\OpDate;
    use Elements\Member;
    use Elements\ReceivedType;
    use Elements\RoundRobin;
    use Elements\SignedBy;
    use Elements\ReviewedBy;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->remove('businessUnit');

        $this
            ->businessUnit($builder, 'PQ')
            ->caseType($builder, CaseCorrespondenceType::getCorrespondenceArrayWithSubTypes('PQ'), false)
            ->uin($builder, false)
            ->keyword($builder)
            ->opDate($builder)
            ->member($builder, $this->listService->getMemberArray(), true)
            ->receivedType($builder, true)
            ->roundRobin($builder, true)
            ->signedBy($builder)
            ->reviewedBy($builder)
        ;
    }
}
