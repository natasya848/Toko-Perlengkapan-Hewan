<?php

namespace App\Models;
use CodeIgniter\Model;

class M_log extends Model {
	protected $table = 'log_activity';
    protected $primaryKey = 'id_log';
    protected $allowedFields = ['id_user', 'username', 'aktivitas', 'ip_address', 'waktu'];

    public function saveLog($id_user, $aktivitas, $ip_address) {
        return $this->insert([
            'id_user'   => $id_user,
            'aktivitas' => $aktivitas,
            'ip_address'=> $ip_address
        ]);
    }

    public function getLogs() 
    {
        $this->db->select('log_activity.*, user.username, user.nama AS nama_user');
        $this->db->from('log_activity');
        $this->db->join('user', 'user.id_user = log_activity.id_user');
        $this->db->order_by('waktu', 'DESC');
        return $this->findAll();
    }

    public function getAllLogs()
    {
        $query = $this->db->table('log_activity')
            ->select('log_activity.*, user.nama as nama_user, user.username')
            ->join('user', 'user.id_user = log_activity.id_user', 'LEFT') 
            ->orderBy('waktu', 'DESC')
            ->get();

        if (!$query) {
            return [];
        }

        return $query->getResultArray(); 
    }

    public function getLogsByUser($id_user)
    {
        return $this->where('id_user', $id_user)->findAll();
    }

}