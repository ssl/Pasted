<?php

class Paste extends Controller {

    /**
     * Index page. This is just a redirect
     *
     * @return void
     */
    public function index() {
        header('Location: /');
    }

    /**
     * Page to view content of a paste
     *
     * @param string $token
     * @return string|void
     */
    public function view($token) {
        $this->view->setTitle('Paste');
        $this->view->renderTemplate('paste/view');

        try {

            $paste = $this->model('Paste')->getPasteByToken($token);
            $this->view->renderCondition('isEncrypted', $paste['encrypted'] == 1);
            $this->view->renderData('content', $paste['content']);
            $this->view->renderData('username', $paste['username']);

            return $this->view->showContent();

        } catch (Exception $e) {
            header('Location: /');
        }
    }

    /**
     * Paste delete page
     *
     * @param string $token
     * @return string|void
     */
    public function delete($token) {
        $this->view->setTitle('Delete paste');
        $this->view->renderTemplate('paste/delete');

        try {
            $paste = $this->model('Paste')->getPasteByToken($token);

            // Check if current user is owner or admin
            if($paste['username'] != $this->session->data('username') &&
               $this->session->data('rank') != 7) {
                throw new Exception("No permissions to delete this paste");
            }

            if($this->isPOST()) {
                $this->validateCsrfToken();

                $this->model('Paste')->deleteByToken($token);
                header('Location: /account');
            } else {
                $this->view->renderData('token', $paste['token']);
                return $this->view->showContent();
            }
        } catch (Exception $e) {
            return $this->view->renderErrorPage($e->getMessage());
        }
    }
}