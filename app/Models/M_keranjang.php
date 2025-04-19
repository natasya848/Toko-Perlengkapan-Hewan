<?php

namespace App\Models;
use CodeIgniter\Model;

class M_keranjang extends Model
{
    protected $table = 'keranjang';
    protected $primaryKey = 'id_keranjang';
    protected $allowedFields = ['id_user', 'id_produk', 'id_layanan', 'jumlah'];

   public function tambahKeKeranjang($id_user, $id_produk, $id_layanan, $jumlah)
    {
        if ($id_produk) {
            $produk = $this->db->table('produk')->where('id_produk', $id_produk)->get()->getRowArray();
            if (!$produk) {
                return false; 
            }
        }

        if ($id_layanan) {
            $layanan = $this->db->table('layanan_grooming')->where('id_layanan', $id_layanan)->get()->getRowArray();
            if (!$layanan) {
                return false; 
            }
        }

        $data = [
            'id_user' => $id_user,
            'id_produk' => $id_produk ?: null, 
            'id_layanan' => $id_layanan ?: null, 
            'jumlah' => $jumlah,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $this->db->table('keranjang')->insert($data);
        return true;
    }

    public function updateJumlahKeranjang($id_keranjang, $jumlah)
    {
        $data = [
            'jumlah' => $jumlah,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->db->table('keranjang')->where('id_keranjang', $id_keranjang)->update($data);
    }

   public function getKeranjangByUser($id_user)
    {
        $produk = $this->db->table('keranjang')
            ->select('keranjang.*, produk.nama_produk AS nama, produk.harga')
            ->join('produk', 'produk.id_produk = keranjang.id_produk')
            ->where('keranjang.id_user', $id_user)
            ->where('keranjang.id_produk IS NOT NULL')
            ->get()
            ->getResultArray();

        $layanan = $this->db->table('keranjang')
            ->select('keranjang.*, layanan_grooming.nama_layanan AS nama, layanan_grooming.harga')
            ->join('layanan_grooming', 'layanan_grooming.id_layanan = keranjang.id_layanan')
            ->where('keranjang.id_user', $id_user)
            ->where('keranjang.id_layanan IS NOT NULL')
            ->get()
            ->getResultArray();

        return [
            'produk' => $produk,
            'layanan' => $layanan
        ];
    }

    public function hapusDariKeranjang($id_keranjang)
    {
        return $this->delete($id_keranjang);
    }

   public function hapusItemKeranjang($userId, $id_produk = null, $id_layanan = null)
{
    $builder = $this->where('id_user', $userId);

    if ($id_produk !== null) {
        $builder->where('id_produk', $id_produk);
    } elseif ($id_layanan !== null) {
        $builder->where('id_layanan', $id_layanan);
    } else {
        // Jangan hapus apa-apa kalau dua-duanya null
        return false;
    }

    return $builder->delete();
}

public function getKeranjangProduk($id_user)
{
    return $this->select('keranjang.*, produk.nama_produk AS nama, produk.harga')
                ->join('produk', 'produk.id_produk = keranjang.id_produk')
                ->where('keranjang.id_user', $id_user)
                ->asObject() // biar hasilnya object, bukan array
                ->findAll();
}

   public function getKeranjangLayanan($id_user)
{
    return $this->db->table('keranjang k')
        ->select('k.*, l.nama_layanan, l.harga')
        ->join('layanan_grooming l', 'l.id_layanan = k.id_layanan')
        ->where('k.id_user', $id_user)
        ->where('k.id_layanan IS NOT NULL', null, false)
        ->get()->getResultArray();
}

    public function checkoutProduk($id_user)
    {
        return $this->where('id_user', $id_user)
                    ->where('id_produk IS NOT NULL', null, false)
                    ->delete();
    }

    public function checkoutLayanan($id_user)
    {
        return $this->where('id_user', $id_user)
                    ->where('id_layanan IS NOT NULL', null, false)
                    ->delete();
    }
}


