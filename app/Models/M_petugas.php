<?php

namespace App\Models;
use CodeIgniter\Model;

class M_petugas extends Model
{
    protected $table = 'petugas'; 
    protected $primaryKey = 'id_petugas'; 
    protected $allowedFields = ['id_user', 'nama_petugas','no_hp', 'email', 'alamat', 'status', 'password', 'jenis_kelamin', 'level', 'create_at', 'update_at', 'delete_at'];
    
    public function getPetugasWithUser()
    {
        return $this->db->table('petugas')
            ->select('petugas.*, user.email, user.level')
            ->join('user', 'user.id_user = petugas.id_user')
            ->where('user.level', '3')  
            ->where('petugas.status', 0) 
            ->get()
            ->getResult();
    }

    public function softDelete($id)
    {
        return $this->update($id, ['status' => 1]); 
    }

    public function restore($id)
    {
        return $this->update($id, ['status' => 0]); 
    }

    public function getDeletedPetugas()
    {
        return $this->db->table('petugas')
            ->select('petugas.*, user.email, user.level')
            ->join('user', 'user.id_user = petugas.id_user')
            ->where('user.level', 3)  
            ->where('petugas.status', 1) 
            ->get()
            ->getResult();
    }

    public function deletePermanen($id)
    {
        return $this->where('id_petugas', $id)->delete();
    }

    // public function getTotalPetugas()
    // {
    //     return $this->where('status', 0)->countAllResults();
    // }

    public function tampil($table)
    {
        return $this->db->table($table)->get()->getResult();
    }

    public function tambah($data)
    {
        $db = \Config\Database::connect();

        $userData = [
            'nama'     => $data['nama'], 
            'email' => $data['email'],
            'password' => MD5($data['password']), 
            'level'    => $data['level']
        ];

        if (!$db->table('user')->insert($userData)) {
            log_message('error', 'Gagal insert ke tabel user: ' . json_encode($db->error()));
            return false;
        }

        $id_user = $db->insertID(); 

        $petugasData = [
            'nama'  => $data['nama'],
            'id_user'       => $id_user,
            'alamat'    => $data['alamat'],
            'no_hp'         => $data['no_hp'],
            'jenis_kelamin' => $data['jenis_kelamin']
        ];

        if (!$db->table('petugas')->insert($petugasData)) {
            log_message('error', 'Gagal insert ke tabel petugas: ' . json_encode($db->error()));
            return false;
        }

        return true;
    }

    public function getPetugasById($id)
    {
        return $this->db->table('petugas')
            ->select('petugas.*, user.email, user.level')
            ->join('user', 'user.id_user = petugas.id_user')
            ->where('petugas.id_petugas', $id)
            ->get()
            ->getRow();
    }

    public function updatePetugas($id, $data)
    {
        $this->update($id, [
            'nama' => $data['nama'],
            'alamat' => $data['alamat'],
            'no_hp' => $data['no_hp']
        ]);

        if (!empty($data['email'])) {
            $this->db->table('user')
                ->where('id_user', $data['id_user'])
                ->update(['email' => $data['email']]);
        }

        return true;
    }

    public function getPetugasDetail($id)
    {
        return $this->select('petugas.*, user.email, user.level')
                    ->join('user', 'user.id_user = petugas.id_user', 'left')
                    ->where('petugas.id_petugas', $id)
                    ->first();
    }

    public function tampil1()
    {
        return $this->db->table('petugas')->get()->getResult(); 
    }

    public function getRecentActivities($id_user)
    {
        return $this->db->table('log_activity')
            ->where('id_user', $id_user)
            ->orderBy('waktu', 'DESC')
            ->limit(5)
            ->get()
            ->getResult();
    }

}
