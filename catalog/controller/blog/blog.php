<?php
class ControllerBlogBlog extends Controller {
	public function index() {
		// Analytics
		$this->load->model('blog/blog');


		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');
		$this->load->model('tool/image');

		$post_id = isset($this->request->get['post_id']) ? (int)$this->request->get['post_id'] : 0;

		$post_info = $this->model_blog_blog->getPost($post_id);

		if($post_info){
			$data['title'] = ($post_info['title']);
			$data['image'] =  $this->model_tool_image->resize($post_info['image'], 205, 295);
			$data['description'] = html_entity_decode($post_info['description']);
			return $this->response->setOutput($this->load->view('blog/blog', $data));
		}else{

			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}

			$filter_data = array(
				'start' => ($page - 1) * 5,
				'limit' => 5,
				'order' => 'DESC'
			);

			$posts = $this->model_blog_blog->getPosts($filter_data);

			$data['posts'] = [];
			foreach($posts as $post){
				$data['posts'][] = [
					'title' => $post['title'],
					'description' => html_entity_decode($post['description']),
					'image' => $this->model_tool_image->resize($post['image'], 600, 340),
					'link' => $this->url->link('blog/blog', 'post_id=' .$post['post_id'])
				];
			}
			return $this->response->setOutput($this->load->view('blog/blog_list', $data));
		}

	}

}
