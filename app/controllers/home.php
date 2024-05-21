<?php

class Home extends Controller {

    public function index() {
      $user = $this->model('User');
      $data = $user->test();

			// NOTE: if you just cloned the repo, after you update your DB_PASS, you will land here. You can do an echo 123; die; and see that you are here! But we are instead going to the home/index file.
			
	    $this->view('home/index');
	    die;
    }

}
