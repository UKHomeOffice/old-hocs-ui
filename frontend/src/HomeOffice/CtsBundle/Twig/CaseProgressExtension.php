<?php

namespace HomeOffice\CtsBundle\Twig;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Entity\Person;
use HomeOffice\AlfrescoApiBundle\Service\CaseProgressHelper;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class CaseProgressExtension
 *
 * @package HomeOffice\CtsBundle\Twig
 */
class CaseProgressExtension extends \Twig_Extension
{
    /**
     * @var CaseProgressHelper
     */
    private $caseProgressHelper;

    /**
     * Constructor
     *
     * @param CaseProgressHelper $caseProgressHelper
     */
    public function __construct(CaseProgressHelper $caseProgressHelper)
    {
        $this->caseProgressHelper = $caseProgressHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            'caseProgressClass' => new \Twig_Function_Method($this, 'getProgressClass'),
            'caseHasStep'       => new \Twig_Function_Method($this, 'hasStep'),
            'caseIsStep'        => new \Twig_Function_Method($this, 'isStep'),
            'caseIsAssigned'    => new \Twig_Function_Method($this, 'isAssigned'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getTests()
    {
        return [
            new \Twig_SimpleTest('case', function ($value) { return $value instanceof CtsCase; }),
        ];
    }

    /**
     * Get the class for the case progress item
     *
     * @param CtsCase $case
     * @param string  $activeStep
     * @param string  $compareStep
     *
     * @return string
     */
    public function getProgressClass(CtsCase $case = null, $activeStep, $compareStep)
    {
        if (!in_array($compareStep, $case ? $this->caseProgressHelper->getValidSteps($case) : ['create'])) {
            $class = 'disabled';
        } elseif ($activeStep == $compareStep) {
            $class = 'active';
        }

        return isset($class) ? 'class='.$class : '';
    }

    /**
     * @param CtsCase|null $case
     * @param string       $step
     *
     * @return bool
     */
    public function hasStep(CtsCase $case = null, $step)
    {
        return is_null($case) ?: $this->caseProgressHelper->hasStep($step, $case);
    }

    /**
     * @param CtsCase|null $case
     * @param string       $step
     *
     * @return bool
     */
    public function isStep(CtsCase $case, $step)
    {
        $constant = 'HomeOffice\AlfrescoApiBundle\Service\CaseProgressHelper::PROGRESS_'.strtoupper($step);
        return defined($constant) && $case->getCaseStatus() == constant($constant);
    }

    /**
     * @param CtsCase $case
     * @param Person  $user
     *
     * @return bool
     */
    public function isAssigned(CtsCase $case, Person $user)
    {
        return $case->getAssignedUser() == $user->getUserName() || $user->isManager();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'case_progress_extension';
    }
}
