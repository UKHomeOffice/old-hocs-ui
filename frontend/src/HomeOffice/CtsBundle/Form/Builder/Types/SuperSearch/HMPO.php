<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\SuperSearch;

use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceType;
use HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\CtsBundle\Form\Builder\Types\SuperSearch;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class HMPO
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\SuperSearch
 */
class HMPO extends SuperSearch
{
    use Elements\CaseType;
    use Elements\DateReceivedFrom;
    use Elements\DateReceivedTo;
    use Elements\CorrespondentForename;
    use Elements\CorrespondentSurname;
    use Elements\CorrespondentPostCode;
    use Elements\CorrespondentEmail;
    use Elements\PassportNumber;
    use Elements\ApplicationNumber;
    use Elements\CorrespondingName;
    use Elements\HmpoComplaintOutcome;
    use Elements\Party;
    use Elements\BringUpDate;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->remove('businessUnit');

        $this
            ->businessUnit($builder, 'HMPO')
            ->caseType($builder, CaseCorrespondenceType::getCorrespondenceArrayWithSubTypes('HMPO'), false)
            ->dateReceivedFrom($builder)
            ->dateReceivedTo($builder)
            ->correspondentForename($builder)
            ->correspondentSurname($builder)
            ->correspondentPostCode($builder)
            ->correspondentEmail($builder)
            ->passportNumber($builder)
            ->applicationNumber($builder)
            ->correspondingName($builder)
            ->party($builder)
            ->bringUpDate($builder);
    }
}
