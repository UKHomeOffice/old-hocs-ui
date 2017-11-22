<?php

namespace HomeOffice\CtsBundle\Form\Builder\Types\Draft;

use HomeOffice\AlfrescoApiBundle\Service\HmpoResponse;
use HomeOffice\CtsBundle\Form\Builder\Elements;
use HomeOffice\CtsBundle\Form\Builder\Groups;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MIN
 *
 * @package HomeOffice\CtsBundle\Form\Builder\Types\Draft
 */
class MIN extends AbstractDraftType
{
    use Groups\LinkedCase;
    use Elements\HmpoResponse;
    use Elements\ResponsePhoneComment;
    use Elements\ReplyToEmail;
    use Groups\Document;
    use Groups\DocumentRemoval;
    use Groups\Allocate;
    use Elements\Save;

    /**
     * @inheritdoc
     */
    public function buildElements(FormBuilderInterface $builder, array $options)
    {
        $this
            ->hmpoResponse($builder, [HmpoResponse::EMAIL, HmpoResponse::POST, HmpoResponse::PHONE])
            ->responsePhoneComment($builder)
            ->replyToEmail($builder)
            ->linkedCaseForm($builder)
            ->documentForm($builder)
            ->documentRemoval($builder)
            ->allocate($builder, $this->getListService())
            ->save($builder);
        ;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'MINDraft';
    }
}
