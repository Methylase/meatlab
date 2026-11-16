<?php
/**
 * Portal: one login for admins and shop owners.
 * After login, admins go to /admin and shop owners go to /owner.
 *
 * Already have a login page? Keep it and, after a successful password check,
 * call $this->module('portal')->_start_session($account_row) then
 * redirect($this->module('portal')->_home_for($account_row->role)).
 */
class Portal extends Trongate {

    /*public function index() {
        redirect('portal/login');
    }

    public function login() {
        $this->module('guard');
        
        $user = $this->guard->_user();
        
        if ($user) {
            redirect($this->_home_for($user['role']));
        }
        
        $data = [
            'csrf'    => $this->guard->_csrf_token(),
            'flash'   => $this->guard->_take_flash(),
            'login'   => $_SESSION['old_login'] ?? '',
        ];
        unset($_SESSION['old_login']);
        $this->view('login', $data);
    }*/

    public function submit_login() {
        $this->module('guard');
        $this->guard->_csrf_check();

        // Allow 5 tries per 10 minutes per browser session.
        $now = time();
        $tries = array_filter($_SESSION['login_tries'] ?? [], fn($t) => $t > $now - 600);
        if (count($tries) >= 5) {
            $this->guard->_flash('Too many attempts. Wait 10 minutes and try again.', 'err');
            redirect('owners/login');
        }

        $login = trim($_POST['login'] ?? '');
        $password = $_POST['password'] ?? '';
        $_SESSION['old_login'] = $login;

        $account = $this->_find_account($login);
       
        if ($account && $account->is_active != 1 || !$this->_password_verify($password, $account->password_hash)) {
            $tries[] = $now;
            $_SESSION['login_tries'] = $tries;
            $this->guard->_flash('Phone, email or password is wrong.', 'err');
            redirect('owners/login');
        }

        $this->_start_session($account);
        redirect($this->_home_for($account->role));
    }

    public function logout() {
        $this->module('guard');
        $this->guard->_boot();
        $_SESSION = [];
        session_destroy();
        redirect('owners/login');
    }

    public function _start_session($account) {
        $this->module('guard');
        $this->guard->_boot();
        session_regenerate_id(true);
        unset($_SESSION['login_tries'], $_SESSION['old_login']);
        $_SESSION['meatlab_user'] = [
            'id'   => (int) $account->id,
            'role' => $account->role,
            'name' => $account->name,
        ];
    }

    public function _home_for($role) {
        return $role === 'admin' ? 'admin' : 'owner';
    }

    /** Looks up by email or phone. Point this at your own owners table if it differs. */
    private function _find_account($login) {
        if ($login === '') {
            return null;
        }
        $phone = preg_replace('/\D+/', '', $login);
        $rows = $this->model->query_bind(
            'SELECT * FROM accounts WHERE email = :e OR (phone <> \'\' AND phone = :p) LIMIT 1',
            ['e' => $login, 'p' => $phone],
            'object'
        );
        return $rows ? $rows[0] : null;
    }

    function _password_verify(string $plain_text, string $hashed_string): bool {
        return password_verify($plain_text, $hashed_string);
    }      
}
