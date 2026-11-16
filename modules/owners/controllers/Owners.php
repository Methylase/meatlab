<?php
class Owners extends Trongate {
    private function page($file, $extra = []) {
        $data = $extra + ['auth' => 'login', 'view_module' => 'owners', 'view_file' => $file, 'title' => 'Shop owners | Meatlab'];
        $data['errors'] = $_SESSION['own_err'] ?? ''; unset($_SESSION['own_err']); $this->template('meatlab', $data);
    }
    function login() {

        $this->module('guard');
        $this->module('portal');

        if (empty($_SESSION['owner_id']) && !empty($_COOKIE['ml_remember'])) {
            [$id, $tok] = array_pad(explode(':', $_COOKIE['ml_remember'], 2), 2, '');
            $o = $this->model->get_one_where('id', (int) $id, 'shops');
            if ($o && $o->remember_token && hash_equals($o->remember_token, hash('sha256', $tok))) { $_SESSION['owner_id'] = $o->id; redirect('shops/show/' . $o->slug); }
        }
        
        $user = $this->guard->_user();
        
        if ($user) {
            redirect($this->portal->_home_for($user['role']));
        }
        $data = [
            'csrf'    => $this->guard->_csrf_token(),
            'flash'   => $this->guard->_take_flash(),
            'login'   => $_SESSION['old_login'] ?? '',
        ];
        unset($_SESSION['old_login']);

        $this->page('login', $data);
    }
    /*function submit_login() {
        $this->validation->set_rules('identifier', 'phone or email', 'required');
        $this->validation->set_rules('password', 'password', 'required');
        if ($this->validation->run() !== true) { $_SESSION['own_err'] = validation_errors(); redirect('owners/login'); }
        $id = post('identifier', true);
        $col = filter_var($id, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $o = $this->model->get_one_where($col, $id, 'shops');
        if (!$o || !password_verify(post('password'), $o->password)) { $_SESSION['own_err'] = 'Invalid login details.'; redirect('owners/login'); }
        $_SESSION['owner_id'] = $o->id;
        if (post('remember')) { $tok = bin2hex(random_bytes(32)); $this->model->update($o->id, ['remember_token' => hash('sha256', $tok)], 'shops');
            setcookie('ml_remember', $o->id . ':' . $tok, ['expires' => time() + 2592000, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax']); }
        redirect('shops/show/' . $o->slug);
    }*/
        
    function register() { 
        $this->page('register'); 
    }

    function submit_register() {

        foreach (['owner_name' => 'required', 'shop_name' => 'required', 'phone' => 'required', 'email' => 'required|valid_email_address', 'address' => 'required', 'city' => 'required', 'password' => 'required|min_length[8]'] as $f => $r) $this->validation->set_rules($f, str_replace('_', ' ', $f), $r);
        if ($this->validation->run() !== true) { $_SESSION['own_err'] = validation_errors(); redirect('owners/register'); }
        if ($this->model->get_one_where('email', post('email', true), 'shops') || $this->model->get_one_where('phone', post('phone', true), 'shops')) { $_SESSION['own_err'] = 'Email or phone already registered.'; redirect('owners/register'); }
        $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', post('shop_name', true)), '-')) . '-' . random_int(10, 99);
        $this->model->insert(['owner_name' => post('owner_name', true), 'shop_name' => post('shop_name', true), 'slug' => $slug, 'phone' => post('phone', true), 'email' => post('email', true),
            'address' => post('address', true), 'city' => post('city', true), 'tagline' => 'Fresh meat, delivered', 'password' => $this->_hash_password(post('password'), PASSWORD_DEFAULT)], 'shops');
        redirect('owners/login');
    }
    function forgot() { $this->page('forgot'); }
    function submit_forgot() {
        $o = $this->model->get_one_where('email', post('email', true), 'accounts');
        if ($o) { $tok = bin2hex(random_bytes(32)); $this->model->update($o->id, ['reset_token' => hash('sha256', $tok)], 'accounts');
            // TODO: email BASE_URL.'owners/reset/'.$o->id.'/'.$tok via your mailer (SMTP / Mailgun)
        }
        $_SESSION['own_err'] = 'If that email exists, a reset link has been sent.'; redirect('owners/forgot');
    }

 
    function _hash_password(string $str): string{
        $hashed_string =password_hash($str, PASSWORD_BCRYPT,array('cost'=> 11));
        return $hashed_string;
    }   

    function logout() { unset($_SESSION['owner_id']); setcookie('ml_remember', '', time() - 3600, '/'); redirect(''); }
}
