<?php

namespace App\Models;
use CodeIgniter\Model;

class M_grooming extends Model
{
    protected $table = 'layanan_grooming'; 
    protected $primaryKey = 'id_layanan'; 
    protected $allowedFields = ['id_petugas', 'nama_layanan', 'nama', 'harga', 'durasi', 'deskripsi', 'status', 'created_at', 'updated_at', 'deleted_at'];

    public function getLayananWithPetugas($id = null)
	{
	    $builder = $this->db->table('layanan_grooming')
	        ->select('layanan_grooming.*, petugas.nama')
	        ->join('petugas', 'layanan_grooming.id_petugas = petugas.id_petugas');

	    if ($id !== null) {
	        return $builder->where('layanan_grooming.id_layanan', $id)->get()->getRow();
	    }

	    return $builder->where('layanan_grooming.status', 0)->get()->getResult();
	}

	public function tampil($table)
    {
        return $this->db->table($table)->get()->getResult();
    }

    public function tambah($data)
	{
	    return $this->db->table('layanan_grooming')->insert($data);
	}

	public function softDelete($id)
    {
        return $this->update($id, ['status' => 1]); 
    }

    public function restore($id)
    {
        return $this->update($id, ['status' => 0]); 
    }

    public function getDeletedGrooming()
    {
        return $this->db->table('layanan_grooming')
            ->select('layanan_grooming.*, petugas.nama')
            ->join('petugas', 'layanan_grooming.id_petugas = petugas.id_petugas')
            ->where('layanan_grooming.status', 1) 
            ->get()
            ->getResult();
    }

    public function deletePermanen($id)
    {
        return $this->where('id_layanan', $id)->delete();
    }

    public function tampil1()
    {
        return $this->db->table('layanan_grooming')->get()->getResult(); 
    }


    public function tampil2($cari = null)
    {
        $builder = $this->db->table('layanan_grooming');
        
        if ($cari) {
            $builder->like('nama_layanan', $cari, 'both'); 
        }
        
        return $builder->get()->getResult();
    }

    public function getJadwalHariIni()
    {
        $today = date('Y-m-d');

        return $this->db->table('booking_grooming bg')
            ->select('hp.nama_hewan, lg.nama_layanan, jg.jam_mulai, jg.jam_selesai')
            ->join('hewan_pelanggan hp', 'hp.id_hewan = bg.id_hewan')
            ->join('jadwal_grooming jg', 'jg.id_jadwal = bg.id_jadwal')
            ->join('detail_grooming dg', 'dg.id_booking = bg.id_booking')
            ->join('layanan_grooming lg', 'lg.id_layanan = dg.id_layanan')
            ->where('DATE(bg.tanggal)', $today)
            ->get()
            ->getResult();
    }

    public function countToday()
    {
        return $this->db->table('booking_grooming')
            ->where('DATE(created_at)', date('Y-m-d'))
            ->countAllResults();
    }

    public function getTodaySchedule()
    {
        return $this->db->table('booking_grooming')
            ->where('DATE(created_at)', date('Y-m-d'))
            ->get()
            ->getResult();
    }

    public function countAll()
    {
        return $this->db->table('booking_grooming')->countAllResults();
    }


}