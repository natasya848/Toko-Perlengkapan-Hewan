<?php

namespace App\Models;
use CodeIgniter\Model;

class M_pengiriman extends Model
{
    protected $table = 'pengiriman';
    protected $primaryKey = 'id_pengiriman';
    protected $allowedFields = ['id_pesanan','biaya', 'status', 'tanggal_status', 'nama', 'subtotal'];
    protected $useTimestamps = false;

    public function getPengirimanLengkap()
    {
        $builder = $this->db->table('pengiriman p')
        ->join('pesanan ps', 'ps.id_pesanan = p.id_pesanan')
        ->join('pelanggan pl', 'pl.id_pelanggan = ps.id_pelanggan')
        ->join('detail_pesanan dp', 'dp.id_pesanan = ps.id_pesanan')
        ->join('produk pr', 'pr.id_produk = dp.id_produk')
        ->select('p.id_pengiriman, pl.nama AS nama_pelanggan, 
                  GROUP_CONCAT(pr.nama_produk SEPARATOR ", ") AS daftar_produk, 
                  (ps.total_harga - 10000) AS total_produk,
                  ps.biaya_pengiriman, 
                  ps.total_harga AS total_bayar, 
                  p.status')
        ->where('p.id_pesanan IS NOT NULL')
        ->orderBy('p.id_pengiriman', 'DESC')
        ->groupBy('p.id_pengiriman');

        $query = $builder->get();

                if (!$query) {
                    echo '<pre>';
                    print_r($this->db->showLastQuery()); 
                    echo "\nError: ";
                    print_r($this->db->error());
                    echo '</pre>';
                    die(); 
                }

        return $query->getResult();
    }

	public function createPengiriman($data)
    {
        return $this->insert($data);
    }

    public function getPengirimanByUser($id_user)
    {
        $subquery = '(SELECT id_pesanan, SUM(subtotal) AS total_produk FROM detail_pesanan GROUP BY id_pesanan) dp';

        return $this->db->table('pengiriman')
            ->select('
                pengiriman.*,
                pesanan.total_harga,
                pesanan.created_at,
                pesanan.biaya_pengiriman,
                dp.total_produk
            ')
            ->join('pesanan', 'pesanan.id_pesanan = pengiriman.id_pesanan')
            ->join('pelanggan', 'pelanggan.id_pelanggan = pesanan.id_pelanggan')
            ->join($subquery, 'dp.id_pesanan = pesanan.id_pesanan', 'left') 
            ->where('pelanggan.id_user', $id_user)
            ->orderBy('pengiriman.id_pengiriman', 'DESC')
            ->get()
            ->getResult();
    }

    public function getProdukByPesanan($id_pesanan)
    {
        return $this->db->table('detail_pesanan')
            ->select('detail_pesanan.*, produk.nama_produk, produk.harga')
            ->join('produk', 'produk.id_produk = detail_pesanan.id_produk')
            ->where('detail_pesanan.id_pesanan', $id_pesanan)
            ->get()
            ->getResult();
    }

}
