<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\SuperSearch;

use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceType;
use HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\CtsBundle\Form\Builder\Types\SuperSearch;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class DCU
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\SuperSearch
 */
class DCU extends SuperSearch
{
    use Elements\CaseType;
    use Elements\DateReceivedFrom;
    use Elements\DateReceivedTo;
    use Elements\Priority;
    use Elements\Advice;
    use Elements\Member;
    use Elements\MpReference;
    use Elements\HomeSecretaryReply;
    use Elements\ReplyToNumberTenCopy;
    use Elements\CorrespondentForename;
    use Elements\CorrespondentSurname;
    use Elements\CorrespondentPostCode;
    use Elements\CorrespondentEmail;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->remove('businessUnit');

        $this
            ->businessUnit($builder, 'DCU')
            ->caseType($builder, CaseCorrespondenceType::getCorrespondenceArrayWithSubTypes('DCU'), false)
            ->dateReceivedFrom($builder)
            ->dateReceivedTo($builder)
            ->priority($builder, false)
            ->advice($builder, false)
            ->member($builder, $this->listService->getMemberArray(), true)
            ->mpReference($builder)
            ->homeSecretaryReply($builder, false)
            ->replyToNumberTenCopy($builder, false)
            ->correspondentForename($builder)
            ->correspondentSurname($builder)
            ->correspondentPostCode($builder)
            ->correspondentEmail($builder)
        ;
    }
}
