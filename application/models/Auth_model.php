<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    //input values
    public function input_values()
    {
        $data = array(
            'username' => remove_special_characters($this->input->post('username', true)),
            'email' => mb_strtolower($this->input->post('email', true)),
            'first_name' => mb_convert_case($this->input->post('first_name', true), MB_CASE_TITLE, "UTF-8"),
            'last_name' => mb_convert_case($this->input->post('last_name', true), MB_CASE_TITLE, "UTF-8"),
            'password' => $this->input->post('password', true)
        );
        return $data;
    }

    //login
    public function login()
    {
        $this->load->library('bcrypt');

        $data = $this->input_values();
        $user = $this->get_user_by_username($data['username']);

        if (!empty($user)) {
            //check password
            if (!$this->bcrypt->check_password($data['password'], $user->password)) {
                $returns=array(
                    'status'=>0,
                    'message' =>"Hatalı şifre girişi yaptınız!"
                );
                $this->session->set_flashdata('error', "Hatalı şifre girişi yaptınız!");
                return $returns;
            }
            
            if ($user->status != 1) {
                $returns=array(
                    'status'=>0,
                    'message' =>"Hesabınız yönetici tarafından onaylanmamıştır!"
                );
                return $returns;
            }
            /*
            if ($user->banned == 1) {
                $this->session->set_flashdata('error', trans("msg_ban_error"));
                return false;
            }
            */
            //set user data
            $user_data = array(
                'mds_sess_user_id' => $user->id,
                'mds_sess_user_email' => $user->email,
                'mds_sess_user_role' => $user->role,
                'mds_sess_user_ps' => md5($user->password),
                'mds_sess_logged_in' => true,
            );
            $this->session->set_userdata($user_data);
            $returns=array(
                'status'=>1,
                'message' =>"Giriş işleminiz başarılı!"
            );
            return $returns;
        } else {
            $returns=array(
                'status'=>0,
                'message' =>"Kayıtlı hesap bulunamadı!"
            );
            return $returns;
        }
    }

    //login direct
    public function login_direct($user)
    {
        //set user data
        $user_data = array(
            'mds_sess_user_id' => $user->id,
            'mds_sess_user_email' => $user->email,
            'mds_sess_user_role' => $user->role,
            'mds_sess_user_ps' => md5($user->password),
            'mds_sess_logged_in' => true,
        );

        $this->session->set_userdata($user_data);
    }

    //generate uniqe username
    public function generate_uniqe_username($username)
    {
        $new_username = $username;
        if (!empty($this->get_user_by_username($new_username))) {
            $new_username = $username . " 1";
            if (!empty($this->get_user_by_username($new_username))) {
                $new_username = $username . " 2";
                if (!empty($this->get_user_by_username($new_username))) {
                    $new_username = $username . " 3";
                    if (!empty($this->get_user_by_username($new_username))) {
                        $new_username = $username . "-" . uniqid();
                    }
                }
            }
        }
        return $new_username;
    }

    //generate uniqe slug
    public function generate_uniqe_slug($username)
    {
        $slug = str_slug($username);
        if (!empty($this->get_user_by_slug($slug))) {
            $slug = str_slug($username . "-1");
            if (!empty($this->get_user_by_slug($slug))) {
                $slug = str_slug($username . "-2");
                if (!empty($this->get_user_by_slug($slug))) {
                    $slug = str_slug($username . "-3");
                    if (!empty($this->get_user_by_slug($slug))) {
                        $slug = str_slug($username . "-" . uniqid());
                    }
                }
            }
        }
        return $slug;
    }

    //register
    public function register()
    {
        $this->load->library('bcrypt');

        $data = $this->auth_model->input_values();
        $data['username'] = remove_special_characters($data['username']);
        //secure password
        $data['password'] = $this->bcrypt->hash_password($data['password']);
        $data['role'] = "member";
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['token'] = generate_token();
        if(!$this->is_unique_email($data['email'])){
            return false;
        }
        if(!$this->is_unique_username($data['username'])){
            $this->generate_uniqe_slug($data['username']);
        }
        /*
        if ($this->general_settings->email_verification == 1) {
            $data['email_status'] = 0;
        }
        if ($this->general_settings->vendor_verification_system != 1) {
            $data['role'] = "vendor";
        }
        */
        if ($this->db->insert('users', $data)) {
            $last_id = $this->db->insert_id();
            /*
            if ($this->general_settings->email_verification == 1) {
                $user = $this->get_user($last_id);
                if (!empty($user)) {
                    $this->session->set_flashdata('success', trans("msg_register_success") . " " . trans("msg_send_confirmation_email") . "&nbsp;<a href='javascript:void(0)' class='link-resend-activation-email' onclick=\"send_activation_email_register('" . $user->id . "','" . $user->token . "');\">" . trans("resend_activation_email") . "</a>");
                    $this->send_email_activation_ajax($user->id, $user->token);
                }
            }
            */
            return $last_id;
        } else {
            return false;
        }
    }

    //send email activation
    public function send_email_activation($user_id, $token)
    {
        if (!empty($user_id)) {
            $user = $this->get_user($user_id);
            if (!empty($user)) {
                if (!empty($user->token) && $user->token != $token) {
                    exit();
                }
                //check token
                $data['token'] = $user->token;
                if (empty($data['token'])) {
                    $data['token'] = generate_token();
                    $this->db->where('id', $user->id);
                    $this->db->update('users', $data);
                }
                //send email
                $email_data = array(
                    'template_path' => "email/email_general",
                    'to' => $user->email,
                    'subject' => trans("confirm_your_account"),
                    'email_content' => trans("msg_confirmation_email"),
                    'email_link' => lang_base_url() . "confirm?token=" . $data['token'],
                    'email_button_text' => trans("confirm_your_account")
                );
                $this->load->model("email_model");
                $this->email_model->send_email($email_data);
            }
        }
    }

    //send email activation
    public function send_email_activation_ajax($user_id, $token)
    {
        if (!empty($user_id)) {
            $user = $this->get_user($user_id);
            if (!empty($user)) {
                if (!empty($user->token) && $user->token != $token) {
                    exit();
                }
                //check token
                $data['token'] = $user->token;
                if (empty($data['token'])) {
                    $data['token'] = generate_token();
                    $this->db->where('id', $user->id);
                    $this->db->update('users', $data);
                }

                //send email
                $email_data = array(
                    'email_type' => 'email_general',
                    'to' => $user->email,
                    'subject' => trans("confirm_your_account"),
                    'email_content' => trans("msg_confirmation_email"),
                    'email_link' => lang_base_url() . "confirm?token=" . $data['token'],
                    'email_button_text' => trans("confirm_your_account")
                );
                $this->session->set_userdata('mds_send_email_data', json_encode($email_data));
            }
        }
    }

    //add administrator
    public function add_administrator()
    {
        $this->load->library('bcrypt');

        $data = $this->auth_model->input_values();
        //secure password
        $data['password'] = $this->bcrypt->hash_password($data['password']);
        $data['user_type'] = "registered";
        $data["slug"] = $this->generate_uniqe_slug($data["username"]);
        $data['role'] = "admin";
        $data['banned'] = 0;
        $data['email_status'] = 1;
        $data['token'] = generate_token();
        $data['last_seen'] = date('Y-m-d H:i:s');
        $data['created_at'] = date('Y-m-d H:i:s');

        return $this->db->insert('users', $data);
    }

    //update slug
    public function update_slug($id)
    {
        $id = clean_number($id);
        $user = $this->get_user($id);

        if (empty($user->slug) || $user->slug == "-") {
            $data = array(
                'slug' => "user-" . $user->id,
            );
            $this->db->where('id', $id);
            $this->db->update('users', $data);

        } else {
            if ($this->check_is_slug_unique($user->slug, $id) == true) {
                $data = array(
                    'slug' => $user->slug . "-" . $user->id
                );

                $this->db->where('id', $id);
                $this->db->update('users', $data);
            }
        }
    }

    //logout
    public function logout()
    {
        //unset user data
        $this->session->unset_userdata('mds_sess_user_id');
        $this->session->unset_userdata('mds_sess_user_email');
        $this->session->unset_userdata('mds_sess_user_role');
        $this->session->unset_userdata('mds_sess_user_ps');
        $this->session->unset_userdata('mds_sess_logged_in');
        $this->session->unset_userdata('mds_sess_app_key');
    }

    //reset password
    public function reset_password($id)
    {
        $id = clean_number($id);
        $this->load->library('bcrypt');
        $new_password = $this->input->post('password', true);
        $data = array(
            'password' => $this->bcrypt->hash_password($new_password),
            'token' => generate_token()
        );
        //change password
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    //delete user
    public function delete_user($id)
    {
        $id = clean_number($id);
        $user = $this->get_user($id);
        if (!empty($user)) {
            /*
            //delete products
            $products = $this->db->where('user_id', $user->id)->get('products')->result();
            if (!empty($products)) {
                foreach ($products as $product) {
                    $this->product_admin_model->delete_product_permanently($product->id);
                }
            }
            */
            
            return $this->db->where('id', $user->id)->delete('users');
        }
        return false;
    }

    //get logged user
    public function get_logged_user()
    {
        if (!empty($this->session->userdata('mds_sess_user_id'))) {
            $user = $this->get_user($this->session->userdata('mds_sess_user_id'));
            if (!empty($user)) {
                //if ($user->banned == 0) {
                    $sess_pass = $this->session->userdata("mds_sess_user_ps");
                    if (!empty($sess_pass) && md5($user->password) == $sess_pass) {
                        return $user;
                    }
                //}
            }
        }
        return false;
    }

    //get user by id
    public function get_user($id)
    {
        $id = clean_number($id);
        $this->db->where('id', $id);
        $query = $this->db->get('users');
        return $query->row();
    }

    //get user by email
    public function get_user_by_email($email)
    {
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        return $query->row();
    }

    //get user by username
    public function get_user_by_username($username)
    {
        $username = remove_special_characters($username);
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        return $query->row();
    }

    //get user by slug
    public function get_user_by_slug($slug)
    {
        $this->db->where('slug', $slug);
        $query = $this->db->get('users');
        return $query->row();
    }

    //get user by token
    public function get_user_by_token($token)
    {
        $token = remove_special_characters($token);
        $this->db->where('token', $token);
        $query = $this->db->get('users');
        return $query->row();
    }

    //get users
    public function get_users()
    {   
        $this->db->where('role','member');
        $this->db->where('status',1);
        $query = $this->db->get('users');
        return $query->result();
    }

    public function get_users_no_approve()
    {   
        $this->db->where('role','member');
        $this->db->where('status',0);
        $query = $this->db->get('users');
        return $query->result();
    }

    public function get_users_no_approve_count()
    {
        $this->db->where('role','member');
        $this->db->where('status',0);
        $query = $this->db->get('users');
        return $query->num_rows();
    }

    public function approve_user($id)
    {
        $this->db->set('status',1);
        $this->db->where('id',$id);
        return $query = $this->db->update('users');
    }

    //get users count
    public function get_users_count()
    {
        $this->db->where('role','member');
        $this->db->where('status',1);
        $query = $this->db->get('users');
        return $query->num_rows();
    }

    //get paginated vendors
    public function get_paginated_vendors($per_page, $offset)
    {
        $this->db->select('users.*, (SELECT COUNT(id) FROM products WHERE users.id = products.user_id AND products.status = 1 AND products.visibility = 1 AND products.is_draft = 0 AND products.is_deleted = 0) AS num_products');
        $this->db->group_start()->where('banned', 0)->group_end();
        return $this->db->where('role', 'vendor')->or_where('role', 'admin')->order_by('num_products DESC, created_at DESC')->limit(clean_number($per_page), clean_number($offset))->get('users')->result();
    }

    //get users count by role
    public function get_paginated_vendors_count()
    {
        $this->db->group_start()->where('banned', 0)->group_end();
        return $this->db->where('role', 'vendor')->or_where('role', 'admin')->count_all_results('users');
    }

    //get paginated users
    public function get_paginated_filtered_users($role, $per_page, $offset)
    {
        $this->filter_users();
        $this->db->where('role', clean_str($role));
        $this->db->order_by('created_at', 'DESC')->limit(clean_number($per_page), clean_number($offset));
        return $this->db->get('users')->result();
    }

    //get users count by role
    public function get_users_count_by_role($role)
    {
        $this->filter_users();
        return $this->db->where('role', clean_str($role))->count_all_results('users');
    }

    //users filter
    public function filter_users()
    {
        $q = input_get('q');
        if (!empty($q)) {
            $this->db->group_start();
            $this->db->like('username', clean_str($q));
            $this->db->or_like('email', clean_str($q));
            $this->db->group_end();
        }
        $status = input_get('status');
        if (!empty($status)) {
            $banned = $status == 'banned' ? 1 : 0;
            $this->db->where('banned', $banned);
        }
        $email_status = input_get('email_status');
        if (!empty($email_status)) {
            $status = $email_status == 'confirmed' ? 1 : 0;
            $this->db->where('email_status', $status);
        }
    }

    //get latest members
    public function get_latest_members($limit)
    {
        $limit = clean_number($limit);
        $this->db->limit($limit);
        $this->db->order_by('users.id', 'DESC');
        $query = $this->db->get('users');
        return $query->result();
    }

    //get last users
    public function get_last_users()
    {
        $this->db->order_by('users.id', 'DESC');
        $this->db->limit(7);
        $query = $this->db->get('users');
        return $query->result();
    }

    //check slug
    public function check_is_slug_unique($slug, $id)
    {
        $id = clean_number($id);
        $this->db->where('users.slug', $slug);
        $this->db->where('users.id !=', $id);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    //check if email is unique
    public function is_unique_email($email, $user_id = 0)
    {
        $user_id = clean_number($user_id);
        $user = $this->auth_model->get_user_by_email($email);

        //if id doesnt exists
        if ($user_id == 0) {
            if (empty($user)) {
                return true;
            } else {
                return false;
            }
        }

        if ($user_id != 0) {
            if (!empty($user) && $user->id != $user_id) {
                //email taken
                return false;
            } else {
                return true;
            }
        }
    }

    //check if username is unique
    public function is_unique_username($username, $user_id = 0)
    {
        $user = $this->get_user_by_username($username);

        //if id doesnt exists
        if ($user_id == 0) {
            if (empty($user)) {
                return true;
            } else {
                return false;
            }
        }

        if ($user_id != 0) {
            if (!empty($user) && $user->id != $user_id) {
                //username taken
                return false;
            } else {
                return true;
            }
        }
    }

    //check if shop name is unique
    public function is_unique_shop_name($shop_name, $user_id = 0)
    {
        $user = $this->get_user_by_shop_name($shop_name);
        //if id doesnt exists
        if ($user_id == 0) {
            if (empty($user)) {
                return true;
            } else {
                return false;
            }
        }

        if ($user_id != 0) {
            if (!empty($user) && $user->id != $user_id) {
                //shop name taken
                return false;
            } else {
                return true;
            }
        }
    }

    //verify email
    public function verify_email($user)
    {
        if (!empty($user)) {
            $data = array(
                'email_status' => 1,
                'token' => generate_token()
            );
            $this->db->where('id', $user->id);
            return $this->db->update('users', $data);
        }
        return false;
    }
//change password input values
public function change_password_input_values()
{
    $data = array(
        'old_password' => $this->input->post('old_password', true),
        'password' => $this->input->post('password', true),
        'password_confirm' => $this->input->post('password_confirm', true)
    );
    return $data;
}

//change password
public function change_password($old_password_exists)
{
    $this->load->library('bcrypt');
    $user = $this->auth_user;
    if (!empty($user)) {
        $data = $this->change_password_input_values();
        if ($old_password_exists == 1) {
            //password does not match stored password.
            if (!$this->bcrypt->check_password($data['old_password'], $user->password)) {
                $this->session->set_flashdata('error', "Eski şifreniz yanlış.");
                $this->session->set_flashdata('form_data', $this->change_password_input_values());
                redirect($this->agent->referrer());
            }
        }

        $data = array(
            'password' => $this->bcrypt->hash_password($data['password'])
        );

        $this->db->where('id', $user->id);
        if ($this->db->update('users', $data)) {
            $this->session->set_userdata("mds_sess_user_ps", md5($data['password']));
            return true;
        }
    } else {
        return false;
    }
}

}
