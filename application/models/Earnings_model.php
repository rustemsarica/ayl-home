<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Earnings_model extends CI_Model
{
    public function get_earnings()
    {
        $this->db->order_by('created_at', 'DESC');
        return $query=$this->db->get('earnings')->result();
    }

    public function get_earnings_by_user($id)
    {   
        $this->db->where('user_id', $id);
        $this->db->order_by('created_at', 'DESC');
        return $query=$this->db->get('earnings')->result();
    }

    public function get_payouts()
    {
        $this->db->order_by('created_at', 'DESC');
        return $query=$this->db->get('payouts')->result();
    }

    public function get_payouts_by_user($id)
    {   
        $this->db->where('user_id', $id);
        $this->db->order_by('status', 'asc');
        $this->db->order_by('created_at', 'DESC');
        return $query=$this->db->get('payouts')->result();
    }

    public function update_user_balance($total,$user_id)
    {
        $user=get_user($user_id);
        $balance=$user->balance+$total;
        $this->db->where('id',$user_id);
        return $this->db->update('users',['balance'=>$balance]);
    }

    public function payouts_request_post()
    {
        if($this->check_payouts_request($data['user_id'])){
            $return=array(
                'status'=>'error',
                'message'=>'Bekleyen bir talebiniz varken yeni talep oluşturamazsınız.'
            );
        }
        $data=$this->input->post(NULL,TRUE);
        $data['amount']=str_replace('.','',$data['amount']);

        if($data['amount']>user()->balance){
            $return=array(
                'status'=>'error',
                'message'=>'Bakiyeniz yetersiz. Lütfen gözden geçirin.'
            );
        }
        return $this->db->insert('payouts',$data);
        
    }

    public function payout_approve($id,$total,$user_id)
    {   
        $data=array(
            'status'=>1,
            'updated_at'=>date('Y-m-d H:i:s')
        );
        $this->db->where('id',$id)->update('payouts',$data);
        $total=0-$total;
        return $this->update_user_balance($total,$user_id);
    }

    public function payout_cancel($id)
    {   
        $this->db->where('id',$id);
        $this->db->where('status',0)->delete('payouts');
        return true;
    }

    public function check_payouts_request($id)
    {
        $this->db->where('user_id',$id);
        $this->db->where('status',0);
        return $this->db->get('payouts')->row();
    }

    public function add_earnings($data)
    {
        if($data->is_approved==1){
            
            $earning=array(
                'total'=>$data->commission_amount,
                'user_id'=>$data->user_id,
                'user_name'=>$data->user_name,
                'order_id'=>$data->id,
                'order_total'=>$data->total,
                'order_number'=>$data->order_number
            );
            if($this->db->insert('earnings',$earning)){
                return $this->update_user_balance($data->commission_amount,$data->user_id);
            }
        }else{
            return false;
        }
        
    }
}