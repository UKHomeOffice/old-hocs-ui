<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\SuperSearch;

use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceType;
use HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\CtsBundle\Form\Builder\Types\SuperSearch;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class UKVI
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\SuperSearch
 */
class UKVI extends SuperSearch
{
    use Elements\CaseType;
    use Elements\DateReceivedFrom;
    use Elements\DateReceivedTo;
    use Elements\Member;
    use Elements\CaseRef;
    use Elements\CorrespondentForename;
    use Elements\CorrespondentSurname;
    use Elements\CorrespondentPostCode;
    use Elements\CorrespondentEmail;
    use Elements\ReplyToNumberTenCopy;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->remove('businessUnit');

        $this
            ->businessUnit($builder, 'UKVI')
            ->caseType($builder, CaseCorrespondenceType::getCorrespondenceArrayWithSubTypes('UKVI'), false)
            ->dateReceivedFrom($builder)
            ->dateReceivedTo($builder)
            ->member($builder, $this->listService->getMemberArray(), true)
            ->correspondentForename($builder)
            ->correspondentSurname($builder)
            ->correspondentPostCode($builder)
            ->correspondentEmail($builder);
    }
}
