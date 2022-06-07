<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_model extends CI_Model
{
    public function get_products()
    {
        $query = $this->db->order_by('created_at','DESC')->get('products');
        return $query->result();
    }

    //get products count
    public function get_products_count()
    {
        $query = $this->db->get('products');
        return $query->num_rows();
    }

    public function get_fields()
    {
        $this->db->order_by('field_order');
        return $this->db->get('custom_fields')->result();
    }

    public function add_product()
    {
        $price=$this->input->post('price', true);
        $price=str_replace(".","",$price);
        $data = array(
            'slug' => str_slug($this->input->post('title', true)),
            'title' => $this->input->post('title', true),
            'price' => $price,
            'stock' =>$this->input->post('stock', true),
            'commission_type'=>$this->input->post('commission_type', true),
            'commission_rate'=>str_replace(".","",$this->input->post('commission_rate', true)),
            'created_at' => date('Y-m-d H:i:s')
        );

        return $this->db->insert('products', $data);
    }

    public function edit_product($id)
    {
        $price=$this->input->post('price', true);
        $data = array(
            'slug' => str_slug($this->input->post('title', true)),
            'title' => $this->input->post('title', true),
            'stock' =>$this->input->post('stock', true),
            'commission_type'=>$this->input->post('commission_type', true),
            'commission_rate'=>str_replace(".","",$this->input->post('commission_rate', true)),
            'price' => str_replace(".","",$price)
        );
        $this->db->where('id',$id);
        return $this->db->update('products', $data);
    }

    public function add_product_images($last_id,$img)
    {   
        $product=$this->get_active_product($last_id);
        unlink($product->img);
        $data['img']=$img;
        $this->load->helper('file');

        $this->db->where('id',$last_id);
        return $this->db->update('products', $data);
    }

    //get available product
    public function get_active_product($id)
    {
        $id=clean_number($id);
        $this->db->where('id', $id);
        return $this->db->get('products')->row();
    }

    public function delete_product()
    {
        $id=$this->input->post('product_id', true);
        $product=$this->get_active_product($id);
        unlink($product->img);
        $this->db->where('id',$id);
        $this->db->delete('products');
    }

}