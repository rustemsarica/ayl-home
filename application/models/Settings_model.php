<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Settings_model extends CI_Model
{
    public function get_general_settings()
    {
        $this->db->where('id',1);
        return $this->db->get('settings')->row();
    }

    public function update_general_settings()
    {
        if(is_admin()){
            $data=$this->input->post(NULL, TRUE);
            $data['updated_at']=date("Y-m-d h:i:s");
            $this->db->where('id',1);
            return $this->db->update('settings',$data);
        }
    }

    public function profile_post()
    {
        $this->load->library('bcrypt');
        $data=$this->input->post(NULL,TRUE);
        $user=user();

        if (!empty($user)) {
            //check password
            if (!$this->bcrypt->check_password($data['password'], $user->password)) {
                $returns=array(
                    'status'=>0,
                    'message' =>"Hatalı şifre girişi yaptınız!"
                );
                return $returns;
            }else{
                $pattern_page = array("(",")","-"," ");
                unset($data['password']);
                $data['phone_number']=str_replace($pattern_page,"",$this->input->post('phone_number', TRUE));
                $this->db->where('id',$user->id);
                $this->db->update('users',$data);
                $returns=array(
                    'status'=>1,
                    'message' =>"Bilgileriniz güncellenmiştir."
                );
                return $returns;
            }
        }
    }

    public function add_profile_pic($img)
    {
        $user=user();
        if(!empty($user->img)){
            unlink($user->img);
        }
        $this->db->where('id',$user->id);
        return $this->db->update('users',['img'=>$img]);
    }
    public function get_cities()
    {
        return $this->db->order_by('il')->get('iller')->result();
    }

    public function get_towns($city)
    {
        $this->db->where('il',$city);
        return $this->db->get('ilceler')->result();
    }

    public function counts(Type $var = null)
    {
        $data['orders']=$this->db->where('is_approved',0)->where('shipping_key',0)->from('orders')->count_all_results();
        $data['payouts']=$this->db->where('status',0)->from('payouts')->count_all_results();
        $data['users']=$this->db->where('status',0)->from('users')->count_all_results();
        return json_encode($data);
    }

}