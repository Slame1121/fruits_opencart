<?php

class ModelCommonContactForm extends Model
{
    public function addFeedback($name, $email, $message) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "feedbacks SET name = '" . $this->db->escape($name) . "', email = '" . $this->db->escape($email) ."', message = '" . $this->db->escape($message) ."', date_added = NOW()");
    }
}