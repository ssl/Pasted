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

        $shortURLenabled = $this->model('Settings')->getSetting('enable-shorturl') == '1';
        $this->view->renderCondition('shortURLenabled', $shortURLenabled);

        if($this->isPOST()) {
            try {
                $this->validateCsrfToken();

                $content = $this->getPostValue('content');
                $encrypted = $this->getPostValue('encrypt') == 'on';

                $short = $this->getPostValue('short') == 'on';
                $shorturl = $this->getPostValue('shorturl') ?? '';

                # Check if shorturl is valid and not taken
                if($short) {
                    try {
                        $this->model('Paste')->getPasteByToken($shorturl);
                        $shorturl = '';
                    } catch (Exception $e) {}
                    

                    if (preg_match('/[^A-Za-z0-9]/', $shorturl) || strlen($shorturl) > 50 || strlen($shorturl) < 1 || $shorturl == 'index') {
                        $shorturl = '';
                    }
                }

                $paste = $this->model('Paste')->paste($content, $encrypted, $this->session->userOrAnonymous());

                # Generate short URL if not provided
                if($short && empty(trim($shorturl))) {
                    $shorturl = short($paste['id']);
                }
                
                # Update token with shorturl
                if($short && $shortURLenabled) {
                    $this->model('Paste')->updateToken($paste['token'], $shorturl);
                }

                $token = $short && $shortURLenabled ? $shorturl : $paste['token'];
                $url = !$short ? "paste/view/$token" : "v/$token";
                
                header('Location: /' . $url);

            } catch (Exception $e) {
                $this->view->renderMessage($e->getMessage());
            }
        }
        return $this->view->showContent();
    }
}