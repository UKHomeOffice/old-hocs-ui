<?php

namespace HomeOffice\CtsBundle\Form\Transition;

/**
 * Class SaveAndCreateLetterTransition
 *
 * @package HomeOffice\CtsBundle\Form\FormTransition\Transition
 */
class SaveAndCreateLetterTransition extends AbstractTransition implements TransitionInterface
{
    /**
     * @inheritdoc
     */
    protected function transition()
    {
        $this->saveCase();

        $documentTemplate = $this->getDocumentTemplateRepository()->getDocumentTemplateByNameMatch(
            $this->getCase(), true, 'Acknowledgement.letter'
        );

        $this->getAjaxResponseBuilder()->setSuccess(true);

        if (is_null($documentTemplate)) {
            $this->getAjaxResponseBuilder()
                ->setMessage('The acknowledgement letter could not be downloaded');
        } else {
            $this->getAjaxResponseBuilder()
                ->setMessage('The case has been saved')
                ->setRedirectToRoute('homeoffice_cts_documenttemplates_download', [
                    'nodeRef'     => $documentTemplate->getNodeId(),
                    'caseNodeRef' => $this->getCase()->getNodeId(),
                ]);
        }
    }
}
