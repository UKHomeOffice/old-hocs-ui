<?php

namespace HomeOffice\AlfrescoApiBundle\Service\DocumentPopulator;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use PhpOffice\PhpWord\TemplateProcessor;

/**
 * Class DocumentTemplatePopulator
 *
 * @package HomeOffice\AlfrescoApiBundle\Service
 */
class DocumentPopulator
{
    /**
     * @var TemplateProcessor
     */
    private $template;

    /**
     * @var DocumentVariable[]
     */
    private $variables = [];

    /**
     * @param CtsCase $case
     * @param string  $templateId
     *
     * @return bool
     */
    public function populate(CtsCase $case, $templateId)
    {
        try {
            $this->template = new TemplateProcessor('/tmp/'.$templateId);

            $this->retrieveVariables();
            $this->populateDateVariables();
            $this->populateCaseVariables($case);
            $this->template->saveAs('/tmp/'.$templateId);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @return $this
     */
    private function populateDateVariables()
    {
        foreach ($this->getVariablesByType('date') as $variable) {
            switch ($variable->getName()) {
                case 'today':
                    $this->template->setValue($variable->getKey(), date($variable->getParameter(1)));
                    break;
            }
        }

        return $this;
    }

    /**
     * @param CtsCase $case
     *
     * @return $this
     */
    private function populateCaseVariables(CtsCase $case)
    {
        foreach ($this->getVariablesByType('case') as $variable) {
            $method = 'get'.ucwords($variable->getName());

            if (method_exists($case, $method)) {
                if ($case->$method() instanceof \DateTime) {
                    $replace = $case->$method()->format($variable->getParameter(1));
                } else {
                    $replace = call_user_func_array([$case, $method], $variable->getParameters());
                    $replace = $this->updateBreakLines($replace);
                }
            } else {
                $replace = '';
            }

            $this->template->setValue($variable->getKey(), $replace);
        }

        return $this;
    }

    /**
     * @param string|null $type
     *
     * @return DocumentVariable[]
     */
    private function getVariablesByType($type = null)
    {
        $variables = [];
        foreach ($this->variables as $variable) {
            if ($variable->getType() == $type) {
                array_push($variables, $variable);
            }
        }

        return $variables;
    }

    /**
     * @return $this
     */
    private function retrieveVariables()
    {
        /** @var string $variable */
        foreach ($this->template->getVariables() as $variable) {
            array_push($this->variables, new DocumentVariable($variable));
        }

        return $this;
    }

    /**
     * @param string $subject
     *
     * @return string
     */
    private function updateBreakLines($subject)
    {
        return preg_replace('/\<br(|\/)\>/', '<w:br/>', html_entity_decode($subject));
    }
}
