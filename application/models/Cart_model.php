<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cart_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->cart_product_ids = array();
    }

    //add to cart
    public function add_to_cart($product)
    {
        $cart = $this->session_cart_items;
        $quantity = $this->input->post('product_quantity', true);
        if ($quantity < 1) {
            $quantity = 1;
        }
        
        $appended_variations = $this->input->post('options', true);
        if(empty($appended_variations)){
            $appended_variations ="(default)";
        }

        $product_id = $product->id;
        $product_title = get_product_title($product) . " " . $appended_variations;
        //check if item exists
        $cart = $this->session_cart_items;
        $update_quantity = 0;
        if (!empty($cart)) {
            foreach ($cart as $item) {
                if ($item->product_id == $product_id && $item->product_title == $product_title) {
                    
                    $item->quantity += 1;
                    
                    $update_quantity = 1;
                }
            }
        }
        if ($update_quantity == 1) {
            $this->session->set_userdata('mds_shopping_cart', $cart);
        } else {
            $item = new stdClass();
            $item->cart_item_id = generate_unique_id();
            $item->product_id = $product->id;
            $item->product_title = get_product_title($product) . " " . $appended_variations;
            $item->title=get_product_title($product);
            $item->options=$appended_variations;
            $item->quantity = $quantity;
            $item->commission_type = $product->commission_type;
            $item->commission_rate = $product->commission_rate;
            $item->unit_price = null;
            $item->total_price = null;
            array_push($cart, $item);
            $this->session->set_userdata('mds_shopping_cart', $cart);
        }
    }

    //remove from cart
    public function remove_from_cart($cart_item_id)
    {
        $cart = $this->session_cart_items;
        if (!empty($cart)) {
            $new_cart = array();
            foreach ($cart as $item) {
                if ($item->cart_item_id != $cart_item_id) {
                    array_push($new_cart, $item);
                }
            }
            return $this->session->set_userdata('mds_shopping_cart', $new_cart);
        }
    }

   

    //update cart product quantity
    public function update_cart_product_quantity($product_id, $cart_item_id, $quantity)
    {
        if ($quantity < 1) {
            $quantity = 1;
        }
        $cart = $this->session_cart_items;
        if (!empty($cart)) {
            foreach ($cart as $item) {
                if ($item->cart_item_id == $cart_item_id) {
                    $item->quantity = $quantity;
                }
            }
        }
         $this->session->set_userdata('mds_shopping_cart', $cart);
    }

    //get cart items session
    public function get_sess_cart_items()
    {
        $cart = array();
        $new_cart = array();
        $this->cart_product_ids = array();
        if (!empty($this->session->userdata('mds_shopping_cart'))) {
            $cart = $this->session->userdata('mds_shopping_cart');
        }
        if (!empty($cart)) {
            foreach ($cart as $cart_item) {
                $product = $this->product_model->get_active_product($cart_item->product_id);
                if (!empty($product)) {
                    
                        $item = new stdClass();
                        $item->cart_item_id = $cart_item->cart_item_id;
                        $item->product_id = $product->id;
                        $item->product_title = $cart_item->product_title;
                        $item->title=$product->title;
                        $item->options=$cart_item->options;
                        $item->product_image = $product->img;
                        $item->quantity = $cart_item->quantity;
                        $item->commission_type = $product->commission_type;
                        $item->commission_rate = $product->commission_rate;
                        $item->unit_price = $product->price;
                        $item->total_price = $product->price * $cart_item->quantity;
                        
                        array_push($new_cart, $item);
                    
                }
            }
        }

        

        $this->session->set_userdata('mds_shopping_cart', $new_cart);
        return $new_cart;
    }

    //calculate cart total
    public function calculate_cart_total($cart_items, $currency_code = null, $set_session = true)
    {
        
        $cart_total = new stdClass();
        $cart_total->total = 0;
        if (!empty($cart_items)) {
            foreach ($cart_items as $item) {
                
                $cart_total->total += $item->total_price;
                    
              
            }
        }
        
        if ($set_session == true) {
            $this->session->set_userdata('mds_shopping_cart_total', $cart_total);
        } else {
            return $cart_total;
        }
    }

    //calculate total vat
    public function calculate_total_vat($price, $vat_rate, $quantity)
    {
        $vat = 0;
        if (!empty($price)) {
            $vat = (($price * $vat_rate) / 100) * $quantity;
            if (filter_var($vat, FILTER_VALIDATE_INT) === false) {
                $vat = number_format($vat, 2, ".", "");
            }
        }
        return $vat;
    }

    //check cart has physical products
    public function check_cart_has_physical_product()
    {
        $cart_items = $this->session_cart_items;
        if (!empty($cart_items)) {
            foreach ($cart_items as $cart_item) {
                if ($cart_item->product_type == 'physical') {
                    return true;
                }
            }
        }
        return false;
    }

    //check cart has digital products
    public function check_cart_has_digital_product()
    {
        $cart_items = $this->session_cart_items;
        if (!empty($cart_items)) {
            foreach ($cart_items as $cart_item) {
                if ($cart_item->product_type == 'digital') {
                    return true;
                }
            }
        }
        return false;
    }

    //validate cart
    public function validate_cart()
    {
        $cart_total = $this->cart_model->get_sess_cart_total();
        if (!empty($cart_total)) {
            if ($cart_total->total <= 0 || $cart_total->is_stock_available != 1) {
                redirect(generate_url("cart"));
                exit();
            }
        }
    }

    //get cart total session
    public function get_sess_cart_total()
    {
        $cart_total = new stdClass();
        if (!empty($this->session->userdata('mds_shopping_cart_total'))) {
            $cart_total = $this->session->userdata('mds_shopping_cart_total');
        }
        return $cart_total;
    }

    //set cart payment method option session
    public function set_sess_cart_payment_method()
    {
        $std = new stdClass();
        $std->payment_option = $this->input->post('payment_option', true);
        $std->terms_conditions = $this->input->post('terms_conditions', true);
        $this->session->set_userdata('mds_cart_payment_method', $std);
    }

    //get cart payment method option session
    public function get_sess_cart_payment_method()
    {
        if (!empty($this->session->userdata('mds_cart_payment_method'))) {
            return $this->session->userdata('mds_cart_payment_method');
        }
    }

    //unset cart items session
    public function unset_sess_cart_items()
    {
        if (!empty($this->session->userdata('mds_shopping_cart'))) {
            $this->session->unset_userdata('mds_shopping_cart');
        }
    }

    //unset cart total
    public function unset_sess_cart_total()
    {
        if (!empty($this->session->userdata('mds_shopping_cart_total'))) {
            $this->session->unset_userdata('mds_shopping_cart_total');
        }
    }

    //unset cart payment method option session
    public function unset_sess_cart_payment_method()
    {
        if (!empty($this->session->userdata('mds_cart_payment_method'))) {
            $this->session->unset_userdata('mds_cart_payment_method');
        }
    }

    //clear cart
    public function clear_cart()
    {
        $this->unset_sess_cart_items();
        $this->unset_sess_cart_total();
       // $this->unset_sess_cart_payment_method();
        if (!empty($this->session->userdata('mds_shopping_cart_final'))) {
            $this->session->unset_userdata('mds_shopping_cart_final');
        }
        if (!empty($this->session->userdata('mds_shopping_cart_total_final'))) {
            $this->session->unset_userdata('mds_shopping_cart_total_final');
        }
        if (!empty($this->session->userdata('mds_cart_shipping'))) {
            $this->session->unset_userdata('mds_cart_shipping');
        }
    }

    //get cart total by currency
    public function get_cart_total_by_currency($currency)
    {
        $cart = array();
        $new_cart = array();
        $this->cart_product_ids = array();
        if (!empty($this->session->userdata('mds_shopping_cart'))) {
            $cart = $this->session->userdata('mds_shopping_cart');
        }
        foreach ($cart as $cart_item) {
            $product = $this->product_model->get_active_product($cart_item->product_id);
            if (!empty($product)) {
                //if purchase type is bidding
                if ($cart_item->purchase_type == 'bidding') {
                    $this->load->model('bidding_model');
                    $quote_request = $this->bidding_model->get_quote_request($cart_item->quote_request_id);
                    if (!empty($quote_request) && $quote_request->status == 'pending_payment') {
                        $price_offered = get_price($quote_request->price_offered, 'decimal');
                        //convert currency
                        if (!empty($currency)) {
                            $price_offered = convert_currency_by_exchange_rate($price_offered, $currency->exchange_rate);
                        }
                        $item = new stdClass();
                        $item->purchase_type = $cart_item->purchase_type;
                        $item->quantity = $cart_item->quantity;
                        $item->unit_price = $price_offered / $quote_request->product_quantity;
                        $item->total_price = $price_offered;
                        $item->discount_rate = 0;
                        $item->product_vat = 0;
                        $item->is_stock_available = $cart_item->is_stock_available;
                        array_push($new_cart, $item);
                    }
                } else {
                    $object = $this->get_product_price_and_stock($product, $cart_item->product_title, $cart_item->options_array);
                    //convert currency
                    if (!empty($currency)) {
                        $object->price_calculated = convert_currency_by_exchange_rate($object->price_calculated, $currency->exchange_rate);
                    }
                    $item = new stdClass();
                    $item->purchase_type = $cart_item->purchase_type;
                    $item->quantity = $cart_item->quantity;
                    $item->unit_price = $object->price_calculated;
                    $item->total_price = $object->price_calculated * $cart_item->quantity;
                    $item->discount_rate = $object->discount_rate;
                    $item->product_vat = $this->calculate_total_vat($object->price_calculated, $product->vat_rate, $cart_item->quantity);
                    $item->is_stock_available = $cart_item->is_stock_available;
                    array_push($new_cart, $item);
                }
            }
        }

        return $this->calculate_cart_total($new_cart, $currency->code, false);
    }

    //convert currency by payment gateway
    public function convert_currency_by_payment_gateway($total, $payment_type)
    {
        $data = new stdClass();
        $data->total = $total;
        $data->currency = $this->selected_currency->code;
        $payment_method = $this->get_sess_cart_payment_method();
        if ($this->payment_settings->currency_converter != 1) {
            return $data;
        }
        if (empty($payment_method)) {
            return $data;
        }
        if (empty($payment_method->payment_option) || $payment_method->payment_option == "bank_transfer" || $payment_method->payment_option == "cash_on_delivery") {
            return $data;
        }
        $payment_gateway = get_payment_gateway($payment_method->payment_option);
        if (!empty($payment_gateway)) {
            if (empty($payment_gateway->base_currency) || $payment_gateway->base_currency == "all") {
                $new_currency = $this->selected_currency;
            } else {
                $new_currency = get_currency_by_code($payment_gateway->base_currency);
            }
            if ($payment_type == "sale") {
                if ($payment_gateway->base_currency != $this->selected_currency->code && $payment_gateway->base_currency != "all") {
                    if (!empty($new_currency)) {
                        $new_total = $this->get_cart_total_by_currency($new_currency);
                        if (!empty($new_total)) {
                            $data->total = $new_total->total;
                            $data->currency = $new_currency->code;
                        }
                    }
                }
            } elseif ($payment_type == "membership") {
                $total = get_price($total, 'decimal');
                $new_total = convert_currency_by_exchange_rate($total, $new_currency->exchange_rate);
                if (!empty($new_total)) {
                    $data->total = $new_total;
                    $data->currency = $new_currency->code;
                }
            } elseif ($payment_type == "promote") {
                $new_total = convert_currency_by_exchange_rate($total, $new_currency->exchange_rate);
                if (!empty($new_total)) {
                    $data->total = $new_total;
                    $data->currency = $new_currency->code;
                }
            }
        }
        return $data;
    }

    //set guest shipping address
    public function set_guest_shipping_address()
    {
        return array(
            'first_name' => $this->input->post('shipping_first_name', true),
            'last_name' => $this->input->post('shipping_last_name', true),
            'email' => $this->input->post('shipping_email', true),
            'phone_number' => $this->input->post('shipping_phone_number', true),
            'address' => $this->input->post('shipping_address', true),
            'country_id' => $this->input->post('shipping_country_id', true),
            'state_id' => $this->input->post('shipping_state_id', true),
            'city' => $this->input->post('shipping_city', true),
            'zip_code' => $this->input->post('shipping_zip_code', true)
        );
    }

    //set guest billing address
    public function set_guest_billing_address()
    {
        return array(
            'first_name' => $this->input->post('billing_first_name', true),
            'last_name' => $this->input->post('billing_last_name', true),
            'email' => $this->input->post('billing_email', true),
            'phone_number' => $this->input->post('billing_phone_number', true),
            'address' => $this->input->post('billing_address', true),
            'country_id' => $this->input->post('billing_country_id', true),
            'state_id' => $this->input->post('billing_state_id', true),
            'city' => $this->input->post('billing_city', true),
            'zip_code' => $this->input->post('billing_zip_code', true)
        );
    }
}
