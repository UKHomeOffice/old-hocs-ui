<?php

namespace HomeOffice\CtsBundle\Form\Builder\Groups;

use HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\ListBundle\Service\ListService;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MemberOfParliament
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Groups
 */
trait MemberOfParliament
{
    use Elements\Member;
    use Elements\MpRef;
    use Elements\ReplyToPostcode;
    use Elements\ReplyToAddressLine1;
    use Elements\ReplyToAddressLine2;
    use Elements\ReplyToAddressLine3;
    use Elements\ReplyToCountry;
    use Elements\ReplyToEmail;
    use Elements\ReplyToTelephone;

    /**
     * @param FormBuilderInterface $builder
     * @param ListService          $listService
     *
     * @return static
     */
    protected function memberOfParliament(FormBuilderInterface $builder, ListService $listService)
    {
        $this
            ->member($builder, $listService->getMemberArray())
            ->mpRef($builder)
            ->replyToPostcode($builder)
            ->replyToAddressLine1($builder)
            ->replyToAddressLine2($builder)
            ->replyToAddressLine3($builder)
            ->replyToCountry($builder)
            ->replyToEmail($builder)
            ->replyToTelephone($builder)
        ;

        return $this;
    }
}
