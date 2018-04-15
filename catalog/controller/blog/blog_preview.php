<?php
class ControllerBlogBlogPreview extends Controller {
	public function index() {
		// Analytics
		$this->load->model('blog/blog');

		$data = [];
		$filter_data = array(
			'start' => 1,
			'limit' => 20
		);
		$this->load->model('tool/image');
		$posts = $this->model_blog_blog->getPosts($filter_data);

		$data['posts'] = [];
		foreach($posts as $post){
			$data['posts'][] = [
				'title' => $post['title'],
				'description' => html_entity_decode($post['description']),
				'image' => $this->model_tool_image->resize($post['image'], 205, 295),
				'link' => $this->url->link('blog/blog', 'post_id=' .$post['post_id'])
			];
		}

		$data['blog_list'] = $this->url->link('blog/blog', 'post_id=0');
		return $this->load->view('blog/preview', $data);
	}

}
