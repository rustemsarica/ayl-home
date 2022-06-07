<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        if (!$this->auth_check) {
            redirect('login');
        }
    }
    
    public function create_order_post()
    {
        $user=user();
        if($this->order_model->create_order()){
            $this->session->set_flashdata('success', $return['message']);
            redirect('orders');
        }
        $this->session->set_flashdata('error', "Sipariş oluşturulamadı.");
        redirect($this->agent->referrer());
    }

    public function delete_order()
    {
        $this->order_model->delete_order();
        redirect($this->agent->referrer());
    }

    public function order_approve_post()
    {
        $this->order_model->is_approved_order();
        redirect($this->agent->referrer());
    }
    public function date_of_orders()
    {
        $start=$this->input->post('start',TRUE);
        $daycount=$this->input->post('daycount',TRUE);
        echo json_encode($data=$this->order_model->date_of_orders($start,$daycount));
        
    }
}