<?php

namespace App\Models;
use CodeIgniter\Model;

class M_pelanggan extends Model {
    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';
    protected $allowedFields = ['id_user', 'nama', 'email', 'level', 'no_hp', 'alamat', 'created_at', 'updated_at']; 
    
    public function getPelangganById($id_pelanggan)
    {
        return $this->db->table('pelanggan')
            ->select('pelanggan.*, user.email') 
            ->join('user', 'user.id_user = pelanggan.id_user')
            ->where('pelanggan.id_pelanggan', $id_pelanggan)
            ->get()->getRow();
    }

    public function edit($table, $data, $where)
    {
        return $this->db->table($table)
                        ->update($data, $where);
    }

    public function getPelangganWithUser($id)
    {
        return $this->select('pelanggan.*, user.email, user.level')
                    ->join('user', 'user.id_user = pelanggan.id_user')
                    ->where('pelanggan.id_pelanggan', $id)
                    ->get()
                    ->getRow(); 
    }

    public function tampil1()
    {
        return $this->db->table('pelanggan')->get()->getResult(); 
    }
}