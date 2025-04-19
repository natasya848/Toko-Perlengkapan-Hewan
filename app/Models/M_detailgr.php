<?php

namespace App\Models;
use CodeIgniter\Model;

class M_detailgr extends Model
{
    protected $table = 'detail_grooming';
    protected $primaryKey = 'id_detail';
    protected $allowedFields = ['id_booking', 'id_layanan', 'created_at'];
}
