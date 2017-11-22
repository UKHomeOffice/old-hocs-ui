<?php

namespace HomeOffice\CtsBundle\Form\Builder\Groups;

use HomeOffice\CtsBundle\Form\Builder\Elements;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class PublicInterestTest
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Groups
 */
trait Refund
{
    use Elements\HmpoRefund;
    use Elements\HmpoRefundAmount;
    use Elements\HmpoRefundDecision;

    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function refund(FormBuilderInterface $builder)
    {
        $this
            ->hmpoRefund($builder)
            ->hmpoRefundAmount($builder)
            ->hmpoRefundDecision($builder)
        ;

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event)  {
            // Remove Refund amount and decision if the refund option is 0(No)
            if ($event->getForm()->get('hmpoRefund')->getData() == 0) {
                $event->getData()->setHmpoRefundAmount('');
                $event->getData()->setHmpoRefundDecision('');
            }
        });

        return $this;
    }
}
