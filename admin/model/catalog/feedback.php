<?php

class ModelCatalogFeedback extends Model
{

    public function getFeedbacks($data = array())
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "feedbacks";

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

    public function getTotalFeedbacks() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "feedbacks");

        return $query->row['total'];
    }

    public function deleteFeedback($id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "feedbacks WHERE id = '" . (int)$id . "'");
    }

    public function getFeedback($id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "feedbacks WHERE id=".$id);

        return $query->row;
    }
}