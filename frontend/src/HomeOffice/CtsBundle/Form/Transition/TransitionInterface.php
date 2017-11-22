<?php

namespace HomeOffice\CtsBundle\Form\Transition;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\CtsBundle\Form\AjaxResponseBuilder;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Interface FormTransitionInterface
 *
 * @package HomeOffice\CtsBundle\Form\Transition
 */
interface TransitionInterface
{
    /**
     * @param AjaxResponseBuilder $ajaxResponseBuilder
     * @param Form                $form
     * @param CtsCase             $case
     *
     * @return static
     */
    public function prepare(AjaxResponseBuilder $ajaxResponseBuilder, Form $form, CtsCase $case);

    /**
     * Handles the form transition.
     * Make all necessary repository requests and build the ajax response
     *
     * @return void
     */
    public function handle();

    /**
     * @return JsonResponse
     */
    public function getResponse();

    /**
     * @return AjaxResponseBuilder
     */
    public function getAjaxResponseBuilder();

    /**
     * @return static
     */
    public function validate();
}
