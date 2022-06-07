<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        if (!$this->auth_check) {
            redirect('login');
        }
        
    }
	
	public function index()
	{	
		$data['title'] = get_general_settings()->company_name;
        $data['description'] = get_general_settings()->company_name;
        $data['keywords'] = get_general_settings()->company_name;
        $data['products_count']=$this->product_model->get_products_count();
        $data['users_count']=$this->auth_model->get_users_count();
        if(is_admin()){
            $data['orders_count']=$this->order_model->get_orders_count();
            $total = $this->order_model->get_orders_total();
            if($total->total==""){
                $total->total=0;
            }
            $data['earnings']=price_formatted($total->total);
        }else{
            $user=user();
            $data['orders_count']=$this->order_model->get_orders_count_by_user_id($user->id);
            $data['earnings'] = price_formatted(get_user($user->id)->balance);
        }
		$this->load->view('index', $data);
	}

    public function orders()
	{	
		$data['title'] = get_general_settings()->company_name;
        $data['description'] = get_general_settings()->company_name;
        $data['keywords'] = get_general_settings()->company_name;
        if (is_admin()) {
            $data['orders']=$this->order_model->get_orders();
        }else{
            $user=user();
            $data['orders']=$this->order_model->get_orders_by_user_id($user->id);
        }
		$this->load->view('orders', $data);
	}

    public function shipped_orders()
	{	
		$data['title'] = get_general_settings()->company_name;
        $data['description'] = get_general_settings()->company_name;
        $data['keywords'] = get_general_settings()->company_name;
        if (is_admin()) {
            $data['orders']=$this->order_model->get_orders_shipped();
        }else{
            $user=user();
            $data['orders']=$this->order_model->get_orders_shipped_by_user_id($user->id);
        }
		$this->load->view('orders', $data);
	}

    public function completed_orders()
	{	
		$data['title'] = get_general_settings()->company_name;
        $data['description'] = get_general_settings()->company_name;
        $data['keywords'] = get_general_settings()->company_name;
        if (is_admin()) {
            $data['orders']=$this->order_model->get_orders_complated();
        }else{
            $user=user();
            $data['orders']=$this->order_model->get_orders_complated_by_user_id($user->id);
        }
		$this->load->view('orders', $data);
	}

    public function earnings()
    {
        $data['title'] = get_general_settings()->company_name;
        $data['description'] = get_general_settings()->company_name;
        $data['keywords'] = get_general_settings()->company_name;
        if (is_admin()) {
            $data['earnings']=$this->earnings_model->get_earnings();
        }else{
            $user=user();
            $data['earnings']=$this->earnings_model->get_earnings_by_user($user->id);
        }
        $this->load->view('earnings', $data);
    }

    public function payouts()
    {
        $data['title'] = get_general_settings()->company_name;
        $data['description'] = get_general_settings()->company_name;
        $data['keywords'] = get_general_settings()->company_name;
        if (is_admin()) {
            $data['payouts']=$this->earnings_model->get_payouts();
        }else{
            $user=user();
            $data['user']=$user;
            $data['payouts']=$this->earnings_model->get_payouts_by_user($user->id);
            $data['max']=$user->balance;
        }
        $this->load->view('payouts', $data);
    }
    public function payouts_request_post()
    {
        if($this->earnings_model->payouts_request_post()){
            $this->session->set_flashdata('success',"Ödeme Talebiniz Alınmıştır.");
        }else{
            $this->session->set_flashdata('error',"Beklenmeyen Bir Hata Oluştu.");
        }
        redirect($this->agent->referrer());
    }

    public function payout_approve()
    {
        $id=$this->input->post('id',TRUE);
        $amount=$this->input->post('amount',TRUE);
        $user_id=$this->input->post('user_id',TRUE);
        $this->earnings_model->payout_approve($id,$amount,$user_id);
        redirect($this->agent->referrer());
    }

    public function payout_cancel()
    {
        $id=$this->input->post('id',TRUE);
        $this->earnings_model->payout_cancel($id);
        redirect($this->agent->referrer());
    }

    public function profile()
    {
        if (is_admin()) {
            redirect(base_url());
        }
        $data['title'] = get_general_settings()->company_name;
        $data['description'] = get_general_settings()->company_name;
        $data['keywords'] = get_general_settings()->company_name;

        $data['user']=user();
        
        $this->load->view('profile', $data);
    }

    public function profile_post()
    {   
        $config['upload_path']          = 'uploads/users/';
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
        $img='uploads/users/'.$this->upload->data('file_name');
        $request=$this->settings_model->profile_post();
        $this->settings_model->add_profile_pic($img);
        if($request['status']==1){
            $this->session->set_flashdata('success', $request['message']);
        }else{
            $this->session->set_flashdata('error', $request['message']);
        }
        redirect($this->agent->referrer());
    }

    public function change_password()
    {
       
        $data['title'] = get_general_settings()->company_name;
        $data['description'] = get_general_settings()->company_name;
        $data['keywords'] = get_general_settings()->company_name;

        $data['user']=user();
        
        $this->load->view('change-password', $data);
    }

	public function error_404()
    {
        get_method();
        header("HTTP/1.0 404 Not Found");
        $data['title'] = "Error 404";
        $data['description'] = "Error 404";
        $data['keywords'] = "error,404";

        $this->load->view('partials/_header', $data);
        $this->load->view('errors/error_404');
        $this->load->view('partials/_footer');
    }
    public function get_city($id)
    {
        return $this->db->where('id',$id)->get('iller')->row();
    }
    public function get_towns()
    {   
        $il=$this->input->post('il',TRUE);
        $towns=$this->settings_model->get_towns($il);
        $status = 0;
        $content = '';
        if (!empty($towns)) {
            $status = 1;
            $content = '<option value="">' . "İlçe". '</option>';
            foreach ($towns as $item) {
                $content .= '<option value="' . $item->ilce . '">' . $item->ilce . '</option>';
            }
        }
        $data = array(
            'result' => $status,
            'content' => $content
        );
        echo json_encode($data);
    }
}
