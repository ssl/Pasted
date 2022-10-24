<?php

class Settings_model extends Model {

    /**
     * @var string
     */
    public $table = 'settings';

    /**
     * Get an setting value
     *
     * @param string $param
     * @return Exception|string
     */
    public function getSetting($param) {
        $database = Database::openConnection();

        $database->prepare('SELECT * FROM `settings` WHERE param = :param');
        $database->bindValue(':param', $param);
        
        if(!$database->execute()) {
            throw new Exception("Something unexpected went wrong");
        }

        if ($database->countRows() == 0){
            throw new Exception("Setting not found");
        }

        $setting = $database->fetch();

        return $setting['value'];
    }

    /**
     * Update a setting value
     *
     * @param string $param
     * @param string $value
     * @return Exception|bool
     */
    public function setSetting($param, $value) {
        $database = Database::openConnection();

        $database->prepare('UPDATE `settings` SET value = :value WHERE param = :param');
        $database->bindValue(':param', $param);
        $database->bindValue(':value', $value);
        
        if(!$database->execute()) {
            throw new Exception("Something unexpected went wrong");
        }

        return true;
    }
}