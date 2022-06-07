<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        if (!$this->auth_check) {
            redirect('login');
        }else{
            if(!is_admin()){
                redirect(base_url());
            }
        }
    }

    public function users()
	{	
		$data['title'] = "Yönetim Paneli";
        $data['description'] = "Yönetim Paneli";
        $data['keywords'] = "Yönetim Paneli";
        
        $data['users']=$this->auth_model->get_users();
        
		$this->load->view('users', $data);
	}

    public function approve_users()
	{	
		$data['title'] = "Yönetim Paneli";
        $data['description'] = "Yönetim Paneli";
        $data['keywords'] = "Yönetim Paneli";
        $data['users']=$this->auth_model->get_users_no_approve();
		$this->load->view('users-waiting', $data);
	}

    public function delete_user()
    {
        $id = $this->input->post('id', true);
        $this->auth_model->delete_user($id);
        redirect($this->agent->referrer());
    }

    public function approve_user_post()
    {
        $id = $this->input->post('id', true);
        $this->auth_model->approve_user($id);
        redirect($this->agent->referrer());
    }

    public function order_shipping_key_post()
    {
        $id = $this->input->post('id', true);
        $shipping_key = $this->input->post('shipping_key', true);
        $shipping_comp = $this->input->post('shipping_comp', true);
        $this->order_model->order_shipping($id,$shipping_key,$shipping_comp);
        redirect($this->agent->referrer());
    }
    
    public function settings()
    {
        $data['title'] = "Yönetim Paneli";
        $data['description'] = "Yönetim Paneli";
        $data['keywords'] = "Yönetim Paneli";
		$this->load->view('settings', $data);
    }

    public function settings_post()
    {
        if($this->settings_model->update_general_settings()){
            $this->session->set_flashdata('success', "Güncelleme başarıyla gerçekleştirildi!");
        }else{
            $this->session->set_flashdata('error', "Birşeyler ters gitti!");
        }
        redirect($this->agent->referrer());
    }

}