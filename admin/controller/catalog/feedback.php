<?php
class ControllerCatalogFeedback extends Controller {
    public function index() {
        $this->load->language('catalog/feedback');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/feedback');

        $this->getList();
    }

    public function delete() {
        $this->load->language('catalog/feedback');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/feedback');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $id) {
                $this->model_catalog_feedback->deleteFeedback($id);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('catalog/feedback', 'user_token=' . $this->session->data['user_token'] . $url, true));
        }

        $this->getList();
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'catalog/feedback')) {
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
            'href' => $this->url->link('catalog/feedback', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['delete'] = $this->url->link('catalog/feedback/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $filter_data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $results = $this->model_catalog_feedback->getFeedbacks($filter_data);

        foreach ($results as $result) {
            $data['feedbacks'][] = array(
                'id'                => $result['id'],
                'name'              => $result['name'],
                'email'              => $result['email'],
                'message'            => htmlspecialchars_decode($result['message']),
                'date_added'        => date('d.m.Y', strtotime($result['date_added'])),
                'view'              => $this->url->link('catalog/feedback/view', 'user_token=' . $this->session->data['user_token'].'&feedback_id='.$result['id']. $url, true)
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_email'] = $this->language->get('column_email');
        $data['column_message'] = $this->language->get('column_message');
        $data['column_date'] = $this->language->get('column_date');
        $data['column_action'] = $this->language->get('column_action');

        $data['button_view'] = $this->language->get('button_view');
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

        $data['sort_name'] = $this->url->link('catalog/feedback', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, true);
        $data['sort_email'] = $this->url->link('catalog/feedback', 'user_token=' . $this->session->data['user_token'] . '&sort=email' . $url, true);

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $feedback_total = $this->model_catalog_feedback->getTotalFeedbacks();

        $pagination = new Pagination();
        $pagination->total = $feedback_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('catalog/feedback', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($feedback_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($feedback_total - $this->config->get('config_limit_admin'))) ? $feedback_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $feedback_total, ceil($feedback_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/feedback_list', $data));
    }

    public function view() {
        $this->load->language('catalog/feedback');
        $this->document->setTitle($this->language->get('text_view'));
        $this->load->model('catalog/feedback');

        $feedback_info = $this->model_catalog_feedback->getFeedback($this->request->get['feedback_id']);

        $data['name'] = $feedback_info['name'];
        $data['email'] = $feedback_info['email'];
        $data['message'] = $feedback_info['message'];
        $data['date_added'] = $feedback_info['date_added'];

        $data['cancel'] = $this->url->link('catalog/feedback', 'user_token=' . $this->session->data['user_token'], true);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('catalog/feedback_view', $data));
    }
}