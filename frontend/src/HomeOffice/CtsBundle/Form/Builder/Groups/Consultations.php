<?php

namespace HomeOffice\CtsBundle\Form\Builder\Groups;

use HomeOffice\CtsBundle\Form\Builder\Elements;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class Consultations
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Groups
 */
trait Consultations
{
    use Elements\AcpoConsultation;
    use Elements\CabinetOfficeConsultation;
    use Elements\NslgConsultation;
    use Elements\RoyalsConsultation;
    use Elements\RoundRobinAdviceConsultation;

    /**
     * @param FormBuilderInterface $builder
     *
     * @return static
     */
    public function consultations(FormBuilderInterface $builder)
    {
        $this
            ->acpoConsultation($builder)
            ->cabinetOfficeConsultation($builder)
            ->nslgConsultation($builder)
            ->royalsConsultation($builder)
            ->roundRobinAdviceConsultation($builder)
        ;

        return $this;
    }
}
