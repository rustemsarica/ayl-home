<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order_model extends CI_Model
{
    
    //get orders count
    public function get_orders_count()
    {
        $query = $this->db->get('orders');
        return $query->num_rows();
    }

    //get orders count
    public function get_orders_count_by_user_id($user_id)
    {   
        $query = $this->db->where('user_id',$user_id);
        $query = $this->db->get('orders');
        return $query->num_rows();
    }

    //get order products
    public function get_order_products($order_id)
    {
        $order_id = clean_number($order_id);
        $this->db->where('order_id', $order_id);
        $query = $this->db->get('orders_products');
        return $query->result();
    }

    //get order
    public function get_order($id)
    {
        $id = clean_number($id);
        $this->db->where('id', $id);
        $query = $this->db->get('orders');
        return $query->row();
    }

    //get orders
    public function get_orders_complated()
    {
        return $this->db->where('shipping_key !=',0)->where('is_approved',1)->order_by('orders.created_at', 'DESC')->get('orders')->result();
    }

    public function order_shipping($id,$shipping_key,$shipping_comp)
    {
        $id = clean_number($id);
        $this->db->set(['shipping_key'=>$shipping_key,'shipping_comp'=>$shipping_comp ]);
        $this->db->where('id', $id);
        return $query = $this->db->update('orders');
    }

    public function update_stock($id,$quantity)
    {
        
        $product=$this->db->where('id',$id)->get("products")->row();
        $stock=$product->stock-$quantity;
        return $this->db->set('stock',$stock)->where('id',$id)->update("products");
        
    }
    
    //get orders by buyer id
    public function get_orders_complated_by_user_id($user_id)
    {
        return $this->db->where('shipping_key !=',0)->where('is_approved',1)->where('user_id', $user_id)->order_by('orders.created_at', 'DESC')->get('orders')->result();
    }

    public function get_orders_shipped()
    {
        return $this->db->where('shipping_key !=',0)->where('is_approved',0)->order_by('orders.created_at', 'DESC')->get('orders')->result();
    }

    //get orders by buyer id
    public function get_orders_shipped_by_user_id($user_id)
    {
        return $this->db->where('shipping_key !=',0)->where('is_approved',0)->where('user_id', $user_id)->order_by('orders.created_at', 'DESC')->get('orders')->result();
    }

    //get orders
    public function get_orders()
    {
        return $this->db->where('shipping_key',0)->where('is_approved',0)->order_by('orders.created_at', 'DESC')->get('orders')->result();
    }

    //get orders by buyer id
    public function get_orders_by_user_id($user_id)
    {
        return $this->db->where('shipping_key',0)->where('is_approved',0)->where('user_id', $user_id)->order_by('orders.created_at', 'DESC')->get('orders')->result();
    }

    public function get_orders_total()
    {
        $this->db->select_sum('total')->where('is_approved',1);
        return $query = $this->db->get('orders')->row();
    }

    public function check_unique_order_number($unique)
    {
        $this->db->where('order_number',$unique);
        if($query = $this->db->get('orders')->row()){
            return false;
        }
        return true;
    }

    public function get_unique_order_number()
    {
        $unique=rand(100000000,999999999);
        if($this->check_unique_order_number($unique)){
            return $unique;
        }else{
            $this->get_unique_order_number();
        }
    }

    public function create_order()
    {
        
        $pattern_page = array("(",")","-"," ");
        $user=user();
        $unique=$this->get_unique_order_number();
        $data=array(
            'order_number'  =>$unique,
            'user_id'       =>$user->id,
            'total'         =>$this->cart_model->get_sess_cart_total()->total,
            'buyer_name'    =>$this->input->post('buyer_name', TRUE),
            'buyer_phone'   =>str_replace($pattern_page,"",$this->input->post('buyer_phone', TRUE)),
            'address'       =>$this->input->post('address', TRUE),
            'city'          =>$this->input->post('city', TRUE),
            'town'          =>$this->input->post('town', TRUE),
            'commission_amount' =>clean_number($this->calculate_commission_amount()),
            'user_name'      => $user->first_name.' '.$user->last_name,  
        );
            $query=$this->db->insert('orders', $data);
        if($query==1){
            $cart_items=$this->cart_model->get_sess_cart_items();
            foreach($cart_items as $item){
                $order_product=array(
                    'order_id'=>$unique,
                    'quantity'=>$item->quantity,
                    'title'=>$item->title,
                    'options'=>$item->options,
                    'commission_type' => $item->commission_type,
                    'commission_rate' => $item->commission_rate,
                    'unit_price'=>$item->unit_price,
                    'product_id'=>$item->product_id,
                );
                $this->db->insert('orders_products', $order_product);
                $this->order_model->update_stock($item->product_id,$item->quantity);
            }
            $this->cart_model->clear_cart();
            $return['message']="<strong>".$unique."</strong> numaralı siparişiniz oluşturuldu!";
            return $return;
        }
        return false;
    }

    public function delete_order()
    {
        $id=$this->input->post("order_number",true);
        if(is_admin()){
            if($this->db->where("order_number",$id)->delete('orders')){
               return $this->db->where("order_id",$id)->delete('orders_products'); 
            }else{
                return false;
            }
            
        }else{
            $user=user();
             if($this->db->where("order_number",$id)->where('user_id',$user->id)->where('is_approved',0)->delete('orders')){
                 return $this->db->where("order_id",$id)->delete('orders_products');
             }else{
                return false;
            }
                
        }
    }

    public function is_approved_order()
    {
        $user=user();
        $id=$this->input->post('id', TRUE);
        $this->db->where('user_id',$user->id);
        $this->db->where('id',$id);
        if($this->db->update('orders',['is_approved'=>1])){
            $number_of_sales=$user->number_of_sales+1;
            $data=$this->get_order($id);
            return $this->earnings_model->add_earnings($data);
        }
    }

    public function calculate_commission_amount()
    {   
        $commission_amount=0;
        $cart_items=$this->cart_model->get_sess_cart_items();
        foreach($cart_items as $item){
            if($item->commission_type=="Yüzde"){
                $rate=$item->commission_rate/100;
                $commission_amount+=($item->total_price*$rate)/100;
            }elseif($item->commission_type=="Sabit"){
                $commission_amount+=$item->quantity*$item->commission_rate;
            }
        }

        return $commission_amount;
    }

    public function date_of_orders($start, $daycount)
    {
        $counts=array();
        $total=array();
        $users=array();
        $total_sum=0;
        $total_count=0;
        $total_product=0;
        for($i=0;$i<$daycount+1;$i++){            
            $start_date=date('Y-m-d 00:00:00' ,strtotime($start."+".$i." day"));
            $then=date('Y-m-d 00:00:00' ,strtotime($start_date."+1 day"));
            $results=$this->db->where('created_at >=',$start_date)->where('created_at <',$then)->get('orders')->result();
            $count=0;
            if($results){
               foreach($results as $item){
                    $total_sum+=$item->total;
                    $total_product+=$this->db->where('order_id',$item->order_number)->from('orders_products')->count_all_results();
                    $count+=1;
                    $amount=$item->total;
                    $username=$item->user_name;
                    
                    if(!array_key_exists($username, $users)){
                        $users[$username]=array(
                                "user_count"=>$count,
                                "user_sales"=>$amount
                        );
                    }else{                        
                        $users[$username]['user_count']+=1;
                        $users[$username]['user_sales']+=$amount;
                    }  
                }                
            }
            $total_count+=$count;
            array_push($counts,["date"=>$start_date,"count"=>$count]);
        }
        $list="";
        if(count($users)>0){
            foreach ($users as $anahtar => $satır) {
                $user_count[$anahtar] = $satır['user_count'];
                $user_sales[$anahtar] = $satır['user_sales'];
            }
            
            array_multisort($user_sales, SORT_DESC, $user_count, SORT_DESC, $users);
            $users=array_slice($users,0,5);
            
            if(count($users)>=1){
            $list='<li class="media event">
                <a class="pull-left border-aero profile_thumb">
                <i class="fa fa-user aero"></i>
                </a>
                <div class="media-body">
                <a class="title" href="#">'.array_keys($users)[0].'</a>
                <p><strong>'.price_formatted($users[array_keys($users)[0]]['user_sales']).'<i class="fa fa-turkish-lira"></i> </strong> Toplam Satış </p>
                <p> <small>'.$users[array_keys($users)[0]]['user_count'].' sipariş</small>
                </p>
                </div>
            </li>';
            }
            if(count($users)>=2){ 
          $list.='<li class="media event">
            <a class="pull-left border-green profile_thumb">
              <i class="fa fa-user green"></i>
            </a>
            <div class="media-body">
              <a class="title" href="#">'.array_keys($users)[1].'</a>
              <p><strong>'.price_formatted($users[array_keys($users)[1]]['user_sales']).'<i class="fa fa-turkish-lira"></i> </strong> Toplam Satış </p>
              <p> <small>'.$users[array_keys($users)[1]]['user_count'].' sipariş</small>
              </p>
            </div>
          </li>';}
          if(count($users)>=3){
          $list.='<li class="media event">
            <a class="pull-left border-blue profile_thumb">
              <i class="fa fa-user blue"></i>
            </a>
            <div class="media-body">
              <a class="title" href="#">'.array_keys($users)[2].'</a>
              <p><strong>'.price_formatted($users[array_keys($users)[2]]['user_sales']).'<i class="fa fa-turkish-lira"></i> </strong> Toplam Satış </p>
              <p> <small>'.$users[array_keys($users)[2]]['user_count'].' sipariş</small>
              </p>
            </div>
          </li>';}
          if(count($users)>=4){
          $list.='<li class="media event">
            <a class="pull-left border-aero profile_thumb">
              <i class="fa fa-user aero"></i>
            </a>
            <div class="media-body">
              <a class="title" href="#">'.array_keys($users)[3].'</a>
              <p><strong>'.price_formatted($users[array_keys($users)[3]]['user_sales']).'<i class="fa fa-turkish-lira"></i> </strong> Toplam Satış </p>
              <p> <small>'.$users[array_keys($users)[3]]['user_count'].' sipariş</small>
              </p>
            </div>
          </li>';}
          if(count($users)>=5){
          $list.='<li class="media event">
            <a class="pull-left border-green profile_thumb">
              <i class="fa fa-user green"></i>
            </a>
            <div class="media-body">
              <a class="title" href="#">'.array_keys($users)[4].'</a>
              <p><strong>'.price_formatted($users[array_keys($users)[4]]['user_sales']).'<i class="fa fa-turkish-lira"></i> </strong> Toplam Satış </p>
              <p> <small>'.$users[array_keys($users)[4]]['user_count'].' sipariş</small>
              </p>
            </div>
          </li>';}
        }
        
        $data=array(
            'counts'=>$counts,
            'total'=>array(
                "total_count"=>$total_count,
                "total_sum"=>price_formatted($total_sum),
                "total_product"=>$total_product
                ),
            'users'=>$list
        );
        return $data;
        

    }

}