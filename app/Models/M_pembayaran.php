<?php

namespace App\Models;
use CodeIgniter\Model;

class M_pembayaran extends Model
{
    protected $table = 'pembayaran'; 
    protected $primaryKey = 'id_pembayaran'; 
    protected $allowedFields = ['id_transaksi', 'id_pesanan', 'id_booking', 'jenis_transaksi', 'metode', 'status', 'bukti_pembayaran', 'created_at', 'update_at'];

    public function getAllPembayaran()
    {
        return $this->db->table('pembayaran')
            ->select('pembayaran.*, 
                      IF(pembayaran.id_pesanan IS NOT NULL, "produk", "grooming") AS jenis_transaksi,
                      COALESCE(pembayaran.id_pesanan, pembayaran.id_booking) AS id_transaksi')
            ->orderBy('id_pembayaran', 'DESC')
            ->get()
            ->getResult();
    }

public function getLaporanKeuangan($tanggal_mulai, $tanggal_selesai)
{
    // Query untuk produk dengan kondisi status pesanan 'Selesai'
    $produk = $this->db->table('pembayaran')
        ->select('
            pembayaran.*, 
            pesanan.created_at, 
            pesanan.total_harga, 
            produk.nama_produk AS nama_item
        ')
        ->join('pesanan', 'pesanan.id_pesanan = pembayaran.id_pesanan', 'left')
        ->join('detail_pesanan', 'detail_pesanan.id_pesanan = pesanan.id_pesanan', 'left')
        ->join('produk', 'produk.id_produk = detail_pesanan.id_produk', 'left')
        ->where('pembayaran.jenis_transaksi', 'Produk')
        ->where('pembayaran.created_at >=', $tanggal_mulai)
        ->where('pembayaran.created_at <=', $tanggal_selesai)
        ->where('pesanan.status', 'Selesai') // Menambahkan kondisi status 'Selesai'
        ->get()->getResultArray();

    // Query untuk grooming dengan kondisi status booking grooming 'Selesai'
    $grooming = $this->db->table('pembayaran')
        ->select('
            pembayaran.*, 
            booking_grooming.created_at, 
            booking_grooming.total_harga, 
            layanan_grooming.nama_layanan AS nama_item
        ')
        ->join('booking_grooming', 'booking_grooming.id_booking = pembayaran.id_booking', 'left')
        ->join('detail_grooming', 'detail_grooming.id_booking = booking_grooming.id_booking', 'left')
        ->join('layanan_grooming', 'layanan_grooming.id_layanan = detail_grooming.id_layanan', 'left')
        ->where('pembayaran.jenis_transaksi', 'Grooming')
        ->where('pembayaran.created_at >=', $tanggal_mulai)
        ->where('pembayaran.created_at <=', $tanggal_selesai)
        ->where('booking_grooming.status', 'Selesai') // Menambahkan kondisi status 'Selesai'
        ->get()->getResultArray();

    // Menggabungkan hasil dari produk dan grooming
    return array_merge($produk, $grooming);
}


}
