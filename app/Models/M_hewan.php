<?php

namespace App\Models;

use CodeIgniter\Model;

class M_hewan extends Model
{
    protected $table            = 'hewan_pelanggan';
    protected $primaryKey       = 'id_hewan';
    protected $allowedFields    = ['id_pelanggan', 'nama_hewan', 'jenis', 'ras', 'usia', 'created_at','updated_at'
    ];

    public function tampil1()
    {
        return $this->db->table('hewan_pelanggan')->get()->getResult(); 
    }

    public function getHewanByPelanggan($id_pelanggan)
    {
        return $this->where('id_pelanggan', $id_pelanggan)->findAll();  
    }

    public function getTotalHewan()
    {
        return $this->countAllResults();
    }



}