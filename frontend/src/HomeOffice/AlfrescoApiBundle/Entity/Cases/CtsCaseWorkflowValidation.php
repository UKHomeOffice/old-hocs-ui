<?php

namespace HomeOffice\AlfrescoApiBundle\Entity\Cases;

/**
 * Class CtsCaseWorkflowValidation
 *
 * @package HomeOffice\AlfrescoApiBundle\Entity\Cases
 */
class CtsCaseWorkflowValidation
{
    /**
     * @var string
     */
    private $name;
 
    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $expression;
 
    /**
     * @param string      $name
     * @param string      $message
     * @param string|null $expression
     */
    public function __construct($name, $message, $expression = null)
    {
        $this->name = $name;
        $this->message = $message;

        $this->expression = $expression ?: sprintf(
            "notBlank(case.%s)",
            preg_replace('/^[a-zA-Z]*\:/', '', $name)
        );
    }

    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Name
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get Message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set Message
     *
     * @param string $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get Condition
     *
     * @return string
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * Set Condition
     *
     * @param string $expression
     *
     * @return $this
     */
    public function setExpression($expression)
    {
        $this->expression = $expression;

        return $this;
    }
}
