<?php

namespace HomeOffice\CtsBundle\Form\Transition;

use Symfony\Component\Form\FormError;

/**
 * Class DocumentTransition
 *
 * @package HomeOffice\CtsBundle\Form\Transition
 */
class DocumentTransition extends AbstractTransition implements TransitionInterface
{
    /**
     * @inheritdoc
     */
    protected function transition()
    {
        switch ($this->getForm()->getClickedButton()->getName()) {
            case 'addDocument':
                $this->add();

                break;
            case 'removeDocument':
                $this->remove();
                break;
        }
    }

    /**
     * Handle Add Document
     *
     * @throws \Twig_Error
     */
    private function add()
    {
        $result = $this->getDocumentRepository()->create(
            $this->getForm()->getClickedButton()->getParent()->getData(),
            $this->getCase()->getId(),
            $this->getCase()->getNodeId()
        );

        if ($result !== true) {
            $this->getForm()->getClickedButton()->getParent()->get('file')->addError(new FormError($result));
            return;
        }

        $this->getCase()->setCaseDocuments(
            $this->getDocumentRepository()->getDocumentsForCase($this->getCase()->getNodeId())
        );

        $this->getAjaxResponseBuilder()
            ->setSuccess(true)
            ->setMessage('The document has been uploaded to the case')
            ->setCallback('uploadDocumentRefresh', [$this->getForm()->getClickedButton()->getParent()->getName()])
        ;
    }

    /**
     * Handle Remove Document
     *
     * @throws \Twig_Error
     */
    private function remove()
    {
        $nodeRef = $this->getDocumentToRemove();

        if (!$this->getDocumentRepository()->deleteDocument($nodeRef)) {
            $this->getForm()->addError(new FormError('There was a problem removing the document'));
            return;
        }

        $this->getAjaxResponseBuilder()
            ->setSuccess(true)
            ->setMessage('The document has been removed')
            ->setCallback('removeDocument', [$nodeRef]);
    }

    /**
     * @return string
     */
    private function getDocumentToRemove()
    {
        if ($this->getForm()->get('removeDocument')->has('documentToRemove')) {
            // @todo can be removed when traits are used for all case forms
            return $this->getForm()->get('removeDocument')->get('documentToRemove')->getData();
        }

        return $this->getForm()->get('documentToRemove')->getData();
    }
}
