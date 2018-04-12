<?php
class ControllerCatalogTestimonial extends Controller {
    public function index() {
        $this->load->language('catalog/testimonial');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/testimonial');

        $this->getList();
    }

    public function delete() {
        $this->load->language('catalog/testimonial');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/testimonial');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $id) {
                $this->model_catalog_testimonial->deleteRequest($id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('catalog/testimonial', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getList();
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'catalog/testimonial')) {
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
            'href' => $this->url->link('catalog/testimonial', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['delete'] = $this->url->link('catalog/testimonial/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['add'] = $this->url->link('catalog/testimonial/add', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $filter_data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $results = $this->model_catalog_testimonial->getRequests($filter_data);

        foreach ($results as $result) {
            $data['contacts'][] = array(
                'id'                => $result['id'],
                'name'              => $result['name'],
                'review'            => htmlspecialchars_decode($result['review']),
                'status'            => $result['status'],
                'date_added'        => date('d.m.Y', strtotime($result['date_added'])),
                'edit'              => $this->url->link('catalog/testimonial/edit', 'user_token=' . $this->session->data['user_token'].'&review_id='.$result['id']. $url, true)
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

        $data['sort_name'] = $this->url->link('catalog/testimonial', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, true);
        $data['sort_email'] = $this->url->link('catalog/testimonial', 'user_token=' . $this->session->data['user_token'] . '&sort=email' . $url, true);
        $data['sort_status'] = $this->url->link('catalog/testimonial', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url, true);

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $contact_total = $this->model_catalog_testimonial->getTotalRequests();

        $pagination = new Pagination();
        $pagination->total = $contact_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('catalog/testimonial', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($contact_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($contact_total - $this->config->get('config_limit_admin'))) ? $contact_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $contact_total, ceil($contact_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/testimonial_list', $data));
    }

    public function edit() {
        $this->load->language('catalog/testimonial');

        $this->document->setTitle($this->language->get('text_edit'));

        $this->load->model('catalog/testimonial');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->model_catalog_testimonial->editReview($this->request->get['review_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('catalog/testimonial', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->getForm();
    }

    public function add() {
        $this->load->language('catalog/testimonial');

        $this->document->setTitle($this->language->get('text_add'));

        $this->load->model('catalog/testimonial');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->model_catalog_testimonial->addReview($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('catalog/testimonial', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->getForm();
    }

    protected function getForm() {
        $data['text_form'] = !isset($this->request->get['review_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
            'href' => $this->url->link('catalog/testimonial', 'user_token=' . $this->session->data['user_token'], true)
        );

        if(!isset($this->request->get['review_id'])){
            $data['action'] = $this->url->link('catalog/testimonial/add', 'user_token=' . $this->session->data['user_token'], true);
        }else{
            $data['action'] = $this->url->link('catalog/testimonial/edit', 'user_token=' . $this->session->data['user_token'] . '&review_id=' . $this->request->get['review_id'], true);
        }


        $data['cancel'] = $this->url->link('catalog/testimonial', 'user_token=' . $this->session->data['user_token'], true);

        if (isset($this->request->get['review_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $review_info = $this->model_catalog_testimonial->getReview($this->request->get['review_id']);
        }

        $data['user_token'] = $this->session->data['user_token'];

        $data["column_name"] = $this->language->get('column_name');
        $data["column_review"] = $this->language->get('column_review');

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($review_info)) {
            $data['name'] = $review_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['text'])) {
            $data['text'] = $this->request->post['text'];
        } elseif (!empty($review_info)) {
            $data['text'] = $review_info['review'];
        } else {
            $data['text'] = '';
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($review_info)) {
            $data['status'] = $review_info['status'];
        } else {
            $data['status'] = 0;
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/testimonial_form', $data));
    }
}