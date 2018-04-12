<?php

class ModelCommonReviewsForm extends Model
{
    public function getRequests()
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "testimonials WHERE status = 1";

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function addReview($name, $comment) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "testimonials SET name = '" . $this->db->escape($name) . "', review = '" . $this->db->escape($comment) ."', date_added = NOW()");
    }
}