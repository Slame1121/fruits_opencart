<?php
class ControllerCommonReviewsForm extends Controller {
    public function index() {
        $this->load->model('common/reviews_form');

        $reviews = $this->model_common_reviews_form->getRequests();

        $contacts = [];
        if($reviews){
            foreach ( $reviews as $review ) {
                $contacts[] = [
                    'name' => $review['name'],
                    'review' => htmlspecialchars_decode($review['review']),
                    'date_added' => $review['date_added']
                ];
            }
        }

        $data['contacts'] = $contacts;

        return $this->load->view('common/reviews_form', $data);
    }

    public function addReview(){
        $this->load->model('common/reviews_form');

        $name = $_POST['name'];
        $comment = $_POST['comment'];

        $this->model_common_reviews_form->addReview($name, $comment);
    }
}