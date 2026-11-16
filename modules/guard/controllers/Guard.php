<?php
/**
 * Guard: shared helpers for both dashboards.
 * Every method starts with "_" so it cannot be reached from a URL.
 */
class Guard extends Trongate {

    public function _boot() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function _user() {
        $this->_boot();
        return $_SESSION['meatlab_user'] ?? null;
    }

    /** Send visitors to login, or to their own dashboard if the role is wrong. */
    public function _require($role) {
        $user = $this->_user();
        if (!$user) {
            redirect('owners/login');
        }
        if ($user['role'] !== $role) {
            redirect($user['role'] === 'admin' ? 'admin' : 'owner');
        }
        return $user;
    }

    public function _csrf_token() {
        $this->_boot();
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf'];
    }

    public function _csrf_check() {
        $this->_boot();
        $sent = $_POST['csrf'] ?? '';
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hash_equals($_SESSION['csrf'] ?? '', $sent)) {
            http_response_code(419);
            die('This page expired. Go back, refresh it and try again.');
        }
    }

    public function _flash($message, $type = 'ok') {
        $this->_boot();
        $_SESSION['flash'] = ['message' => $message, 'type' => $type];
    }

    public function _take_flash() {
        $this->_boot();
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }

    public function _setting($name, $default = '') {
        $rows = $this->model->query_bind('SELECT value FROM settings WHERE name = :n', ['n' => $name], 'object');
        return $rows ? $rows[0]->value : $default;
    }

    /** Save an uploaded image. Returns ['error' => string|null, 'file' => string|null]. */
    public function _save_image($field, $folder) {
        if (empty($_FILES[$field]['name'])) {
            return ['error' => null, 'file' => null];
        }
        $f = $_FILES[$field];
        $max_mb = (int) $this->_setting('max_image_mb', 3);

        if ($f['error'] !== UPLOAD_ERR_OK) {
            return ['error' => 'The image did not upload. Try again.', 'file' => null];
        }
        if ($f['size'] > $max_mb * 1048576) {
            return ['error' => 'The image is larger than ' . $max_mb . 'MB.', 'file' => null];
        }
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
        $types = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        if (!isset($types[$mime]) || !getimagesize($f['tmp_name'])) {
            return ['error' => 'Use a JPG, PNG or WebP image.', 'file' => null];
        }
        $dir = UPLOAD_DIR . $folder . '/';
        if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
            return ['error' => 'The upload folder could not be created.', 'file' => null];
        }
        $name = bin2hex(random_bytes(10)) . '.' . $types[$mime];
        if (!move_uploaded_file($f['tmp_name'], $dir . $name)) {
            return ['error' => 'The image could not be saved.', 'file' => null];
        }
        return ['error' => null, 'file' => $name];
    }

    public function _delete_image($folder, $file) {
        if ($file) {
            $path = UPLOAD_DIR . $folder . '/' . basename($file);
            if (is_file($path)) {
                @unlink($path);
            }
        }
    }

    public function _slug($text) {
        $slug = trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($text)), '-');
        return $slug !== '' ? $slug : 'shop';
    }
}
