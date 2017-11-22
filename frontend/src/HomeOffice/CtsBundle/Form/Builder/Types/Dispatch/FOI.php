<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Dispatch;

use HomeOffice\AlfrescoApiBundle\Service\HmpoResponse;
use HomeOffice\CtsBundle\Form\Builder\Elements;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class FOI
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Dispatch
 */
class FOI extends BaseFoi
{
    use Elements\HmpoResponse;

    /**
     * @inheritdoc
     */
    public function buildElements(FormBuilderInterface $builder, array $options)
    {
        parent::buildElements($builder, $options);

        $this->hmpoResponse($builder, [
            HmpoResponse::EMAIL => HmpoResponse::EMAIL,
            HmpoResponse::POST  => HmpoResponse::POST,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'FOIDispatch';
    }
}
