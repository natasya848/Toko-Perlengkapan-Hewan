<?php

namespace App\Models;
use CodeIgniter\Model;

class M_user extends Model {
    protected $table = 'user';
    protected $primaryKey = 'id_user';
    protected $allowedFields = ['nama', 'email', 'password', 'level', 'created_at', 'updated_at', 'deleted_at', 'status', 'foto', 'no_hp']; 
    
    public function getPelangganUser()
	{
    	return $this->db->table('pelanggan')
        	->select('pelanggan.*, user.email, user.level')
        	->join('user', 'user.id_user = pelanggan.id_user')
        	->get()
        	->getResult();
	}

	public function getUser()
    {
        return $this->db->table('user')->where('status', 0)->get()->getResult();
    }

    public function registerPelanggan($dataUser, $dataPelanggan)
    {
        $db = \Config\Database::connect();
        
        if (!$db->table('user')->insert($dataUser)) {
            die("Error insert user: " . print_r($db->error(), true));
        }
        $id_user = $db->insertID(); 

        if (!$id_user) {
            die("Error: ID user tidak ditemukan setelah insert");
        }

        $dataPelanggan['id_user'] = $id_user;
        
        if (!$db->table('pelanggan')->insert($dataPelanggan)) {
            die("Error insert pelanggan: " . print_r($db->error(), true));
        }

        return $id_user;
    }

    public function getUserById($id)
    {
        return $this->db->table('user')
                        ->where('id_user', $id)
                        ->get()
                        ->getRow(); 
    }

    public function softDelete($id)
    {
        return $this->update($id, ['status' => 1]);
    }

    public function restore($id)
    {
        return $this->update($id, ['status' => 0]);
    }

    public function deletePermanen($id)
    {
        return $this->where('id_user', $id)->delete();
    }

    public function getDeletedUser()
    {
        return $this->db->table('user')->where('status', 1)->get()->getResult();
    }

    public function edit($table, $data, $where)
    {
        return $this->db->table($table)
                        ->update($data, $where);
    }

     public function updateAlamat($id_user, $alamat)
    {
        return $this->update($id_user, ['alamat' => $alamat]);
    }

    public function countPelanggan()
    {
        return $this->where('level', '2')->countAllResults();
    }

}