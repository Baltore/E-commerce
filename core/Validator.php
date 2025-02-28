<?php
class Validator {
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function validatePassword($password) {
        return strlen($password) >= 6; // Exige un mot de passe d'au moins 6 caractères
    }

    public static function validateRequired($fields) {
        foreach ($fields as $field) {
            if (empty(trim($field))) {
                return false;
            }
        }
        return true;
    }
}
?>
