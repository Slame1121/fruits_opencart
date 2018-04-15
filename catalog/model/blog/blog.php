<?php
class ModelBlogBlog extends Model {

	public function getPosts($data = array())
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "blog  ";

		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY date_added";
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


	public function getPost($id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog WHERE post_id=".$id);

		return $query->row;
	}
}