<?php

class ModelCatalogBlog extends Model
{

    public function getPosts($data = array())
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "blog ";

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

    public function getTotalPosts() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "testimonials");

        return $query->row['total'];
    }

    public function deletePost($id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "blog WHERE post_id = '" . (int)$id . "'");
    }

    public function getPost($id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog WHERE post_id=".$id);

        return $query->row;
    }

    public function editPost($id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "blog SET status = " . $this->db->escape($data['status']) . ", title = '" . $this->db->escape($data['title']) . "', description = '" . $this->db->escape($data['description']) . "',image =   '" . $this->db->escape($data['image']) ."', date_added = NOW() WHERE post_id = " . (int)$id . "");

		// SEO URL
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE query = 'post_id=" . (int)$id . "'");

		if (isset($data['post_seo_url'])) {
			foreach ($data['post_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!empty($keyword)) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'post_id=" . (int)$id . "', keyword = '" . $this->db->escape($keyword) . "'");
					}
				}
			}
		}
    }

	public function getBlogSeoUrls($category_id) {
		$category_seo_url_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'post_id=" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$category_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $category_seo_url_data;
	}

    public function addPost($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "blog SET status = " . $this->db->escape($data['status']) . ", title = '" . $this->db->escape($data['title']) . "', description = '" . $this->db->escape($data['description']) ."',image =   '" . $this->db->escape($data['image']) ."',date_added = NOW()");
		$post_id = $this->db->getLastId();
		if (isset($data['post_seo_url'])) {
			foreach ($data['post_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!empty($keyword)) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'post_id=" . (int)$post_id . "', keyword = '" . $this->db->escape($keyword) . "'");
					}
				}
			}
		}
    }
}