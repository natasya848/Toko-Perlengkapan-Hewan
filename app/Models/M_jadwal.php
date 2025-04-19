<?php

namespace App\Models;
use CodeIgniter\Model;

class M_jadwal extends Model
{
    protected $table = 'jadwal_grooming'; 
    protected $primaryKey = 'id_jadwal'; 
    protected $allowedFields = ['hari', 'jam_mulai', 'jam_selesai', 'status', 'statusr', 'create_at', 'update_at'];

    public function tampil1()
    {
        return $this->db->table('jadwal_grooming')->get()->getResult(); 
    }

    public function getJadwal()
    {
        return $this->db->table('jadwal_grooming')
            ->select('jadwal_grooming.*')
            ->where('jadwal_grooming.statusr', 0) 
            ->get()
            ->getResult();
    }

    public function getJadwalById($id)
    {
        return $this->db->table('jadwal_grooming')
            ->select('jadwal_grooming.*')
            ->where('jadwal_grooming.id_jadwal', $id)
            ->get()
            ->getRow();
    }

    Public function softDelete($id)
    {
        return $this->update($id, ['statusr' => 1]); 
    }

    public function restore($id)
    {
        return $this->update($id, ['statusr' => 0]); 
    }

    public function getDeletedJadwal()
    {
        return $this->db->table('jadwal_grooming')
            ->select('jadwal_grooming.*')
            ->where('jadwal_grooming.statusr', 1) 
            ->get()
            ->getResult();
    }

    public function deletePermanen($id)
    {
        return $this->where('id_jadwal', $id)->delete();
    }

    public function get_data_where($where)
{
    return $this->where($where)->findAll();
}



}