<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contacts extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('email');
    }

    public function index()
    {
        $head = array();
        $data = array();
        if (isset($_POST['message'])) {
            $result = $this->sendEmail();
            if ($result) {
                $this->session->set_flashdata('resultSend', 'Email is sent!');
            } else {
                $this->session->set_flashdata('resultSend', 'Email send error!');
            }
            redirect('contacts');
        }
        $data['googleMaps'] = $this->Home_admin_model->getValueStore('googleMaps');
        $data['googleApi'] = $this->Home_admin_model->getValueStore('googleApi');
        $arrSeo = $this->Public_model->getSeo('contacts');
        $head['title'] = @$arrSeo['title'];
        $head['description'] = @$arrSeo['description'];
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $this->render('contacts', $head, $data);
    }

    private function sendEmail()
    {

        $config = array(
                 'protocol'  => 'ssmtp',
                'smtp_host' => '103.212.121.55',
                'smtp_port' => 587,
                'smtp_crypto'=>'tls',
                'smtp_user' => 'test@flywithflyby.com',
                'smtp_pass' => 'test@007',
                'mailtype'  => 'html',
                'charset'   => 'utf-8'
            );

        $message =  "
                        <html>
                        <head>
                            <title>Verification Code</title>
                        </head>
                        <body>
                            <h2>Hello Admin !!</h2>
                            <p>Your Account:</p>
                            <p>Email: ".$name."</p>
                            <p>Email: ".$email."</p>
                            <p>Email: ".$subject."</p>
                            <p>Email: ".$message."</p>
                            
                        </body>
                        </html>
                        ";


        $myEmail = $this->Home_admin_model->getValueStore('contactsEmailTo');
        if (filter_var($myEmail, FILTER_VALIDATE_EMAIL) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $this->load->library('email', $config);

            $this->email->from($config['smtp_user']);
            //$this->email->to($myEmail);
            $this->email->to('shuklaji88as@gmail.com');

            $this->email->subject($_POST['subject']);
            $this->email->message($message);

            $this->email->send();
            return true;
        }
        return false;
    }

}
