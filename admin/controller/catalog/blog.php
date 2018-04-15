<?php
class ControllerCatalogBlog extends Controller {
    public function index() {
        $this->load->language('catalog/blog');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/blog');

        $this->getList();
    }

    public function delete() {
        $this->load->language('catalog/blog');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/blog');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $id) {
                $this->model_catalog_blog->deletePost($id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('catalog/blog', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getList();
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'catalog/blog')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function getList() {
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'date_added';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/blog', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['delete'] = $this->url->link('catalog/blog/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['add'] = $this->url->link('catalog/blog/add', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $filter_data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $results = $this->model_catalog_blog->getPosts($filter_data);

        foreach ($results as $result) {
            $data['contacts'][] = array(
                'id'                => $result['post_id'],
                'title'              => $result['title'],
                'review'            => htmlspecialchars_decode($result['description']),
                'status'            => $result['status'],
                'date_added'        => date('d.m.Y', strtotime($result['date_added'])),
                'edit'              => $this->url->link('catalog/blog/edit', 'user_token=' . $this->session->data['user_token'].'&post_id='.$result['post_id']. $url, true)
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_review'] = $this->language->get('column_review');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_date'] = $this->language->get('column_date');
        $data['column_action'] = $this->language->get('column_action');

        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('catalog/blog', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, true);
        $data['sort_email'] = $this->url->link('catalog/blog', 'user_token=' . $this->session->data['user_token'] . '&sort=email' . $url, true);
        $data['sort_status'] = $this->url->link('catalog/blog', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url, true);

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $contact_total = $this->model_catalog_blog->getTotalPosts();

        $pagination = new Pagination();
        $pagination->total = $contact_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('catalog/blog', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($contact_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($contact_total - $this->config->get('config_limit_admin'))) ? $contact_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $contact_total, ceil($contact_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/blog_list', $data));
    }

    public function edit() {
        $this->load->language('catalog/blog');

        $this->document->setTitle($this->language->get('text_edit'));

        $this->load->model('catalog/blog');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->model_catalog_blog->editPost($this->request->get['post_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('catalog/blog', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->getForm();
    }

    public function add() {
        $this->load->language('catalog/blog');

        $this->document->setTitle($this->language->get('text_add'));

        $this->load->model('catalog/blog');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->model_catalog_blog->addPost($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('catalog/blog', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->getForm();
    }

    protected function getForm() {
        $data['text_form'] = !isset($this->request->get['post_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        $data['button_save'] = $this->language->get('button_save');
        $data['column_status'] = $this->language->get('column_status');

        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/blog', 'user_token=' . $this->session->data['user_token'], true)
        );

        if(!isset($this->request->get['post_id'])){
            $data['action'] = $this->url->link('catalog/blog/add', 'user_token=' . $this->session->data['user_token'], true);
        }else{
            $data['action'] = $this->url->link('catalog/blog/edit', 'user_token=' . $this->session->data['user_token'] . '&post_id=' . $this->request->get['post_id'], true);
        }


        $data['cancel'] = $this->url->link('catalog/blog', 'user_token=' . $this->session->data['user_token'], true);

        if (isset($this->request->get['post_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $review_info = $this->model_catalog_blog->getPost($this->request->get['post_id']);
        }

        $data['user_token'] = $this->session->data['user_token'];

        $data["column_name"] = $this->language->get('column_name');
        $data["column_review"] = $this->language->get('column_review');

        if (isset($this->request->post['title'])) {
            $data['title'] = $this->request->post['title'];
        } elseif (!empty($review_info)) {
            $data['title'] = $review_info['title'];
        } else {
            $data['title'] = '';
        }

        if (isset($this->request->post['description'])) {
            $data['description'] = $this->request->post['description'];
        } elseif (!empty($review_info)) {
            $data['description'] = $review_info['description'];
        } else {
            $data['description'] = '';
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($review_info)) {
            $data['status'] = $review_info['status'];
        } else {
            $data['status'] = 0;
        }

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($review_info)) {
			$data['image'] = $review_info['image'];
		} else {
			$data['image'] = '';
		}
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($review_info) && is_file(DIR_IMAGE . $review_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($review_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (isset($this->request->post['post_seo_url'])) {
			$data['post_seo_url'] = $this->request->post['post_seo_url'];
		} elseif (isset($this->request->get['post_id'])) {
			$data['post_seo_url'] = $this->model_catalog_blog->getBlogSeoUrls($this->request->get['post_id']);
		} else {
			$data['post_seo_url'] = array();
		}


		$this->load->model('setting/store');
		$data['stores'] = array();

		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		);

		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = array(
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			);
		}
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/blog_form', $data));
    }
}