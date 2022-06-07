<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . "third_party/swiftmailer/vendor/autoload.php";
require APPPATH . "third_party/phpmailer/vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email_model extends CI_Model
{
    //send text email
    public function send_test_email($email, $subject, $message)
    {
        if (!empty($email)) {
            $data = array(
                'subject' => $subject,
                'message' => $message,
                'to' => $email,
                'template_path' => "email/email_newsletter",
                'subscriber' => "",
            );
            return $this->send_email($data);
        }
    }

    //send email activation
    public function send_email_activation($user_id)
    {
        $user_id = clean_number($user_id);
        $user = $this->auth_model->get_user($user_id);
        if (!empty($user)) {
            $token = $user->token;
            //check token
            if (empty($token)) {
                $token = generate_token();
                $data = array(
                    'token' => $token
                );
                $this->db->where('id', $user->id);
                $this->db->update('users', $data);
            }

            $data = array(
                'subject' => trans("confirm_your_account"),
                'to' => $user->email,
                'template_path' => "email/email_activation",
                'token' => $token
            );

            $this->send_email($data);
        }
    }

    //send email reset password
    public function send_email_reset_password($user_id)
    {
        $user_id = clean_number($user_id);
        $user = $this->auth_model->get_user($user_id);
        if (!empty($user)) {
            $token = $user->token;
            //check token
            if (empty($token)) {
                $token = generate_token();
                $data = array(
                    'token' => $token
                );
                $this->db->where('id', $user->id);
                $this->db->update('users', $data);
            }
 
            $data = array(
                'subject' => trans("reset_password"),
                'to' => $user->email,
                'template_path' => "email/email_reset_password",
                'token' => $token
            );

            $this->send_email($data);
        }
    }

    //send email newsletter
    public function send_email_newsletter($subscriber, $subject, $message)
    {
        if (!empty($subscriber)) {
            if (empty($subscriber->token)) {
                $this->newsletter_model->update_subscriber_token($subscriber->email);
                $subscriber = $this->newsletter_model->get_subscriber($subscriber->email);
            }
            $data = array(
                'subject' => $subject,
                'message' => $message,
                'to' => $subscriber->email,
                'template_path' => "email/email_newsletter",
                'subscriber' => $subscriber,
            );
            return $this->send_email($data);
        }
    }

    //send email
    public function send_email($data)
    {
        $protocol = "smtp";
       
        $encryption = "tls";
        
        
        return $this->send_email_php_mailer($protocol, $encryption, $data);
        
    }

    //send email with swift mailer
    public function send_email_swift($encryption, $data)
    {
        try {
            // Create the Transport
            $transport = (new Swift_SmtpTransport($this->general_settings->mail_host, $this->general_settings->mail_port, $encryption))
                ->setUsername($this->general_settings->mail_username)
                ->setPassword($this->general_settings->mail_password);

            // Create the Mailer using your created Transport
            $mailer = new Swift_Mailer($transport);

            // Create a message
            $message = (new Swift_Message("AYL HOME"))
                ->setFrom(array("reset_password@aylhome.com" => "AYL HOME"))
                ->setTo([$data['to'] => ''])
                ->setSubject($data['subject'])
                ->setBody($this->load->view($data['template_path'], $data, TRUE), 'text/html');

            //Send the message
            $result = $mailer->send($message);
            if ($result) {
                return true;
            }
        } catch (\Swift_TransportException $Ste) {
            $this->session->set_flashdata('error', $Ste->getMessage());
            return false;
        } catch (\Swift_RfcComplianceException $Ste) {
            $this->session->set_flashdata('error', $Ste->getMessage());
            return false;
        }
    }

    //send email with php mailer
    public function send_email_php_mailer($protocol, $encryption, $data)
    {
        $mail = new PHPMailer(true);
        try {
            if ($protocol == "mail") {
                $mail->isMail();
                $mail->setFrom("info@aylhome.com", "AYL HOME");
                $mail->addAddress($data['to']);
                $mail->isHTML(true);
                $mail->Subject = $data['subject'];
                $mail->Body = $this->load->view($data['template_path'], $data, TRUE, 'text/html');
            } else {
                $mail->isSMTP();
                $mail->Host = "stilgar.wlsrv.com";
                $mail->SMTPAuth = true;
                $mail->Username = "info@aylhome.com";
                $mail->Password = "GI,c$}hsmE[b";
                $mail->SMTPSecure = $encryption;
                $mail->CharSet = 'UTF-8';
                $mail->Port = 465;
                $mail->setFrom("info@aylhome.com", "AYL HOME");
                $mail->addAddress($data['to']);
                $mail->isHTML(true);
                $mail->Subject = $data['subject'];
                $mail->Body = $this->load->view($data['template_path'], $data, TRUE, 'text/html');
            }
            $mail->send();
            return true;
        } catch (Exception $e) {
            $this->session->set_flashdata('error', $mail->ErrorInfo);
            return false;
        }
        return false;
    }
}
