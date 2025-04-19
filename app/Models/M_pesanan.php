<?php

namespace App\Models;
use CodeIgniter\Model;

class M_Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';
    protected $allowedFields = ['id_pelanggan', 'id_user', 'total_harga', 'status', 'created_at', 'updated_at', 'jenis_pengiriman', 'biaya_pengiriman'];

    public function getPesananByUser($id_user)
    {
        $builder = $this->db->table('pesanan')
                    ->join('pelanggan', 'pelanggan.id_pelanggan = pesanan.id_pelanggan')
                    ->where('pelanggan.id_user', $id_user)
                    ->orderBy('pesanan.created_at', 'DESC');
        
        $query = $builder->get(); 
        if (!$query) {
            log_message('error', 'Query gagal: ' . $this->db->error());
            return [];
        }
        
        return $query->getResult(); 
    }

    public function countAll()
    {
        return $this->db->table('pesanan')->countAllResults();
    }


}