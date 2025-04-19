<?php

namespace App\Models;
use CodeIgniter\Model;

class M_booking extends Model
{
    protected $table = 'booking_grooming'; 
    protected $primaryKey = 'id_booking'; 
    protected $allowedFields = ['id_pelanggan', 'id_hewan', 'id_layanan', 'id_jadwal', 'tanggal', 'total_harga', 'waktu', 'status', 'create_at', 'update_at'];

   public function getDetailBooking($id_booking)
{
    return $this->db->table('booking_grooming')
        ->select('booking_grooming.*, 
                  pelanggan.nama_pelanggan, 
                  hewan.nama_hewan, 
                  hewan.jenis, 
                  layanan.nama_layanan, 
                  layanan.harga, 
                  layanan.durasi, 
                  layanan.deskripsi, 
                  petugas.nama_petugas,
                  jadwal.jam_mulai, 
                  jadwal.jam_selesai')
        ->join('pelanggan', 'pelanggan.id_pelanggan = booking_grooming.id_pelanggan')
        ->join('hewan', 'hewan.id_hewan = booking_grooming.id_hewan')
        ->join('layanan_grooming layanan', 'layanan.id_layanan = booking_grooming.id_layanan')
        ->join('jadwal_grooming jadwal', 'jadwal.id_jadwal = booking_grooming.id_jadwal')
        ->join('petugas', 'petugas.id_petugas = jadwal.id_petugas')
        ->where('id_booking', $id_booking)
        ->get()->getRow();
}


    public function simpanBookingLengkap($data_booking, $data_detail)
    {
        $this->db->table('booking_grooming')->insert($data_booking);

        $id_booking = $this->db->insertID();

        $data_detail['id_booking'] = $id_booking;

        $this->db->table('detail_grooming')->insert($data_detail);

        return $id_booking;
    }

public function getBookingByUser($id_user)
{
    return $this->db->table('booking_grooming')
        ->select('booking_grooming.*, hewan_pelanggan.nama_hewan, jadwal_grooming.jam_mulai')
        ->join('jadwal_grooming', 'jadwal_grooming.id_jadwal = booking_grooming.id_jadwal')
        ->join('pelanggan', 'pelanggan.id_pelanggan = booking_grooming.id_pelanggan')
        ->join('user', 'user.id_user = pelanggan.id_user')
        ->join('hewan_pelanggan', 'hewan_pelanggan.id_hewan = booking_grooming.id_hewan')
        ->where('user.id_user', $id_user)
        ->orderBy('booking_grooming.created_at', 'DESC')
        ->get()->getResult();
}

public function getLaporanGrooming($tanggal_mulai, $tanggal_selesai, $id_petugas = null)
{
    $builder = $this->db->table('booking_grooming');
    $builder->select('
        booking_grooming.id_booking,
        booking_grooming.tanggal,
        booking_grooming.status,
        pelanggan.nama AS nama_pelanggan,
        hewan_pelanggan.nama_hewan,
        jadwal_grooming.jam_mulai,
        jadwal_grooming.jam_selesai,
        GROUP_CONCAT(layanan_grooming.nama_layanan SEPARATOR ", ") AS layanan,
        SUM(layanan_grooming.harga) AS total_harga
    ');
    $builder->join('detail_grooming', 'detail_grooming.id_booking = booking_grooming.id_booking');
    $builder->join('layanan_grooming', 'layanan_grooming.id_layanan = detail_grooming.id_layanan');
    $builder->join('pelanggan', 'pelanggan.id_pelanggan = booking_grooming.id_pelanggan');
    $builder->join('hewan_pelanggan', 'hewan_pelanggan.id_hewan = booking_grooming.id_hewan');
    $builder->join('jadwal_grooming', 'jadwal_grooming.id_jadwal = booking_grooming.id_jadwal');

    // Tambahkan pengecekan tanggal valid
    if (!empty($tanggal_mulai) && !empty($tanggal_selesai)) {
        $builder->where('booking_grooming.tanggal >=', $tanggal_mulai);
        $builder->where('booking_grooming.tanggal <=', $tanggal_selesai);
    }

    $builder->where('booking_grooming.status', 'Selesai');

    if ($id_petugas) {
        $builder->where('layanan_grooming.id_petugas', $id_petugas);
    }

    $builder->groupBy([
        'booking_grooming.id_booking',
        'booking_grooming.tanggal',
        'booking_grooming.status',
        'pelanggan.nama',
        'hewan_pelanggan.nama_hewan',
        'jadwal_grooming.jam_mulai',
        'jadwal_grooming.jam_selesai'
    ]);

    $query = $builder->get();

    return $query->getResultArray();
}


}
