<?php
class Usuario_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    public function obtener_usuario_por_id($usuario_id) {
        $this->db->where('usuario_id', $usuario_id);
        $query = $this->db->get('usuarios');
        return $query->row();  // Asegúrate de que esto devuelve un objeto stdClass
    }
    

    public function editar_usuario($usuario_id, $data) {
        // Si se proporciona una nueva contraseña, convertirla a MD5
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = md5($data['password']);
        } else {
            unset($data['password']); // Eliminar el campo de contraseña si está vacío
        }
        $this->db->where('usuario_id', $usuario_id);
        return $this->db->update('usuarios', $data);
    }

    public function autenticar($nombre_usuario, $password) {
        $password_md5 = $password;  // Se utiliza MD5 para cifrar la contraseña
        $this->db->where('nombre_usuario', $nombre_usuario);
        $this->db->where('password', $password_md5);
        $query = $this->db->get('usuarios');
        return $query->row_array();
    }

    public function agregar_usuario($data) {
        // Convertir la contraseña a MD5 antes de almacenar
        $data['password'] = md5($data['password']);
        return $this->db->insert('usuarios', $data);
    }

    public function obtener_usuario($usuario_id) {
        $this->db->where('usuario_id', $usuario_id);
        $query = $this->db->get('usuarios');
        return $query->row_array();
    }

    public function eliminar_usuario($usuario_id) {
        $data = ['estado' => FALSE];
        $this->db->where('usuario_id', $usuario_id);
        return $this->db->update('usuarios', $data);
    }

    public function obtener_usuarios_eliminados() {
        $this->db->where('estado', FALSE);
        $query = $this->db->get('usuarios');
        return $query->result_array();
    }

    public function habilitar_usuario($usuario_id) {
        $data = ['estado' => TRUE];
        $this->db->where('usuario_id', $usuario_id);
        return $this->db->update('usuarios', $data);
    }

    public function obtener_usuarios_activos() {
        $this->db->where('estado', TRUE);
        $query = $this->db->get('usuarios');
        return $query->result_array();
    }

    public function email_exists($email) {
        $query = $this->db->get_where('usuarios', ['email' => $email]);
        return $query->num_rows() > 0;
    }
}
?>
