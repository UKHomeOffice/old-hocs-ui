<?php

namespace HomeOffice\ProcessManagerAuthenticatorBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use HomeOffice\ProcessManagerAuthenticatorBundle\Form\Type\ResetPasswordType;
use HomeOffice\ProcessManagerAuthenticatorBundle\Form\Type\ForgottenPasswordType;
use HomeOffice\AlfrescoApiBundle\v2\Repository\MessageOfTheDay;

class SecurityController extends Controller
{
    // messages received from alfresco
    const SAME_PASSWORD_ERROR = 'New password must not match old password.';
    const PASSWORD_COMPLEXITY_ERROR = 'Your password is not complex enough.';
    // message sent back to user
    //@codingStandardsIgnoreStart
    const USER_RESET_PASSWORD_ERROR = 'Unable to reset password, please ensure you have entered the correct username and password.';
    //@codingStandardsIgnoreEnd
    const USER_PASSWORD_COMPLEXITY_ERROR = 'Your password is not complex enough.';
    const USER_SAME_PASSWORD_ERROR = 'Your new password must not be the same as your current password.';
    const USER_RESET_PASSWORD_SUCCESS = 'Password reset successful.';
 
    /**
     * @Template()
     */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();
        $successMessage = $session->get('successMessage');
        if (isset($successMessage)) {
            $session->set('successMessage', null);
        }

        // get the login error if there is one
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContextInterface::AUTHENTICATION_ERROR
            );
        } elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME);
     
        if ($error instanceof \Symfony\Component\Security\Core\Exception\AccountExpiredException) {
            return $this->redirect($this->generateUrl('reset_password', array('username' => $lastUsername)));
        }

        $messageOfTheDay = $this->get('home_office_alfresco_api.consumer.message_of_the_day');

        return array(
            // last username entered by the user
            'last_username' => $lastUsername,
            'error' => $error,
            'successMessage' => $successMessage,
            'messageOfTheDay' => $messageOfTheDay->get()
        );
    }
 
    /**
     * @Template
     * @Route("/signout")
     * @Method({"GET"})
     */
    public function signoutAction()
    {
        $personRepository = $this->get('home_office_alfresco_api.person.repository');
        $personRepository->signoutUser();
     
        $this->get('security.context')->setToken(null);
        $this->get('request')->getSession()->invalidate();
     
        return $this->redirect($this->generateUrl('login'));
    }
 
    /**
     * @Template
     * @Route("/resetPassword")
     * @Method({"GET", "POST"})
     */
    public function resetPasswordAction(Request $request)
    {
        $error = null;
        $username = $this->get('security.context')->getToken()->getUser()->getUserName();
        $resetPasswordForm = $this->createForm(new ResetPasswordType());
     
        $resetPasswordForm->handleRequest($request);

        if ($resetPasswordForm->isValid()) {
            $formData = $resetPasswordForm->getData();
            $currentPassword = $formData['currentPassword'];
            $newPassword = $formData['newPassword'];
            // send POST request to alfresco to reset password
            $personRepo = $this->get('home_office_alfresco_api.person.repository');
            $result = $personRepo->resetPassword($username, $currentPassword, $newPassword);

            if (is_array($result)) {
                if ($result[0] == 400 && strpos($result[1], self::PASSWORD_COMPLEXITY_ERROR)) {
                    $error = self::USER_PASSWORD_COMPLEXITY_ERROR;
                } elseif ($result[0] == 400 && strpos($result[1], self::SAME_PASSWORD_ERROR)) {
                    $error = self::USER_SAME_PASSWORD_ERROR;
                } else {
                    $error = self::USER_RESET_PASSWORD_ERROR;
                }
            }

            if (is_bool($result) && $result) {
                $session = $request->getSession();
                $session->set('successMsg', self::USER_RESET_PASSWORD_SUCCESS);

                $personRepository = $this->get('home_office_alfresco_api.person.repository');
                $personRepository->signoutUser();

                $this->get('security.context')->setToken(null);
                $this->get('request')->getSession()->invalidate();

                return $this->redirect($this->generateUrl('login'));

            }
        }

        return array(
            'resetPasswordForm' => $resetPasswordForm->createView(),
            'error' => $error,
            'breadcrumb' => [
                'Home' => 'homeoffice_cts_home_home',
                'Reset Password' => 'reset_password'
            ]
        );
    }

    /**
     * @Template
     * @Route("/forgottenPassword")
     * @Method({"GET", "POST"})
     */
    public function forgottenPasswordAction(Request $request)
    {

        $error = false;
        $success = false;

        $forgottenPasswordForm = $this->createForm(new ForgottenPasswordType());

        $forgottenPasswordForm->handleRequest($request);

        if ($forgottenPasswordForm->isValid()) {
            $forgottenPasswordConsumer = $this->get('home_office_alfresco_api.consumer.forgotten_password');
            $formData = $forgottenPasswordForm->getData();
            $forgottenPasswordConsumer->setQueryField('username', $formData['username']);
            $result = $forgottenPasswordConsumer->get();
            if ($result !== false) {
                return $this->render(
                    'HomeOfficeProcessManagerAuthenticatorBundle:Security:forgottenPasswordSuccess.html.twig',
                    [
                        'forgottenPasswordForm' => $forgottenPasswordForm->createView(),
                        'breadcrumb' => [
                            'Home' => 'homeoffice_cts_home_home',
                            'Forgotten Password' => 'forgotten_password'
                        ]
                    ]
                );
            } else {
                $error = true;
            }
        }

        return array(
            // last username entered by the user
            'errorBool' => $error,
            'forgottenPasswordForm' => $forgottenPasswordForm->createView(),
            'breadcrumb' => [
                'Home' => 'homeoffice_cts_home_home',
                'Forgotten Password' => 'forgotten_password'
            ]
        );
    }

}
