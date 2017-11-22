<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\SuperSearch;

use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceType;
use HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\CtsBundle\Form\Builder\Types\SuperSearch;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class FOI
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\SuperSearch
 */
class FOI extends SuperSearch
{
    use Elements\CaseType;
    use Elements\DateReceivedFrom;
    use Elements\DateReceivedTo;
    use Elements\HOCaseOfficer;
    use Elements\CorrespondentForename;
    use Elements\CorrespondentSurname;
    use Elements\CorrespondentPostCode;
    use Elements\CorrespondentEmail;
    use Elements\FoiIsEir;
    use Elements\FoiMinisterSignOff;
    use Elements\SuitableForDisclosure;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->remove('businessUnit');

        $this
            ->businessUnit($builder, 'FOI')
            ->caseType($builder, CaseCorrespondenceType::getCorrespondenceArrayWithSubTypes('FOI'), false)
            ->dateReceivedFrom($builder)
            ->dateReceivedTo($builder)
            ->hoCaseOfficer($builder)
            ->correspondentForename($builder)
            ->correspondentSurname($builder)
            ->correspondentPostCode($builder)
            ->correspondentEmail($builder)
            ->foiIsEir($builder, false)
            ->foiMinisterSignOff($builder, false)
            ->suitableForDisclosure($builder);
    }
}
