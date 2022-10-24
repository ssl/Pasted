<?php

class Admin extends Controller {

    /**
     * Admin index page. This holds all pastes of all users.
     *
     * @return string
     */
    public function index() {
        $this->isAdminOrExit();

        $this->view->setTitle('Admin');
        $this->view->renderTemplate('admin/index');

        $pastes = $this->model('Paste')->getAllPastes();
        foreach($pastes as $key => $value) {
            // Add the correct icon param to all items based on if encrypted
            $pastes[$key]['icon'] = $pastes[$key]['encrypted'] ? 'check' : 'close';
        }
        $this->view->renderDataset('paste', $pastes);
    
        return $this->view->showContent();
    }

    /**
     * Accounts page. This holds all accounts.
     *
     * @return string
     */
    public function accounts() {
        $this->isAdminOrExit();

        $this->view->setTitle('Admin');
        $this->view->renderTemplate('admin/accounts');

        $accounts = $this->model('Account')->getAllAccounts();
        $this->view->renderDataset('account', $accounts);
        return $this->view->showContent();
    }

    /**
     * Delete account page
     *
     * @param string $id
     * @return string
     */
    public function delete($id) {
        $this->isAdminOrExit();

        $this->view->setTitle('Admin');
        $this->view->renderTemplate('admin/delete');

        try {
            $accountModel = $this->model('Account');
            $account = $accountModel->getById($id);
            $this->view->renderData('username', $account['username']);

            // Makes sure you can't delete Anonymous or your own account
            if($account['username'] == 'Anonymous') {
                throw new Exception("Can't delete the Anonymous account");
            }

            if($account['username'] == $this->session->data('username')) {
                throw new Exception("Can't delete your own account");
            }

            if($this->isPOST()) {
                $this->validateCsrfToken();

                // Either delete all pastes from a user or move them all to the Anonymous user
                $pastes = $this->getPostValue('pastes');
                if($pastes == 'delete') {
                    $this->model('Paste')->deleteByUsername($account['username']);
                } else {
                    $this->model('Paste')->moveByUsername($account['username']);
                }

                $accountModel->deleteById($id);
                header('Location: /admin/accounts');
            }

            return $this->view->showContent();
        } catch(Exception $e) {
            return $this->view->renderErrorPage($e->getMessage());
        }
    }

    /**
     * Admin settings page
     *
     * @return string
     */
    public function settings() {
        $this->isAdminOrExit();
        $this->view->setTitle('Admin');
        $this->view->renderTemplate('admin/settings');

        try {
            if($this->isPOST()) {
                $this->validateCsrfToken();

                $enabled = $this->getPostValue('enable-registration') == 'on';
                $this->model('Settings')->setSetting('enable-registration', $enabled);
            }
            $setting = $this->model('Settings')->getSetting('enable-registration') == '1' ? 'checked' : '';
            $this->view->renderData('registration-check', $setting);
        } catch(Exception $e) {
            return $this->view->renderErrorPage($e->getMessage());
        }

        return $this->view->showContent();
    }
}
