<?php

class Account extends Controller {

    /**
     * Account index. This holds all pastes by the user
     *
     * @return string
     */
    public function index() {
        $this->isLoggedInOrExit();

        $this->view->setTitle('Account');
        $this->view->renderTemplate('account/index');

        $this->view->renderCondition('userIsAdmin', $this->isAdmin());

        $pastes = $this->model('Paste')->getPastesByUsername($this->session->data('username'));
        $i = 1;
        foreach($pastes as $key => $value) {
            $pastes[$key]['id'] = $i++;
            $pastes[$key]['icon'] = $pastes[$key]['encrypted'] ? 'check' : 'close';
        }
        $this->view->renderDataset('paste', $pastes);
    
        return $this->view->showContent();
    }

    /**
     * Login page
     *
     * @return string
     */
    public function login() {
        $this->isLoggedOutOrExit();
        $this->view->setTitle('Login');
        $this->view->renderTemplate('account/login');

        if ($this->isPOST()) {
            try {
                $this->validateCsrfToken();

                $username = $this->getPostValue('username');
                $password = $this->getPostValue('password');

                $account = $this->model('Account')->login($username, $password);
                $this->session->createSession($account);
                header('Location: /account/index');
                
            } catch (Exception $e) {
                $this->view->renderMessage($e->getMessage());
            }
        }

        return $this->view->showContent();
    }

    /**
     * Sign up page
     *
     * @return string
     */
    public function signup() {
        $this->isLoggedOutOrExit();
        $this->view->setTitle('Sign up');
        $this->view->renderTemplate('account/signup');

        if ($this->isPOST()) {
            try {
                $this->validateCsrfToken();

                // Check if registration is enabled at the time
                $enabled = $this->model('Settings')->getSetting('enable-registration');
                if($enabled != '1') {
                    throw new Exception("Registration is currently closed");
                }

                $username = $this->getPostValue('username');
                $password = $this->getPostValue('password');
                $password2 = $this->getPostValue('password2');
                $captcha = $this->getPostValue('captcha');

                if($captcha != '4') { // just for demo purpose, this does not prevent any bots rly
                    throw new Exception("Invalid captcha token");
                }

                if($username == 'Anonymous') {
                    throw new Exception("Reserved username");
                }

                if($password !== $password2) {
                    throw new Exception("Passwords do not match");
                }

                $account = $this->model('Account')->signUp($username, $password);
                $this->session->createSession($account);
                header('Location: /account/index');
                
            } catch (Exception $e) {
                $this->view->renderMessage($e->getMessage());
            }
        }

        return $this->view->showContent();
    }

    /**
     * Settings page
     *
     * @return string
     */
    public function settings() {
        $this->isLoggedInOrExit();
        $this->view->setTitle('Account');
        $this->view->renderTemplate('account/settings');
        $this->view->renderCondition('userIsAdmin', $this->isAdmin());

        try {
            if($this->isPOST()) {
                $this->validateCsrfToken();

                $currentPassword = $this->getPostValue('currentPassword');
                $newPassword = $this->getPostValue('newPassword');
                $newPassword2 = $this->getPostValue('newPassword2');

                $account = $this->model('Account')->login($this->session->data('username'), $currentPassword);

                if($newPassword !== $newPassword2) {
                    throw new Exception("New passwords do not match");
                }

                $this->model('Account')->updatePassword($account['id'], $newPassword);
                header('Location: /account/login');
            }
        } catch (Exception $e) {
            $this->view->renderMessage($e->getMessage());
        }

        return $this->view->showContent();
    }

    /**
     * Logout page
     *
     * @return string
     */
    public function logout() {
        $this->isLoggedInOrExit();
        $this->view->setTitle('Logout');
        $this->view->renderTemplate('account/logout');
        $this->view->renderCondition('userIsAdmin', $this->isAdmin());

        try {
            if ($this->isPOST()) {
                $this->validateCsrfToken();

                $this->session->deleteSession();
                header('Location: /account/login');
            }
        } catch (Exception $e) {
            $this->view->renderMessage($e->getMessage());
        }

        return $this->view->showContent();
    }
}