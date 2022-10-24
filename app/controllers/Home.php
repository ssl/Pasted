<?php

class Home extends Controller {

    /**
     * Index of Pasted. This holds the page to create new pastes.
     *
     * @return string
     */
    public function index() {
        $this->validateSession();
        $this->view->setTitle('Index');
        $this->view->renderTemplate('home/index');

        if($this->isPOST()) {
            try {
                $this->validateCsrfToken();

                $content = $this->getPostValue('content');
                $encrypted = $this->getPostValue('encrypt') == 'on';

                $token = $this->model('Paste')->paste($content, $encrypted, $this->session->userOrAnonymous());
                
                header('Location: /paste/view/' . $token);

            } catch (Exception $e) {
                $this->view->renderMessage($e->getMessage());
            }
        }
        return $this->view->showContent();
    }
}