<?php

namespace HomeOffice\AlfrescoApiBundle\Consumer;

/**
 * Class ForgottenPassword
 *
 * @package HomeOffice\AlfrescoApiBundle\Consumer
 * @author  Adam Lewis <jonathan.webster@homeoffice.digital.gov.uk>
 * @since   2016-09-23
 */
final class ForgottenPassword extends AbstractConsumer
{
    /**
     * @var string
     */
    protected $url = 's/homeoffice/ctsv2/user/resetPassword';

}
