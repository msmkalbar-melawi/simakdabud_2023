<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class SkpdController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('support');
    }

    /**
     * Get data semua skpd untuk BLUD
     */
    public function blud()
    {
        $skpd = $this->support->skpdBlud('nm_skpd AS nama, kd_skpd AS kode', 'array');

        echo json_encode($skpd);
    }
}
