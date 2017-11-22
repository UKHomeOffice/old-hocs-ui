<?php

namespace HomeOffice\CtsBundle\Form;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class AjaxResponseBuilder
 *
 * @package HomeOffice\CtsBundle\Form
 */
class AjaxResponseBuilder
{
    /**
     * @var FormErrorSerializer
     */
    private $formErrorSerializer;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var EngineInterface
     */
    private $templateEngine;

    /**
     * @var Form
     */
    private $form;

    /**
     * @var bool
     */
    private $success = false;

    /**
     * @var string
     */
    private $redirect = '#content_top';

    /**
     * @var string
     */
    private $message = '';

    /**
     * @var string
     */
    private $callback = '';

    /**
     * @var array
     */
    private $callbackParameters = [];

    /**
     * Constructor
     *
     * @param FormErrorSerializer $formErrorSerializer
     * @param RouterInterface     $router
     * @param EngineInterface     $templateEngine
     */
    public function __construct(
        FormErrorSerializer $formErrorSerializer,
        RouterInterface $router,
        EngineInterface $templateEngine
    ) {
        $this->formErrorSerializer = $formErrorSerializer;
        $this->router = $router;
        $this->templateEngine = $templateEngine;
    }

    /**
     * Get Form
     *
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Set Form
     *
     * @param Form $form
     *
     * @return $this
     */
    public function setForm(Form $form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Get Success
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * Set Success
     *
     * @param bool $success
     *
     * @return $this
     */
    public function setSuccess($success)
    {
        $this->success = $success ? true : false;

        return $this;
    }

    /**
     * Get Redirect
     *
     * @return string
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

    /**
     * Set Redirect
     *
     * @param string $redirect
     *
     * @return $this
     */
    public function setRedirect($redirect)
    {
        $this->redirect = $redirect;

        return $this;
    }

    /**
     * Set Redirect To Route
     *
     * @param string $name
     * @param array  $parameters
     * @param bool   $referenceType
     *
     * @return $this
     */
    public function setRedirectToRoute($name, $parameters = [], $referenceType = RouterInterface::ABSOLUTE_PATH)
    {
        $this->setRedirect($this->router->generate($name, $parameters, $referenceType));

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
     * Get Callback
     *
     * @return string
     */
    public function getCallback()
    {
        if ($this->callback == '') {
            return null;
        }

        $parameters = [];
        for ($x = 0; $x < count($this->callbackParameters); $x++) {
            // We do this as a json_encode string is wrapped in quotes.
            // We dont want them but we do want the string escaped.
            // Requires improvement
            $parameters[] = trim($this->callbackParameters[$x], '"');
        }

        return sprintf(
            "%s(%s)",
            $this->callback,
            ($parameters ? sprintf('"%s"', implode('", "', $parameters)) : '')
        );
    }

    /**
     * Set Callback
     *
     * This will format the callback as `functionName('param', 'param'...)`
     *
     * @param string $callback
     * @param array  $callbackParameters
     *
     * @return $this
     */
    public function setCallback($callback, array $callbackParameters = [])
    {
        $this->callback = $callback;
        $this->setCallbackParameters($callbackParameters);

        return $this;
    }

    /**
     * Get CallbackParameters
     *
     * @return array
     */
    public function getCallbackParameters()
    {
        return $this->callbackParameters;
    }

    /**
     * Set CallbackParameters
     *
     * @param array $callbackParameters
     *
     * @return $this
     */
    public function setCallbackParameters(array $callbackParameters = [])
    {
        $this->callbackParameters = $callbackParameters;

        return $this;
    }

    /**
     * Get Template
     *
     * @param string $name
     * @param array $parameters
     *
     * @return string
     */
    public function getTemplate($name, $parameters = [])
    {
        return json_encode($this->templateEngine->render($name, $parameters));
    }

    /**
     * Create the Json Response
     *
     * @return JsonResponse
     */
    public function createResponse()
    {
        if ($this->isSuccess()) {
            return new JsonResponse([
                'success'  => $this->isSuccess(),
                'redirect' => $this->getRedirect(),
                'message'  => $this->getMessage(),
                'callback' => $this->getCallback(),
            ]);
        }

        return new JsonResponse([
            'success'     => $this->isSuccess(),
            'message'     => 'Please check the form',
            'description' => null,
            'errors'      => $this->formErrorSerializer->serializeFormErrors($this->form),
            'redirect'    => $this->getRedirect(),
        ], 400);
    }
}
