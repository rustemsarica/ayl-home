<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        if (!$this->auth_check) {
            redirect('login');
        }
    }
    public function products()
    {
        $data['title'] = get_general_settings()->company_name;
        $data['description'] = get_general_settings()->company_name;
        $data['keywords'] = get_general_settings()->company_name;
        $data['products']=$this->product_model->get_products();

        $this->load->view('products',$data);
    }

    public function add_product()
    {
        $data['title'] = get_general_settings()->company_name;
        $data['description'] = get_general_settings()->company_name;
        $data['keywords'] = get_general_settings()->company_name;
        //$data["custom_fields"] = $this->product_model->get_fields();
        $this->load->view('products-add',$data);
    }

    public function add_product_post()
    {   
        $config['upload_path']          = 'uploads/products/';
        $config['allowed_types']        = 'webp|jpg|png|jpeg|';
        
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('img'))
        {
            $error = array('error' => $this->upload->display_errors());
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
        }
        $img='uploads/products/'.$this->upload->data('file_name');
        
        //add product
        if ($this->product_model->add_product()) {
            //last id
            $last_id = $this->db->insert_id();
            //add product images
            $this->product_model->add_product_images($last_id,$img);

            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
            redirect($this->agent->referrer());
        }
    }

    public function edit_product($id)
    {
        $data['title'] = get_general_settings()->company_name;
        $data['description'] = get_general_settings()->company_name;
        $data['keywords'] = get_general_settings()->company_name;
        $data['product']=$this->product_model->get_active_product($id);
        //$data["custom_fields"] = $this->product_model->get_fields();
        $this->load->view('products-update',$data);
    }

    public function edit_product_post()
    {   
        $id=$this->input->post('id', true);
        $id=clean_number($id);
        $config['upload_path']          = 'uploads/products/';
        $config['allowed_types']        = 'gif|jpg|png|jpeg|webp';
        
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('img'))
        {
            $error = array('error' => $this->upload->display_errors());
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
        }
        $img='uploads/products/'.$this->upload->data('file_name');
        
        //add product
        if ($this->product_model->edit_product($id)) {
            //last id
            
            if($this->upload->data('file_name')!=""){
                //add product images
            $this->product_model->add_product_images($id,$img);
            }
            $this->session->set_flashdata('success', "Güncelleme başarıyla gerçekleştirildi.");

            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', "Beklenmeyen bir hata oluştu.");
            redirect($this->agent->referrer());
        }
    }

    public function delete_product_post()
    {
        $this->product_model->delete_product();
    }
}