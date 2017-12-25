<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Approve;

use HomeOffice\CtsBundle\Form\Builder\Elements;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class FOI
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Approve
 */
class FOI extends BaseFoi
{
    use Elements\FoiMinisterSignOff;
    use Elements\AnsweringMinister;

    /**
     * @inheritdoc
     */
    protected function buildElements(FormBuilderInterface $builder, array $options)
    {
        parent::buildElements($builder, $options);
        $this
            ->foiMinisterSignOff($builder)
            ->answeringMinister(
                $builder,
                $this->getMinisterList(),
                'Sign off Minister',
                'Select Sign off Minister'
            );
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'FOIApprove';
    }
}
