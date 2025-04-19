<?php

namespace App\Models;
use CodeIgniter\Model;

class M_produk extends Model
{
    protected $table = 'produk'; 
    protected $primaryKey = 'id_produk'; 
    protected $allowedFields = ['kode_produk', 'nama_produk', 'gambar','kategori', 'harga', 'stok', 'deskripsi', 'status', 'create_at', 'update_at', 'delete_at'];

    public function getProduk($kategori = null)
	{
	    $builder = $this->db->table('produk')->where('status', 0);

	    if ($kategori) {
	        $builder->where('kategori', $kategori);
	    }

	    return $builder->get()->getResult();
	}

    public function tampil($table)
    {
        return $this->db->table($table)->get()->getResult();
    }

    public function tambah($data)
	{
	    return $this->db->table('produk')->insert($data);
	}

	public function getProdukById($id)
    {
        return $this->db->table('produk')
            ->select('produk.*')
            ->where('produk.id_produk', $id)
            ->get()
            ->getRow();
    }

    public function getProdukFiltered($kategori = null)
	{
	    $builder = $this->db->table('produk')->where('status', 0);
	    if ($kategori) {
	        $builder->like('kategori', $kategori); 
	    }
	    return $builder->get()->getResult();
	}

	public function softDelete($id)
    {
        return $this->update($id, ['status' => 1]); 
    }

    public function restore($id)
    {
        return $this->update($id, ['status' => 0]); 
    }

    public function getDeletedProduk()
    {
        return $this->db->table('produk')
            ->select('produk.*') 
            ->where('produk.status', 1) 
            ->get()
            ->getResult();
    }

    public function deletePermanen($id)
    {
        return $this->where('id_produk', $id)->delete();
    }

   public function tampil1()
    {
        $builder = $this->db->table('produk');
        $query = $builder->get();

        return $query->getResultArray();
    }

}
    