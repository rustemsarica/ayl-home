<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_controller extends CI_Controller
{
    
    
    /**
     * Login
     */
    public function login()
    {
        if ($this->auth_check) {
            redirect(base_url());
        }
        $data['title'] = "login";
        $data['description'] = "login" . " - ";
        $data['keywords'] = "login" . ', ' ;
        $this->load->view('login', $data);
    }

    /**
     * Login Post
     */
    public function login_post()
    {	
        //check auth
        if ($this->auth_check) {
            redirect(base_url());
        }
        //validate inputs
        $this->form_validation->set_rules('username', "username", 'required|max_length[200]');
        $this->form_validation->set_rules('password', "password", 'required|max_length[255]');
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('errors', validation_errors());
            $this->session->set_flashdata('form_data', $this->auth_model->input_values());
        } else {
            $request=$this->auth_model->login();
            
            if ($request['status']==1) {
                redirect(base_url());
            } else {
                //error
                $this->session->set_flashdata('error', $request['message']);
                redirect($this->agent->referrer());
            }
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        if (!$this->auth_check) {
            redirect(base_url());
        }
        $this->auth_model->logout();
        redirect($this->agent->referrer());
    }

    public function register()
    {
        if ($this->auth_check) {
            redirect(base_url());
        }
        $data['title'] = "register";
        $data['description'] = "register" . " - ";
        $data['keywords'] = "register" . ', ' ;
        $this->load->view('register', $data);
    }

    public function register_post()
    {
        //check if logged in
        if ($this->auth_check) {
            redirect(base_url());
        }
        //validate inputs
        $this->form_validation->set_rules('username', "kullanıcı adı", 'required|min_length[4]|max_length[100]');
        $this->form_validation->set_rules('email', "email adresi", 'required|max_length[200]');
        $this->form_validation->set_rules('password', "şifre", 'required|min_length[4]|max_length[255]');
        $this->form_validation->set_rules('confirm_password', "şifre onayı", 'required|matches[password]');

        if ($this->form_validation->run() === false) {
            $this->session->set_flashdata('errors', validation_errors());
            $this->session->set_flashdata('form_data', $this->auth_model->input_values());
            redirect($this->agent->referrer());
        } else {
            $email = $this->input->post('email', true);
            $username = $this->input->post('username', true);

            //is email unique
            if (!$this->auth_model->is_unique_email($email)) {
                $this->session->set_flashdata('form_data', $this->auth_model->input_values());
                $this->session->set_flashdata('error', "Bu E-posta zaten kullanılmaktadır.");
                redirect($this->agent->referrer());
            }
            //is username unique
            if (!$this->auth_model->is_unique_username($username)) {
                $this->session->set_flashdata('form_data', $this->auth_model->input_values());
                $this->session->set_flashdata('error', "Kullanıcı adı zaten kullanılmaktadır.");
                redirect($this->agent->referrer());
            }
            //register
            $user_id = $this->auth_model->register();
            if ($user_id) {
                $user = get_user($user_id);
                $this->session->set_flashdata('success', "<h4>".$user->username."</h4> kullanıcı adıyla kaydoldunuz. Yöneticiniz onayladıktan sonra sisteme erişebilirsiniz.");
            }else {
                //error
                $this->session->set_flashdata('form_data', $this->auth_model->input_values());
                $this->session->set_flashdata('error', "Hata oluştu");
                redirect(generate_url("register"));
            }
            redirect(base_url());
            /*
            if ($user_id) {
                $user = get_user($user_id);
                if (!empty($user)) {
                    //update slug
                    $this->auth_model->update_slug($user->id);
                    if ($this->general_settings->email_verification != 1) {
                        $this->auth_model->login_direct($user);
                        $this->session->set_flashdata('success', trans("msg_register_success"));
                        redirect(generate_url("settings", "update_profile"));
                    }
                }
                redirect(generate_url("register"));
            } else {
                //error
                $this->session->set_flashdata('form_data', $this->auth_model->input_values());
                $this->session->set_flashdata('error', trans("msg_error"));
                redirect(generate_url("register"));
            } */
        }
    }
/**
     * Forgot Password
     */
    public function forgot_password()
    {
        //check if logged in
        if ($this->auth_check) {
            redirect(base_url());
        }

        $data['title'] = "Şifre Sıfırlama";
        $data['description'] = "Şifre Sıfırlama" . " - " . $this->app_name;
        $data['keywords'] = "Şifre Sıfırlama" . "," . $this->app_name;

        $this->load->view('partials/_header', $data);
        $this->load->view('auth/forgot_password');
        $this->load->view('partials/_footer');
    }

    /**
     * Forgot Password Post
     */
    public function forgot_password_post()
    {
        //check auth
        if ($this->auth_check) {
            redirect(base_url());
        }

        $email = $this->input->post('email', true);
        //get user
        $user = $this->auth_model->get_user_by_email($email);

        //if user not exists
        if (empty($user)) {
            $this->session->set_flashdata('error', html_escape("Girdiğiniz e-posta adresi kayıtlı değil!"));
            redirect($this->agent->referrer());
        } else {
            $this->load->model("email_model");
            $this->email_model->send_email_reset_password($user->id);
            $this->session->set_flashdata('success', "Şifre Yenileme Bağlantınız Gönderilmiştir.");
            redirect($this->agent->referrer());
        }
    }

    /**
     * Reset Password
     */
    public function reset_password()
    {
        //check if logged in
        if ($this->auth_check) {
            redirect(base_url());
        }

        $data['title'] = "Şifre Sıfırlama";
        $data['description'] = "Şifre Sıfırlama" . " - " . $this->app_name;
        $data['keywords'] = "Şifre Sıfırlama" . "," . $this->app_name;
        $token = $this->input->get('token', true);
        //get user
        $data["user"] = $this->auth_model->get_user_by_token($token);
        $data["success"] = $this->session->flashdata('success');

        if (empty($data["user"]) && empty($data["success"])) {
            redirect(base_url());
        }

        
        $this->load->view('auth/reset_password');
        
    }

    /**
     * Reset Password Post
     */
    public function reset_password_post()
    {
        $success = $this->input->post('success', true);
        if ($success == 1) {
            redirect(base_url());
        }

        $this->form_validation->set_rules('password', "Yeni Şifre", 'required|min_length[4]|max_length[255]');
        $this->form_validation->set_rules('password_confirm', "Şifre Tekrar", 'required|matches[password]');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('errors', validation_errors());
            redirect($this->agent->referrer());
        } else {
            $token = $this->input->post('token', true);
            $user = $this->auth_model->get_user_by_token($token);
            if (!empty($user)) {
                if ($this->auth_model->reset_password($user->id)) {
                    $this->session->set_flashdata('success', "Şifreniz başarıyla değiştirildi.");
                    redirect($this->agent->referrer());
                }
                $this->session->set_flashdata('error', "Beklenmeyen bir hata oluştu!");
                redirect($this->agent->referrer());
            }
        }
    }
    public function change_password_post()
    {
        //check user
        if (!$this->auth_check) {
            redirect(base_url());
        }

        $old_password_exists = $this->input->post('old_password_exists', true);

        if ($old_password_exists == 1) {
            $this->form_validation->set_rules('old_password', "Eski Şifre", 'required');
        }
        $this->form_validation->set_rules('password', "Şifre", 'required|min_length[4]|max_length[50]');
        $this->form_validation->set_rules('password_confirm', "Şifre Tekrar", 'required|matches[password]');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('errors', validation_errors());
            $this->session->set_flashdata('form_data', $this->auth_model->change_password_input_values());
            redirect($this->agent->referrer());
        } else {
            if ($this->auth_model->change_password($old_password_exists)) {
                $this->session->set_flashdata('success', "Şifreniz başarılı bir şekilde değiştirildi.");
                redirect($this->agent->referrer());
            } else {
                $this->session->set_flashdata('error', "Beklenmeyen bir hata oluştu.");
                redirect($this->agent->referrer());
            }
        }
    }
}