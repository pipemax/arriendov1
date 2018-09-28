<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_Model extends CI_model{

    function Consulta(){
        $this->load->database();
        $consulta = "SELECT * FROM HERRAMIENTA";
        $output = $this->db->query($consulta);
        if($output->num_rows()>0){
            return $output->result();
        }else{
            return FALSE;
        }
    }

}