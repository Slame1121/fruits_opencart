<?php

class ModelCatalogTestimonial extends Model
{

    public function getRequests($data = array())
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "testimonials";

        if (isset($data['sort'])) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY pd.date_added";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalRequests() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "testimonials");

        return $query->row['total'];
    }

    public function deleteRequest($id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "testimonials WHERE id = '" . (int)$id . "'");
    }

    public function getReview($id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "testimonials WHERE id=".$id);

        return $query->row;
    }

    public function editReview($id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "testimonials SET status = " . $this->db->escape($data['status']) . ", name = '" . $this->db->escape($data['name']) . "', review = '" . $this->db->escape($data['text']) . "', date_added = NOW() WHERE id = " . (int)$id . "");
    }

    public function addReview($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "testimonials SET status = " . $this->db->escape($data['status']) . ", name = '" . $this->db->escape($data['name']) . "', review = '" . $this->db->escape($data['text']) ."', date_added = NOW()");
    }
}