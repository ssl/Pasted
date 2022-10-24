<?php

class Paste_model extends Model {

    /**
     * @var string
     */
    public $table = 'paste';

    /**
     * Create new paste
     *
     * @param string $content
     * @param bool $encrypted
     * @param string $username
     * @return Exception|string
     */
    public function paste($content, $encrypted, $username) {
        $database = Database::openConnection();

        $token = bin2hex(random_bytes(16));

        $database->prepare('INSERT INTO `paste` (`token`, `encrypted`, `content`, `username`) VALUES (:token, :encrypted, :content, :username);');
        $database->bindValue(':token', $token);
        $database->bindValue(':encrypted', $encrypted);
        $database->bindValue(':content', $content);
        $database->bindValue(':username', $username);
        
        if(!$database->execute()) {
            throw new Exception("Something unexpected went wrong");
        }

        return $token;
    }

    /**
     * Delete paste by token
     *
     * @param string $token
     * @return Exception|bool
     */
    public function deleteByToken($token) {
        $database = Database::openConnection();

        $database->prepare('DELETE FROM `paste` WHERE token = :token');
        $database->bindValue(':token', $token);
        
        if(!$database->execute()) {
            throw new Exception("Something unexpected went wrong");
        }

        return true;
    }

    /**
     * Get paste by token
     *
     * @param string $token
     * @return Exception|array
     */
    public function getPasteByToken($token) {
        $database = Database::openConnection();

        $database->prepare('SELECT * FROM `paste` WHERE token = :token');
        $database->bindValue(':token', $token);
        
        if(!$database->execute()) {
            throw new Exception("Something unexpected went wrong");
        }

        if ($database->countRows() == 0){
            throw new Exception("Paste not found");
        }

        $paste = $database->fetch();

        return $paste;
    }

    /**
     * Delete paste by username
     *
     * @param string $username
     * @return Exception|bool
     */
    public function deleteByUsername($username) {
        $database = Database::openConnection();

        $database->prepare('DELETE FROM `paste` WHERE username = :username');
        $database->bindValue(':username', $username);
        
        if(!$database->execute()) {
            throw new Exception("Something unexpected went wrong");
        }

        return true;
    }

    /**
     * Move all pastes to Anonymous account by username
     *
     * @param string $username
     * @return Exception|bool
     */
    public function moveByUsername($username) {
        $database = Database::openConnection();

        $database->prepare('UPDATE `paste` SET username = "Anonymous" WHERE username = :username');
        $database->bindValue(':username', $username);
        
        if(!$database->execute()) {
            throw new Exception("Something unexpected went wrong");
        }

        return true;
    }

    /**
     * Returns all pastes by username
     *
     * @param string $username
     * @return array
     */
    public function getPastesByUsername($username) {
        $database = Database::openConnection();
        $database->getByUsername($this->table, $username);

        $pastes = $database->fetchAll();

        return $pastes;
    }

    /**
     * Returns all pastes
     *
     * @return array
     */
    public function getAllPastes() {
        $database = Database::openConnection();
        $database->getAll($this->table);

        $pastes = $database->fetchAll();

        return $pastes;
    }
}