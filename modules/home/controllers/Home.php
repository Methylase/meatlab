<?php

class Home extends Trongate {
	/**

	 * Renders the (default) how it works for public access.

	 *

	 * @return void

	 */
    public function index(): void {
        $this->module('guard');
        
        $user = $this->guard->_user();
        
        $data['shops'] = $this->model->query('SELECT * FROM shops ORDER BY RAND() LIMIT 3', 'object');
        $data['title'] = 'Meatlab | Find fresh meat shops near you';
        $data['user'] = $user;
        $data['view_module'] = 'home';
        $data['view_file'] = 'landing';
        $this->template('meatlab', $data);
    }
}
