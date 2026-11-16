<?php
class Trongate_security extends Trongate {

    /**
     * Ensures the user is allowed access for the specified scenario.
     *
     * @param string $scenario The scenario for access control. Default is 'admin panel'.
     * @param array $params (Optional) Additional parameters for more granular control.
     * @return mixed Returns a Trongate token, a boolean, or null.
     */
    public function _make_sure_allowed(string $scenario = 'admin panel', array $params = []): mixed {
        // Returns either a Trongate token, a boolean, or null.

        switch ($scenario) {
            case 'admin':
                $this->module('users');
                $token = $this->users->_make_sure_allow_admin();
                break;
            case 'founder':
                $this->module('users');
                $token = $this->users->_make_sure_allow_founder();
                break;                
            case 'national team':
                $this->module('users');
                $token = $this->users->_make_sure_allow_national_team();
                break;
             case 'manager':
                 $this->module('users');
                 $token = $this->users->_make_sure_allow_manager();
                 break;
             case 'coordinator':
                 $this->module('users');
                 $token = $this->users->_make_sure_allow_coordinator();
                 break;                 
             case 'agent':
                 $this->module('users');
                 $token = $this->users->_make_sure_allow_agent();
                 break;                 
            default:
                $this->module('trongate_administrators');
                $token = $this->trongate_administrators->_make_sure_allowed($scenario, $params);
                break;
        }

        return $token;
    }

}