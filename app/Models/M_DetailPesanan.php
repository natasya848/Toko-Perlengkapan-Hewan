<?php

namespace App\Models;
use CodeIgniter\Model;

class M_DetailPesanan extends Model
{
    protected $table = 'detail_pesanan';
    protected $primaryKey = 'id_detail';
    protected $allowedFields = ['id_pesanan', 'id_produk', 'jumlah', 'subtotal'];
}