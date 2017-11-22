<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Create;

use HomeOffice\AlfrescoApiBundle\Service\Channel;
use HomeOffice\CtsBundle\Form\Builder\Elements;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class FOI
 *
 * @package HomeOffice\CtsBundle\Form\Type\Builder\Types\Create
 */
class FOI extends BaseFoi
{
    use Elements\OriginalChannel;

    /**
     * @inheritdoc
     */
    protected function buildElements(FormBuilderInterface $builder, array $options)
    {
        parent::buildElements($builder, $options);

        $this->originalChannel($builder, [Channel::EMAIL, Channel::POST, Channel::FAX]);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'FOICreate';
    }
}
