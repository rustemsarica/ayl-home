<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ajax_controller extends Home_Core_Controller
{
    public function __construct() {
        parent::__construct();
        
        if (!$this->auth_check) {
            redirect('login');
        }
    }

   

    
	
    

    /*
     *------------------------------------------------------------------------------------------
     * VARIATION FUNCTIONS
     *------------------------------------------------------------------------------------------
     */

    //select variation option
    public function select_product_variation_option()
    {
        $variation_id = $this->input->post('variation_id', true);
        $selected_option_id = $this->input->post('selected_option_id', true);
        $variation = $this->variation_model->get_variation($variation_id);
        $option = $this->variation_model->get_variation_option($selected_option_id);

        $data = array(
            'status' => 0,
            'html_content_slider' => "",
            'html_content_price' => "",
            'html_content_stock' => "",
            'stock_status' => 1,
        );
        if (!empty($variation) && !empty($option)) {
            $product = $this->product_model->get_product_by_id($variation->product_id);

            //slider content response
            if ($variation->show_images_on_slider) {
                $product_images = $this->variation_model->get_variation_option_images($selected_option_id);
                if (empty($product_images)) {
                    $product_images = $this->file_model->get_product_images($variation->product_id);
                }
                $vars = array(
                    "product" => $product,
                    "product_images" => $product_images
                );
                $data["html_content_slider"] = $this->load->view('product/details/_preview', $vars, true);
            }

            //price content response
            if ($variation->use_different_price == 1) {
                $price = $product->price;
                $discount_rate = $product->discount_rate;
                if (isset($option->price)) {
                    $price = $option->price;
                }
                if (isset($option->discount_rate)) {
                    $discount_rate = $option->discount_rate;
                }
                if (empty($price)) {
                    $price = $product->price;
                    $discount_rate = $product->discount_rate;
                }
                $vars = array(
                    "product" => $product,
                    "price" => $price,
                    "discount_rate" => $discount_rate
                );
                $data["html_content_price"] = $this->load->view('product/details/_price', $vars, true);
            }

            //stock content response
            $stock = $product->stock;
            if ($option->is_default != 1) {
                $stock = $option->stock;
            }
            if ($stock == 0) {
                $data["html_content_stock"] = '<span class="text-danger">' . trans("out_of_stock") . '</span>';
                $data["stock_status"] = 0;
            } else {
                $data["html_content_stock"] = '<span class="text-success">' . trans("in_stock") . '</span>';
            }
            $data["status"] = 1;

        }
        echo json_encode($data);
    }

    //get sub variation options
    public function get_sub_variation_options()
    {
        $variation_id = $this->input->post('variation_id', true);
        $selected_option_id = $this->input->post('selected_option_id', true);
        $subvariation = $this->variation_model->get_product_sub_variation($variation_id);
        $content = null;
        $data = array(
            'status' => 0,
            'subvariation_id' => "",
            'html_content' => ""
        );
        if (!empty($subvariation)) {
            $options = $this->variation_model->get_variation_sub_options($selected_option_id);
            if (!empty($options)) {
                $content .= '<option value="">' . trans("select") . '</option>';
                foreach ($options as $option) {
                    $option_name = get_variation_option_name($option->option_names, $this->selected_lang->id);
                    $content .= '<option value="' . $option->id . '">' . html_escape($option_name) . '</option>';
                }
            }
            $data["status"] = 1;
            $data["subvariation_id"] = $subvariation->id;
            $data["html_content"] = $content;
        }

        echo json_encode($data);
    }

    /*
    *------------------------------------------------------------------------------------------
    * WISHLIST FUNCTIONS
    *------------------------------------------------------------------------------------------
    */

    //add or remove wishlist
    public function add_remove_wishlist()
    {
        $product_id = $this->input->post('product_id', true);
        $this->product_model->add_remove_wishlist($product_id);
    }


    

    /*
    *------------------------------------------------------------------------------------------
    * EMAIL FUNCTIONS
    *------------------------------------------------------------------------------------------
    */

    //send email
    public function send_email()
    {
        $email_type = $this->input->post('email_type', true);
        if ($email_type == 'contact') {
            $this->send_email_contact_message();
        } elseif ($email_type == 'new_order') {
            $this->send_email_new_order();
        } elseif ($email_type == 'new_product') {
            $this->send_email_new_product();
        } elseif ($email_type == 'order_shipped') {
            $this->send_email_order_shipped();
        } elseif ($email_type == 'new_message') {
            $this->send_email_new_message();
        } elseif ($email_type == 'email_general') {
            $this->send_email_general();
        }
    }

    //send email contact message
    public function send_email_contact_message()
    {
        if ($this->general_settings->send_email_contact_messages == 1) {
            $this->load->model("email_model");
            $data = array(
                'subject' => trans("contact_message"),
                'to' => $this->general_settings->mail_options_account,
                'template_path' => "email/email_contact_message",
                'message_name' => $this->input->post('message_name', true),
                'message_email' => $this->input->post('message_email', true),
                'message_text' => $this->input->post('message_text', true)
            );
            $this->email_model->send_email($data);
        }
    }

    //send email order summary to user
    public function send_email_new_order()
    {
        if ($this->general_settings->send_email_buyer_purchase == 1) {
            $this->load->model("email_model");
            $order_id = $this->input->post('order_id', true);
            $order_id = clean_number($order_id);
            $order = get_order($order_id);
            $order_products = $this->order_model->get_order_products($order_id);
            $order_shipping = get_order_shipping($order_id);
            if (!empty($order)) {
                //send to buyer
                $to = "";
                if (!empty($order_shipping)) {
                    $to = $order_shipping->shipping_email;
                }
                if ($order->buyer_type == "registered") {
                    $user = get_user($order->buyer_id);
                    if (!empty($user)) {
                        $to = $user->email;
                    }
                }
                $data = array(
                    'subject' => trans("email_text_thank_for_order"),
                    'order' => $order,
                    'order_products' => $order_products,
                    'to' => $to,
                    'template_path' => "email/email_new_order"
                );
                $this->email_model->send_email($data);

                //send to seller
                if (!empty($order_products)) {
                    $seller_ids = array();
                    foreach ($order_products as $order_product) {
                        $seller = get_user($order_product->seller_id);
                        if (!empty($seller)) {
                            if (!in_array($seller->id, $seller_ids)) {
                                array_push($seller_ids, $seller->id);
                                $seller_order_products = $this->order_model->get_seller_order_products($order_id, $seller->id);
                                $data = array(
                                    'subject' => trans("you_have_new_order"),
                                    'order' => $order,
                                    'order_products' => $seller_order_products,
                                    'to' => $seller->email,
                                    'template_path' => "email/email_new_order_seller"
                                );
                                $this->email_model->send_email($data);
                            }
                        }
                    }
                }
            }
        }
    }

    //send email new product
    public function send_email_new_product()
    {
        if ($this->general_settings->send_email_new_product == 1) {
            $this->load->model("email_model");
            $product_id = $this->input->post('product_id', true);
            $product = $this->product_model->get_product_by_id($product_id);
            if (!empty($product)) {
                $data = array(
                    'subject' => trans("email_text_new_product"),
                    'product_url' => generate_product_url($product),
                    'to' => $this->general_settings->mail_options_account,
                    'template_path' => "email/email_new_product"
                );
                $this->email_model->send_email($data);
            }
        }
    }

    //send email new message
    public function send_email_new_message()
    {
        $this->load->model("email_model");
        $sender_id = $this->input->post('sender_id', true);
        $receiver_id = $this->input->post('receiver_id', true);
        $receiver = get_user($receiver_id);
        if (!empty($receiver) && !empty($sender_id)) {
            $data = array(
                'subject' => trans("you_have_new_message"),
                'to' => $receiver->email,
                'template_path' => "email/email_new_message",
                'message_sender' => "",
                'message_subject' => $this->input->post('message_subject', true),
                'message_text' => $this->input->post('message_text', true)
            );
            $sender = get_user($sender_id);
            if (!empty($sender)) {
                $data['message_sender'] = $sender->username;
            }
            $this->email_model->send_email($data);
        }
    }

    //send email order shipped
    public function send_email_order_shipped()
    {
        if ($this->general_settings->send_email_order_shipped == 1) {
            $this->load->model("email_model");
            $order_product_id = $this->input->post('order_product_id', true);
            $order_product = $this->order_model->get_order_product($order_product_id);
            if (!empty($order_product)) {
                $order = get_order($order_product->order_id);
                $order_shipping = $this->order_model->get_order_shipping($order_product->order_id);
                if (!empty($order)) {
                    $to = "";
                    if (!empty($order_shipping)) {
                        $to = $order_shipping->shipping_email;
                    }
                    if (!empty($to)) {
                        $data = array(
                            'subject' => trans("your_order_shipped"),
                            'to' => $to,
                            'template_path' => "email/email_order_shipped",
                            'order' => $order,
                            'order_product' => $order_product
                        );
                        $this->email_model->send_email($data);
                    }
                }
            }
        }
    }

    //send email general
    public function send_email_general()
    {
        $this->load->model("email_model");
        $data = array(
            'template_path' => "email/email_general",
            'to' => $this->input->post('to', true),
            'subject' => $this->input->post('subject', true),
            'email_content' => $this->input->post('email_content', true),
            'email_link' => $this->input->post('email_link', true),
            'email_button_text' => $this->input->post('email_button_text', true)
        );
        $this->email_model->send_email($data);
    }

    //city-town
    public function get_city_town(){
        $city = $this->input->post('state_id', true);
        $towns = $this->location_model->get_towns_tr($city);
        $status = 0;
        $content = '';
        if (!empty($towns)) {
            $status = 1;
            $content = '<option value="">' . trans("town") . '</option>';
            foreach ($cities as $item) {
                $content .= '<option value="' . $item->id . '">' . html_escape($item->ilce) . '</option>';
            }
        }
        $data = array(
            'result' => $status,
            'content' => $content
        );
        echo json_encode($data);
    }
}
