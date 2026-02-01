<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Partials extends CI_Controller {

  public function view($view)
  {
      $this->load->view('partials/'.$view);
  }
}