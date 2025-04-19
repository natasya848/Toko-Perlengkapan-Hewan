<?php

namespace App\Controllers;

use App\Models\M_pelanggan;
use App\Models\M_user;
use App\Models\M_petugas;
use App\Models\M_produk;
use App\Models\M_grooming;
use App\Models\M_log;
use App\Models\M_pesanan;
use App\Models\M_DetailPesanan;
use App\Models\M_jadwal;
use App\Models\M_booking;
use App\Models\M_hewan;
use App\Models\M_pembayaran;
use App\Models\M_keranjang;
use App\Models\M_detailgr;
use App\Models\M_setting;
use App\Models\M_pengiriman;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Home extends BaseController
{   
    protected function logActivity($aktivitas) 
   {
        $M_log = new M_log();

        $id_user = session()->get('id_user');
        if (!$id_user) return;

        $ip_address = $this->request->getIPAddress();

        $M_log->saveLog($id_user, $aktivitas, $ip_address);
    }

    public function log()
    {
        $this->logActivity("Mengakses Riwayat Aktivitas");

        $session = session();
        $id_user = $session->get('id_user'); 
        $level = $session->get('level');
        $logModel = new M_log();

        if ($level == 1 || $level == 4) {
            $logs = $logModel->getAllLogs();
        } elseif ($level == 2 || $level == 3) {
            $logs = $logModel->where('log_activity.id_user', $id_user)
                            ->join('user', 'user.id_user = log_activity.id_user', 'LEFT')
                            ->select('log_activity.*, user.nama AS nama_user, user.username')
                            ->orderBy('waktu', 'DESC')
                            ->findAll();
        } else {
            return redirect()->to('/home')->with('error', 'Anda tidak memiliki akses!');
        }

        if (!$logs) {
            return "Query gagal atau tidak ada data log.";
        }

        return view('log_act', ['logs' => $logs]);
    }   

    public function login()
    {
        return view('login');
    }

    public function logout()
    {   
        $this->logActivity("Logout dari sistem");
        
        session()->destroy();
            return redirect()->to('home/login');
    }

    public function aksi_login()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('pswd');
        $recaptcha_response = $this->request->getPost('g-recaptcha-response');
        $math_answer = $this->request->getPost('math_answer');
        $correct_answer = $this->request->getPost('correct_answer');

        $connected = @fsockopen("www.google.com", 80);
        
        if ($connected) {
            fclose($connected);

            $recaptcha_secret = "6LcNvAsrAAAAAI8Nvq9CniGxm_wZyonH5OrjuJCc"; 
            $verify_url = "https://www.google.com/recaptcha/api/siteverify";
            $response = file_get_contents($verify_url . "?secret=" . $recaptcha_secret . "&response=" . $recaptcha_response);
            $response_keys = json_decode($response, true);

            if (!$response_keys["success"]) {
                echo "reCAPTCHA verification failed. Please try again.";
                exit();
            }
        } else {
            if ($math_answer === null || $correct_answer === null || (int)$math_answer !== (int)$correct_answer) {
                echo "Verifikasi matematika salah. Coba lagi.";
                exit();
            }
        }

        $M_user = new \App\Models\M_user(); 
        $user = $M_user->where('email', $email)->get()->getRowArray();

        if (!$user) {
            echo "Username tidak ditemukan!";
            exit();
        }

        if (md5($password) !== $user['password']) {
            echo "Password salah!";
            exit();
        }   

        $M_pelanggan = new \App\Models\M_pelanggan();
        $pelanggan = null;

        if ($user['level'] == 2) {
            $pelanggan = $M_pelanggan->where('id_user', $username['id_user'])->get()->getRowArray();
        }

        $id_pelanggan = $pelanggan ? $pelanggan['id_pelanggan'] : null;

        session()->regenerate();
        $sessionData = [
            'id_user' => (int) $user['id_user'],
            'email' => $user['email'],
            'level' => (int) $user['level'],
            'id_pelanggan' => (int) $id_pelanggan,
            'isLoggedIn' => true,
            'last_activity' => time()
        ];
        session()->set($sessionData);

        $this->logActivity("Login ke sistem");

        return redirect()->to('home/dashboard');
    }

    public function register()
    {
        return view ('register');
    }

    public function aksi_register()
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'email'           => 'required|is_unique[user.email]',
            'password'        => 'required|min_length[1]',
            'nama'            => 'required',
            'no_hp'           => 'required|numeric'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $M_user = new M_user();

        $dataUser = [
            'nama'     => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'password' => md5($this->request->getPost('password')),
            'level'    => '2' 
        ];

        $dataPelanggan = [
            'nama'   => $this->request->getPost('nama'),
            'no_hp'  => $this->request->getPost('no_hp')
        ];

        $M_user->registerPelanggan($dataUser, $dataPelanggan);

        return redirect()->to(base_url('home/login'))->with('success', 'Registrasi berhasil! Silakan login.');
    }

   public function dashboard()
    {
        $this->logActivity("Mengakses Dashboard");

        $M_produk = new M_produk();
        $M_grooming = new M_grooming();
        $M_pesanan = new M_pesanan();
        $M_petugas = new M_petugas();
        $M_hewan = new M_hewan();
        $M_user = new M_user();

        $produk = $M_produk->tampil1();
        $jadwalHariIni = $M_grooming->getJadwalHariIni();

        $data = [
            'title' => 'Dashboard', 
            'showWelcome' => true,
            'produk' => $produk,
            'layanan' => $M_grooming->tampil1(),
            'totalPesanan' => $M_pesanan->countAll(),
            'totalGrooming' => $M_grooming->countAll(),
            'totalPelanggan' => $M_user->countPelanggan(),
            'totalHewan' => $M_hewan->getTotalHewan(),
            'jadwalHariIni' => $jadwalHariIni,
            'aktivitasTerakhir' => $M_petugas->getRecentActivities(session()->get('id_user')),
        ];

        switch (session()->get('level')) {
            case 1: 
            case 4:
                return view('dashadmin', $data);
            case 3: 
                return view('dashpetugas', $data);
            default:
                return view('dashboard', $data);
        }
    }


    public function meowgic()
    {
        $M_produk = new M_produk();
        $M_grooming = new M_grooming();

        $produk = $M_produk->tampil1();
        $data = [
            'produk' => $produk,
            'layanan' => $M_grooming->tampil1()
        ];

        return view('dashboard', $data);
    }
    
    public function tampilproduk()
    {
        $this->logActivity("Mengakses Halaman Produk");

        // $session = session();
        // if (!$session->get('isLoggedIn')) {
        //     return redirect()->to('home/login'); 
        // }

        $kategori = $this->request->getGet('kategori');
        $cari = $this->request->getGet('cari');

        $produk = new \App\Models\M_produk();

        if ($kategori) {
            $produk->where('kategori', $kategori);
        }

        if ($cari) {
            $produk->like('nama_produk', $cari);
        }

        $data = [
            'produk' => $produk->findAll(), 
            'kategori_selected' => $kategori,
            'cari' => $cari
        ];

        return view('tmpproduk', $data);
    }

    public function tampilgrooming()
    {
        $this->logActivity("Mengakses Halaman Layanan Grooming");

        // $session = session();
        // if (!$session->get('isLoggedIn')) {
        //     return redirect()->to('home/login'); 
        // }

        $cari = $this->request->getGet('cari');  

        $grooming = new \App\Models\M_grooming();  

        $data = [
            'grooming' => $grooming->tampil2($cari), 
            'cari' => $cari  
        ];

        return view('tmpgrooming', $data);  
    }

    public function tambahKeKeranjang()
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('home/login');
        }

        $id_user = $session->get('id_user');
        $id_produk = $this->request->getPost('id_produk');
        $id_layanan = $this->request->getPost('id_layanan');
        $jumlah = (int)$this->request->getPost('jumlah');

        if (!$id_produk && !$id_layanan) {
            return redirect()->back()->with('error', 'Harap pilih produk atau layanan terlebih dahulu.');
        }

        $keranjangModel = new M_keranjang();

        $this->logActivity("Menambahkan item ke keranjang: " .
            "Produk ID = " . ($id_produk ?: '-') . ", " .
            "Layanan ID = " . ($id_layanan ?: '-') . ", " .
            "Jumlah = $jumlah");

        if ($keranjangModel->tambahKeKeranjang($id_user, $id_produk, $id_layanan, $jumlah)) {
            return redirect()->back()->with('success', 'Item berhasil ditambahkan ke keranjang.');
        } else {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan item ke keranjang.');
        }
    }

    public function tampilKeranjang()
    {
        $this->logActivity("Mengakses Keranjang");

        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('home/login');
        }

        $id_user = $session->get('id_user');
        $keranjangModel = new M_keranjang();
        $keranjang = $keranjangModel->getKeranjangByUser($id_user);

        $produk = $keranjang['produk'] ?? [];
        $layanan = $keranjang['layanan'] ?? [];

        $totalProduk = 0;
        foreach ($produk as $item) {
            $totalProduk += $item['jumlah'] * $item['harga'];
        }

        $totalLayanan = 0;
        foreach ($layanan as $item) {
            $totalLayanan += $item['harga'];
        }

        $data = [
            'keranjang' => $keranjang,
            'totalProduk' => $totalProduk,
            'totalLayanan' => $totalLayanan
        ];

        return view('keranjang', $data);
    }

    public function hapusDariKeranjang($id_keranjang)
    {
        $keranjangModel = new M_keranjang();
        $keranjangModel->hapusDariKeranjang($id_keranjang);

         $this->logActivity("Menghapus item dari keranjang: ID Keranjang = $id_keranjang");

        return redirect()->to('home/tampilKeranjang')->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function updateJumlahProduk()
    {
        $id_keranjang = $this->request->getPost('id_keranjang');
        $jumlah = $this->request->getPost('jumlah');
        $action = $this->request->getPost('action');

        if ($jumlah < 1) {
            return redirect()->back()->with('error', 'Jumlah produk tidak bisa kurang dari 1.');
        }

        if ($action === 'minus') {
            $jumlah--;
        }

        if ($action === 'plus') {
            $jumlah++;
        }

        $keranjangModel = new M_keranjang();
        $keranjangModel->updateJumlahKeranjang($id_keranjang, $jumlah);

        $this->logActivity("Memperbarui jumlah item keranjang: ID Keranjang = $id_keranjang, Jumlah baru = $jumlah");

        return redirect()->back()->with('success', 'Jumlah produk berhasil diperbarui.');
    }

    public function checkoutProduk()
    {
        $userId = session()->get('id_user');
        $keranjangModel = new M_keranjang();
        $produk = $keranjangModel->getKeranjangProduk($userId);

        if (empty($produk)) {
            return redirect()->to('home/keranjang')->with('error', 'Tidak ada produk untuk di-checkout.');
        }

        return redirect()->to('home/formCheckout')->with('success', 'Produk berhasil di-checkout. Silakan lanjutkan ke pembayaran.');
    }

    public function formCheckout() 
    {
        $id_user = session()->get('id_user');

        $keranjangModel = new M_keranjang();
        $pelangganModel = new M_Pelanggan();

        $data['produk'] = $keranjangModel->getKeranjangProduk($id_user);
        $data['pelanggan'] = $pelangganModel->where('id_user', $id_user)->first();

        if (empty($data['produk'])) {
            log_message('error', 'Produk Kosong untuk user ID: ' . $id_user);
            return redirect()->to('home/keranjang')->with('error', 'Produk kosong.');
        }

        $this->logActivity("Mengakses form checkout produk oleh user ID: $id_user");

        return view('form_co', $data);
    }

    public function prosesCheckout()
    {
        $id_user = session()->get('id_user');
        $keranjangModel = new M_keranjang();
        $pesananModel = new M_Pesanan();
        $detailModel = new M_DetailPesanan();
        $pelangganModel = new M_Pelanggan();
        $pembayaranModel = new M_Pembayaran(); 

        $keranjang = $keranjangModel->getKeranjangProduk($id_user);
        $pelanggan = $pelangganModel->where('id_user', $id_user)->first();

        if (empty($keranjang) || !$pelanggan) {
            return redirect()->to('home/keranjang')->with('error', 'Keranjang kosong atau data pelanggan tidak ditemukan.');
        }

        $alamat = $this->request->getPost('alamat');
        $metode_pembayaran = $this->request->getPost('metode');
        $jenis_pengiriman = $this->request->getPost('jenis_pengiriman');
        $biaya_pengiriman = (int) $this->request->getPost('biaya_pengiriman');

        if ($alamat !== $pelanggan->alamat) {
            $pelangganModel->update($pelanggan->id_pelanggan, ['alamat' => $alamat]);
        }

        $jumlahProduk = $this->request->getPost('jumlah');
        $total_produk = 0;

        foreach ($keranjang as $key => $item) {
            $jumlah = isset($jumlahProduk[$key]) ? $jumlahProduk[$key] : $item->jumlah;
            $total_produk += $item->harga * $jumlah;
        }

        $total_harga = $total_produk + $biaya_pengiriman;

        $pesananModel->insert([
            'id_pelanggan' => $pelanggan['id_pelanggan'],
            'tanggal' => date('Y-m-d'),
            'total_harga' => $total_harga,
            'status' => 'Menunggu Konfirmasi',
            'metode' => $metode_pembayaran,
            'jenis_pengiriman' => $jenis_pengiriman,
            'biaya_pengiriman' => $biaya_pengiriman
        ]);

        $id_pesanan = $pesananModel->getInsertID();

        foreach ($keranjang as $key => $item) {
            $jumlah = isset($jumlahProduk[$key]) ? $jumlahProduk[$key] : $item->jumlah;
            $detailModel->insert([
                'id_pesanan' => $id_pesanan,
                'id_produk' => $item->id_produk,
                'jumlah' => $jumlah,
                'subtotal' => $item->harga * $jumlah
            ]);
        }

        foreach ($keranjang as $item) {
            $keranjangModel->hapusItemKeranjang($id_user, $item->id_produk, null);
        }

        $status_pembayaran = 'Menunggu Konfirmasi';
        $buktiPembayaran = $this->request->getFile('bukti_pembayaran');
        $namaFile = null;

        if ($metode_pembayaran === 'Transfer Bank') {
            if ($buktiPembayaran && $buktiPembayaran->isValid()) {
                $namaFile = $buktiPembayaran->getRandomName();
                $buktiPembayaran->move('uploads/bukti', $namaFile);
            }
        } elseif ($metode_pembayaran === 'Cash') {
            $status_pembayaran = 'COD';
        }

        $pembayaranModel->insert([
            'id_pesanan' => $id_pesanan,
            'id_booking' => null,
            'id_transaksi' => null,
            'jenis_transaksi' => 'Produk',
            'metode' => $metode_pembayaran,
            'status' => $status_pembayaran,
            'bukti_pembayaran' => $namaFile,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->logActivity("Checkout dilakukan. ID Pesanan: $id_pesanan, Total Harga: Rp" . number_format($total_harga, 0, ',', '.'));

        return redirect()->to('home/meowgic')->with('success', 'Checkout berhasil! Silakan lanjutkan pembayaran.');
    }

    public function pesan($id_produk)
    {
        $userId = session()->get('id_user');
        $produkModel = new M_Produk();
        $keranjangModel = new M_Keranjang();

        $produk = $produkModel->find($id_produk);
        if (!$produk) {
            return redirect()->to('home')->with('error', 'Produk tidak ditemukan.');
        }

        $keranjangModel->insert([
            'id_user' => $userId,
            'id_produk' => $produk['id_produk'],
            'jumlah' => 1, 
            'harga' => $produk['harga'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->logActivity("Mengakses form checkout produk oleh user ID: $id_user");

        return redirect()->to('home/formCheckout')->with('success', 'Produk telah ditambahkan ke keranjang. Lanjutkan ke checkout.');
    }

   public function riwayat()
    {
        $this->logActivity("Mengakses Halaman Riwayat Pesanan");

        $id_user = session()->get('id_user');

        $pesananModel = new M_pesanan();
        $pesanan = $pesananModel->getPesananByUser($id_user);

        $groomingModel = new M_booking();
        $grooming = $groomingModel->getBookingByUser($id_user);

        return view('riwayat', [
            'pesanan' => $pesanan,
            'grooming' => $grooming
        ]);
    }

    public function checkoutLayanan()
    {
        $userId = session()->get('id_user');
        $keranjangModel = new M_keranjang();
        $pelangganModel = new M_pelanggan();
        $hewanModel = new M_hewan();
        $jadwalModel = new M_jadwal();

        $this->jadwalModel = new M_jadwal(); 
        $pelanggan = $pelangganModel->where('id_user', $userId)->first();
        $id_pelanggan = $pelanggan['id_pelanggan'];

        $hewan = $hewanModel->getHewanByPelanggan($id_pelanggan);

        $keranjang = $keranjangModel->getKeranjangLayanan($userId);

        if (empty($keranjang)) {
            return redirect()->to('home/keranjang')->with('error', 'Keranjang layanan kosong.');
        }

        $total = 0;
        foreach ($keranjang as $item) {
            $total += $item['harga'];
        }

        $jadwal = $jadwalModel->getJadwal();

        $this->logActivity("Mengakses form checkout layanan oleh user ID: $id_user");

        return view('form_cogr', [
            'id_pelanggan' => $id_pelanggan,
            'pelanggan'    => $pelanggan,  
            'hewan'        => $hewan,      
            'keranjang'    => $keranjang,  
            'total_harga'  => $total,      
            'jadwal'       => $jadwal     
        ]);
    }

    public function simpanHewan()
    {
        $data = [
            'id_pelanggan' => $this->request->getPost('id_pelanggan'),
            'nama_hewan'   => $this->request->getPost('nama_hewan'),
            'jenis'        => $this->request->getPost('jenis'),
            'ras'          => $this->request->getPost('ras'),
            'usia'         => $this->request->getPost('usia'),
        ];

        $hewanModel = new M_hewan(); 
        $hewanModel->insert($data);  

        $this->logActivity("Menambahkan data hewan");

        return redirect()->to('home/checkoutLayanan')->with('success', 'Hewan berhasil disimpan.');
    }

    public function prosesCheckoutLayanan()
    {
        $userId = session()->get('id_user');
        
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Silakan login dulu.');
        }

        $keranjangModel = new M_keranjang();
        $bookingModel = new M_booking();
        $detailGroomingModel = new M_detailgr();

        $keranjang = $keranjangModel->getKeranjangLayanan($userId);

        if (empty($keranjang)) {
            return redirect()->to('home/keranjang')->with('error', 'Keranjang kosong.');
        }

        $total_harga = 0;
        foreach ($keranjang as $item) {
            echo 'Harga: ' . $item['harga'] . '<br>';  
            $total_harga += floatval($item['harga']); 
        }

           $bookingData = [
            'id_pelanggan' => $this->request->getPost('id_pelanggan'),
            'id_hewan'     => $this->request->getPost('id_hewan'),
            'id_jadwal'    => $this->request->getPost('id_jadwal'),
            'tanggal'      => $this->request->getPost('tanggal'),
            'status'       => 'Menunggu Konfirmasi',
            'total_harga'  => $total_harga,
        ];

        $bookingModel->insert($bookingData);
        $id_booking = $bookingModel->getInsertID();

        foreach ($keranjang as $item) {
            $detailGroomingModel->insert([
                'id_booking' => $id_booking,
                'id_layanan' => $item['id_layanan'],
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        foreach ($keranjang as $item) {
            $keranjangModel->hapusItemKeranjang($userId, null, $item['id_layanan']);
        }

        $this->logActivity("Checkout dilakukan. ID booking: $id_booking, Total Harga: Rp" . number_format($total_harga, 0, ',', '.'));

        return redirect()->to('home/dashboard')->with('success', 'Booking layanan berhasil.');
    }

    public function pesangr($id_layanan)
    {
        $userId = session()->get('id_user');

        if (!$userId) {
            return redirect()->to('home/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $layananModel = new M_grooming();
        $layanan = $layananModel->find($id_layanan);

        if (!$layanan) {
            return redirect()->back()->with('error', 'Layanan tidak ditemukan.');
        }

        $keranjangModel = new M_keranjang();
        $keranjangModel->insert([
            'id_user' => $userId,
            'id_produk' => null, 
            'id_layanan' => $id_layanan,
            'jumlah' => 1,
            'harga' => $layanan['harga'],
        ]);

        $this->logActivity("Mengakses form checkout layanan oleh user ID: $id_user");

        return redirect()->to('home/checkoutLayanan')->with('success', 'Layanan berhasil ditambahkan ke keranjang.');
    }


    public function pelanggan()
    {
        $this->logActivity("Mengakses Tabel Pelanggan");

        if (!session()->has('id_user')) {
            return redirect()->to('home/login');
        }

        $M_user = new M_user();
        $id_user = session()->get('id_user');
        $level = session()->get('level');

        if ($level == 1 || $level == 3 || $level == 4) {
            $data = [
                'title' => 'Data Pelanggan',
                'pelanggan' => $M_user->getPelangganUser(),
                'showWelcome' => false 
            ];

        // } elseif ($level == 2) {
        //     $data = [
        //         'title' => 'Profil Saya',
        //         'pelanggan' => $M_user->getPenumpangByUserId($id_user),
        //         'showWelcome' => true
        //     ];

        } else {
            return redirect()->to('home/dashboard');
        }

        return view('pelanggan', $data);
    }

    public function edit_pelanggan($id)
    {
        $M_pelanggan = new M_pelanggan();
        $pelanggan = $M_pelanggan->getPelangganById($id);

        if (!$pelanggan) {
            return redirect()->to(base_url('home/pelanggan'))->with('error', 'Data pelanggan tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Data Pelanggan',
            'pelanggan' => $pelanggan, 
        ];

        return view('edit1', $data);
    }

    public function edit1($id)
    {
        $M_pelanggan = new M_pelanggan();
        $existingData = $M_pelanggan->find($id);

        if (!$existingData) {
            return redirect()->to(base_url('home/pelanggan'))->with('error', 'Data pelanggan tidak ditemukan.');
        }

        $newData = [
            'nama'   => $this->request->getPost('nama'),
            'alamat' => $this->request->getPost('alamat'),
            'no_hp'  => $this->request->getPost('no_hp'),
        ];

        $changes = [];
        foreach ($newData as $key => $value) {
            if ($existingData[$key] != $value) {
                $changes[] = ucfirst(str_replace('_', ' ', $key)) . " dari '" . $existingData[$key] . "' ke '" . $value . "'";
            }
        }

        $where = ['id_pelanggan' => $id];
        $M_pelanggan->edit('pelanggan', $newData, $where);

        if (!empty($changes)) {
            $logMessage = "Mengedit Pelanggan ID $id - " . implode(', ', $changes);
            $this->logActivity($logMessage);
        }

        return redirect()->to(base_url('home/pelanggan'))->with('success', 'Data berhasil diperbarui.');
    }

    public function detail_pelanggan($id)
    {
        $session = session();
        $user_id = $session->get('id_user'); 
        $user_level = $session->get('level'); 
        $ip_address = $_SERVER['REMOTE_ADDR']; 

        $logModel = new M_log();
        $M_pelanggan = new M_pelanggan();

        $logModel->saveLog($user_id, "User ID {$user_id} mencoba mengakses detail pelanggan ID {$id}", $ip_address);

        if ($user_level != 4) {
            $logModel->saveLog($user_id, "User ID {$user_id} tidak memiliki izin untuk melihat detail pelanggan ID {$id}", $ip_address);
            return redirect()->to(base_url('home/penumpang'))->with('error', 'Anda tidak memiliki izin.');
        }

        $pelanggan = $M_pelanggan->getPelangganWithUser($id);

        if (!$pelanggan) {
            $logModel->saveLog($user_id, "User ID {$user_id} tidak menemukan data pelanggan ID {$id}", $ip_address);
            return redirect()->to(base_url('home/pelanggan'))->with('error', 'Data pelanggan tidak ditemukan.');
        }

        $logModel->saveLog($user_id, "User ID {$user_id} berhasil melihat detail pelanggan ID {$id}", $ip_address);

        $data = [
            'title' => 'Detail Pelanggan',
            'pelanggan' => $pelanggan
        ];

        return view('detailpg', $data);
    }

    public function lihat_hewan($id_pelanggan)
    {
        $this->logActivity("Mengakses Tabel Hewan Pelanggan");

        $db = \Config\Database::connect();
        $hewan = $db->table('hewan_pelanggan')
            ->where('id_pelanggan', $id_pelanggan)
            ->get()
            ->getResult();

        $data = [
            'title' => 'Daftar Hewan Pelanggan',
            'hewan' => $hewan
        ];

        return view('hewan_pelanggan', $data);
    }

    public function petugas()
    {
        $this->logActivity("Mengakses Tabel Petugas");

        if (!session()->has('id_user')) { 
            return redirect()->to('home/login');
        }

        if (!in_array(session()->get('level'), [1, 4])) {
            return redirect()->to('home/dashboard'); 
        }

        $M_petugas = new M_petugas();
        $data = [
            'title' => 'Data Petugas',
            'petugas' => $M_petugas->getPetugasWithUser(), 
            'deleted_petugas' => $M_petugas->getDeletedPetugas(),
            'showWelcome' => false
        ];

        return view('petugas', $data);
    }

    public function petugas1()
    {
        $this->logActivity("Mengakses Tabel Data Petugas yang Dihapus");

        if (!session()->has('id_user')) { 
            return redirect()->to('home/login');
        }

        if (!in_array(session()->get('level'), [1, 4])) {
            return redirect()->to('home/dashboard'); 
        }

        $M_petugas = new M_petugas();
        $data = [
            'title' => 'Data Petugas yang Dihapus',
            'deleted_petugas' => $M_petugas->getDeletedPetugas(),
            'showWelcome' => false
        ];

        return view('petugas1', $data);
    }

    public function hapusP($id)
    {
        $M_petugas = new M_petugas();
        $result = $M_petugas->deletePermanen($id);

        if ($result) {
            $this->logActivity("Menghapus permanen petugas dengan ID: $id");

            return redirect()->to('home/petugas1')->with('success', 'Petugas berhasil dihapus secara permanen');
        } else {
            return redirect()->to('home/petugas1')->with('error', 'Petugas tidak ditemukan atau gagal dihapus');
        }
    }

    public function deletePetugas($id)
    {
        $M_petugas = new M_petugas();
        $result = $M_petugas->softDelete($id);

        if ($result) {
            $this->logActivity("Menghapus petugas dengan ID: $id (soft delete)");

            return redirect()->to('home/petugas')->with('success', 'Petugas berhasil dihapus (soft delete)');
        } else {
            return redirect()->to('home/petugas')->with('error', 'Petugas tidak ditemukan');
        }
    }

    public function restorePetugas($id)
    {
        $M_petugas = new M_petugas();
        $result = $M_petugas->restore($id);

        if ($result) {
            $this->logActivity("Mengembalikan petugas dengan ID: $id (soft delete)");

            return redirect()->to('home/petugas')->with('success', 'Petugas berhasil direstore');
        } else {
            return redirect()->to('home/petugas')->with('error', 'Petugas tidak ditemukan');
        }
    }

    public function edit_petugas($id)
    {
        $M_petugas = new M_petugas();
        $petugas = $M_petugas->getPetugasById($id);

        if (!$petugas) {
            return redirect()->to(base_url('home/petugas'))->with('error', 'Data petugas tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Data Petugas',
            'petugas' => $petugas,
        ];

        return view('edit2', $data);
    }

    public function edit2($id)
    {
        $M_petugas = new M_petugas();
        $M_user = new M_user(); 
        // $modelLog = new M_log(); 
        $petugas = $M_petugas->find($id);

        if (!$petugas) {
            return redirect()->to(base_url('home/petugas'))->with('error', 'Petugas tidak ditemukan.');
        }

        $dataPetugas = [
            'nama' => $this->request->getPost('nama_petugas'),
            'alamat'   => $this->request->getPost('alamat'),
            'no_hp'        => $this->request->getPost('no_hp'),
        ];

        $changes = [];
        foreach ($dataPetugas as $key => $value) {
        if ($petugas[$key] != $value) {
            $changes[] = ucfirst(str_replace('_', ' ', $key)) . " dari '" . $petugas[$key] . "' ke '" . $value . "'";
            }
        }

        $M_petugas->update($id, $dataPetugas);
        $dataUser = ['email' => $this->request->getPost('email')];
        $M_user->update($petugas['id_user'], $dataUser);

        if ($petugas['username'] != $dataUser['username']) {
            $changes[] = "Username dari '" . $petugas['username'] . "' ke '" . $dataUser['username'] . "'";
        }

        if (!empty($changes)) {
            $aktivitas = "Mengedit Data Petugas ID $id - " . implode(', ', $changes);
            $this->logActivity($aktivitas);
        }

        return redirect()->to(base_url('home/petugas'))->with('success', 'Data petugas berhasil diperbarui.');
    }

    public function input_petugas()
    {
        $M_petugas = new M_petugas(); 

        $data = [
            'title'   => 'Input Petugas',
            'petugas' => $M_petugas->tampil('petugas'),
        ];

        return view('tpetugas', $data);
    }

    public function tpetugas()
    {
        $M_petugas = new M_petugas();

        $data = [
            'nama'          => $this->request->getPost('nama'),
            'email'         => $this->request->getPost('email'),
            'password'      => $this->request->getPost('password'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'no_hp'         => $this->request->getPost('no_hp'),
            'alamat'        => $this->request->getPost('alamat'),
            'level'         => '3'
        ];

        log_message('debug', 'Data yang dikirim ke tambah(): ' . print_r($data, true));

        if ($M_petugas->tambah($data)) {
            $logMessage = "Menambahkan Petugas: " . $data['nama_petugas'] . 
                      " (Username: " . $data['username'] . ")";
            $this->logActivity($logMessage);

            return redirect()->to(base_url('home/petugas'))->with('success', 'Petugas berhasil ditambahkan!');
        // } else {
            // return redirect()->to(base_url('home/input_pt'))->with('error', 'Gagal menambahkan petugas.');
        } 
    }

    public function detail_petugas($id)
    {
        $session = session();
        $user_id = $session->get('id_user'); 
        $user_level = $session->get('level'); 

        $logModel = new \App\Models\M_log();
        $M_petugas = new M_petugas();

        $logModel->saveLog($user_id, "id_user={$user_id} mencoba mengakses detail petugas ID {$id}", $ip_address);

        if ($user_level != 4) {
            $logModel->saveLog($user_id, "id_user={$user_id} tidak memiliki izin untuk melihat petugas ID {$id}", $ip_address);
            return redirect()->to(base_url('home/petugas'))->with('error', 'Anda tidak memiliki izin.');
        }

        $petugas = $M_petugas->getPetugasDetail($id);
        if (!$petugas) {
            $logModel->saveLog($user_id, "id_user={$user_id} tidak menemukan data petugas ID {$id}", $ip_address);
            return redirect()->to(base_url('home/petugas'))->with('error', 'Data petugas tidak ditemukan.');
        }

        $logModel->saveLog($user_id, "id_user={$user_id} berhasil melihat detail petugas ID {$id}", $ip_address);

        $data = [
            'title' => 'Detail Petugas',
            'petugas' => $petugas
        ];

        return view('detailpt', $data);
    }

    public function user()
    {
        $this->logActivity("Mengakses Tabel User");

        if (!session()->has('id_user')) { 
            return redirect()->to('home/login');
        }

        if (!in_array(session()->get('level'), [1, 4])) {
            return redirect()->to('home/dashboard'); 
        }

        $M_user = new M_user(); 
        $data = [
            'title' => 'Data User',
            'showWelcome' => false, 
            'user' => $M_user->getUser(),
        ];

        return view('user', $data);
    }

    public function detail_user($id)
    {
        $session = session();
        $user_id = $session->get('id_user'); 
        $user_level = $session->get('level'); 

        $logModel = new \App\Models\M_log();
        $M_user = new M_user();

        $logModel->saveLog($user_id, "id_user={$user_id} mencoba mengakses detail user ID {$id}", $ip_address);

        if (!in_array($user_level, [1, 4])) {
            $logModel->saveLog($user_id, "id_user={$user_id} tidak memiliki izin untuk melihat user ID {$id}", $ip_address);
            return redirect()->to(base_url('home/user'))->with('error', 'Anda tidak memiliki izin.');
        }

        $user = $M_user->getUserById($id);

        if (!$user) {
            $logModel->saveLog($user_id, "id_user={$user_id} tidak menemukan data user ID {$id}", $ip_address);
            return redirect()->to(base_url('home/user'))->with('error', 'Data user tidak ditemukan.');
        }

        $logModel->saveLog($user_id, "id_user={$user_id} berhasil melihat detail user ID {$id}", $ip_address);

        $data = [
            'title' => 'Detail User',
            'user' => $user
        ];

        return view('detailu', $data);
    }

    public function edit_user($id)
    {   
        $M_user = new M_user();
        $wece = ['id_user' => $id];
    
        $data = [
            "password" => md5('1111') 
        ];

        $M_user->edit('user', $data, $wece);

        $db = \Config\Database::connect();
        $user = $db->table('user')->where('id_user', $id)->get()->getRowArray();

        if ($user) {
            $this->logActivity("Reset password untuk user: " . $user['email']);
        } else {
            $this->logActivity("Reset password gagal, user dengan ID $id tidak ditemukan.");
        }

        return redirect()->to('home/user');
    }

    public function user1()
    {
        $this->logActivity("Mengakses Tabel Data User yang Dihapus");

        if (!session()->has('id_user')) { 
            return redirect()->to('home/login');
        }

        if (!in_array(session()->get('level'), [1, 4])) {
            return redirect()->to('home/dashboard'); 
        }

        $M_user = new M_user();
        $data = [
            'title' => 'Data User yang Dihapus',
            'deleted_user' => $M_user->getDeletedUser(),
            'showWelcome' => false 
        ];

        return view('user1', $data);
    }

    public function hapusU($id)
    {
        $M_user = new M_user();
        if ($M_user->deletePermanen($id)) {
            $this->logActivity("Menghapus permanen user ID: $id");

            return redirect()->to('home/user1')->with('success', 'User berhasil dihapus secara permanen');
        }
        return redirect()->to('home/user1')->with('error', 'User tidak ditemukan atau gagal dihapus');
    }

    public function deleteU($id)
    {
        $M_user = new M_user();
        if ($M_user->softDelete($id)) {

            $this->logActivity("Menghapus user ID: $id (soft delete)");

            return redirect()->to('home/user1')->with('success', 'User berhasil dihapus (soft delete)');
        }
        return redirect()->to('home/user')->with('error', 'User tidak ditemukan atau gagal dihapus');
    }

    public function restoreU($id)
    {
        $M_user = new M_user();

        if ($M_user->restore($id)) {
            $this->logActivity("Mengembalikan user ID: $id (soft delete)");
            return redirect()->to('home/user')->with('success', 'User berhasil direstore');
        }
        return redirect()->to('home/user1')->with('error', 'User tidak ditemukan');
    }

    public function produk()
    {
        $this->logActivity("Mengakses Tabel Produk");

        if (!session()->has('id_user')) { 
            return redirect()->to('home/login');
        }

        if (!in_array(session()->get('level'), [1, 3, 4])) {
            return redirect()->to('home/dashboard'); 
        }

        $M_produk = new M_produk();

        $kategori = $this->request->getGet('kategori');

        if ($kategori) {
            $produk = $M_produk->getProdukFiltered($kategori);
        } else {
            $produk = $M_produk->getProduk(); 
        }

        $data = [
            'title' => 'Data Produk',
            'showWelcome' => false, 
            'produk' => $produk,
            'kategori_selected' => $kategori,
        ];

        return view('produk', $data);
    }

    public function input_produk()
    {
        $M_produk = new M_produk(); 

        $data = [
            'title'   => 'Input Produk',
            'produk' => $M_produk->tampil('produk'),
        ];

        return view('tproduk', $data);
    }

    public function tproduk()
    {
        $M_produk = new M_produk(); 

        $fileGambar = $this->request->getFile('gambar');

        if ($fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $namaGambar = $fileGambar->getRandomName();

        $fileGambar->move('uploads/produk', $namaGambar);
        } else {
            $namaGambar = null; 
        }

        $data = [
            'gambar'          => $namaGambar,
            'kode_produk'     => $this->request->getPost('kode_produk'),
            'nama_produk'     => $this->request->getPost('nama_produk'),
            'kategori'        => $this->request->getPost('kategori'),
            'harga'           => $this->request->getPost('harga'),
            'stok'            => $this->request->getPost('stok'),
            'deskripsi'       => $this->request->getPost('deskripsi'),
        ];

        
        if ($M_produk->tambah($data)) {
            $logMessage = "Menambahkan Produk: " . $data['kategori'] . 
                      " (Nama Produk: " . $data['nama_produk'] . ")";
            $this->logActivity($logMessage);

            return redirect()->to(base_url('home/produk'))->with('success', 'Produk berhasil ditambahkan!');
        } 
    }

    public function edit_produk($id)
    {
        $M_produk = new M_produk();
        $produk = $M_produk->getProdukById($id);

        if (!$produk) {
            return redirect()->to(base_url('home/produk'))->with('error', 'Data produk tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Data Produk',
            'produk' => $produk,
        ];

        return view('edit3', $data);
    }

    public function edit3($id)
    {
        $M_produk = new M_produk();
        $modelLog = new M_log(); 
        $produk = $M_produk->find($id);

        if (!$produk) {
            return redirect()->to(base_url('home/produk'))->with('error', 'Produk tidak ditemukan.');
        }

        $fileGambar = $this->request->getFile('gambar');

        if ($fileGambar && $fileGambar->isValid() && !$fileGambar->hasMoved()) {
            if ($produk->gambar && file_exists('uploads/produk/' . $produk->gambar)) {
                unlink('uploads/produk/' . $produk->gambar);
            }

            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move('uploads/produk', $namaGambar);
        } else {
            $namaGambar = $this->request->getPost('gambar_lama');

        }

        $dataProduk = [
            'gambar'          => $namaGambar,
            'kode_produk'     => $this->request->getPost('kode_produk'),
            'nama_produk'     => $this->request->getPost('nama_produk'),
            'kategori'        => $this->request->getPost('kategori'),
            'harga' => str_replace('.', '', $this->request->getPost('harga')),
            'stok'            => $this->request->getPost('stok'),
            'deskripsi'       => $this->request->getPost('deskripsi'),
        ];

        $changes = [];
        foreach ($dataProduk as $key => $value) {
            if ($produk->$key != $value) {
                $changes[] = ucfirst(str_replace('_', ' ', $key)) . " dari '" . $produk->$key . "' ke '" . $value . "'";
            }
        }

        $M_produk->update($id, $dataProduk);

        if (!empty($changes)) {
            $aktivitas = "Mengedit Data Produk ID $id - " . implode(', ', $changes);
            $this->logActivity($aktivitas);
        }

        return redirect()->to(base_url('home/produk'))->with('success', 'Data produk berhasil diperbarui.');
    }

    public function detail_produk($id)
    {

        $session = session();
        $user_id = $session->get('id_user'); 
        $user_level = $session->get('level'); 

        $logModel = new \App\Models\M_log();
        $M_produk = new M_produk();

        $produk = $M_produk->getProdukById($id);

        $data = [
            'title' => 'Detail Produk',
            'produk' => $produk
        ];

        $logModel->saveLog($user_id, "id_user={$user_id} berhasil melihat detail produk ID {$id}", $ip_address);

        return view('detailproduk', $data);
    }

    public function produk1()
    {
        $this->logActivity("Mengakses Tabel Data Produk yang Dihapus");

        if (!session()->has('id_user')) { 
            return redirect()->to('home/login');
        }

        if (!in_array(session()->get('level'), [1, 4])) {
            return redirect()->to('home/dashboard'); 
        }

        $M_produk = new M_produk();
        $data = [
            'title' => 'Data Produk yang Dihapus',
            'deleted_produk' => $M_produk->getDeletedProduk(),
            'showWelcome' => false 
        ];

        return view('produk1', $data);
    }

    public function hapusProduk($id)
    {
        $M_produk = new M_produk();
        if ($M_produk->deletePermanen($id)) {
            $this->logActivity("Menghapus permanen produk ID: $id");

            return redirect()->to('home/produk1')->with('success', 'Produk berhasil dihapus secara permanen');
        }
        return redirect()->to('home/produk1')->with('error', 'Produk tidak ditemukan atau gagal dihapus');
    }

    public function deleteProduk($id)
    {
        $M_produk = new M_produk();
        if ($M_produk->softDelete($id)) {

            $this->logActivity("Menghapus produk ID: $id (soft delete)");

            return redirect()->to('home/produk1')->with('success', 'Produk berhasil dihapus (soft delete)');
        }
        return redirect()->to('home/produk')->with('error', 'Produk tidak ditemukan atau gagal dihapus');
    }

    public function restoreProduk($id)
    {
        $M_produk = new M_produk();

        if ($M_produk->restore($id)) {
            $this->logActivity("Mengembalikan user ID: $id (soft delete)");
            return redirect()->to('home/produk')->with('success', 'Produk berhasil direstore');
        }
        return redirect()->to('home/produk1')->with('error', 'Produk tidak ditemukan');
    }

    public function grooming()
    {
        $this->logActivity("Mengakses Tabel Layanan Grooming");

        if (!session()->has('id_user')) { 
            return redirect()->to('home/login');
        }

        if (!in_array(session()->get('level'), [1, 3, 4])) {
            return redirect()->to('home/dashboard'); 
        }

        $M_grooming = new M_grooming(); 
        $data = [
            'title' => 'Data Layanan Grooming',
            'showWelcome' => false, 
            'layanan_grooming' => $M_grooming->getLayananWithPetugas(),
        ];

        return view('grooming', $data);
    }

    public function input_layanan()
    {
        $M_grooming = new M_grooming(); 
        $M_petugas = new M_petugas(); 

        $data = [
            'title'   => 'Input Layanan Grooming',
            'layanan_grooming' => $M_grooming->tampil('layanan_grooming'),
            'petugas' => $M_petugas->tampil1(), 
        ];

        return view('tgrooming', $data);
    }

    public function tgrooming()
    {
        $M_grooming = new M_grooming(); 

        $data = [
            'nama_layanan'     => $this->request->getPost('nama_layanan'),
            'harga'            => $this->request->getPost('harga'),
            'durasi'           => $this->request->getPost('durasi'),
            'deskripsi'        => $this->request->getPost('deskripsi'),
            'id_petugas'       => $this->request->getPost('id_petugas'),
        ];


        if ($M_grooming->tambah($data)) {
            $logMessage = "Menambahkan Layanan Grooming: "  . 
                      " (Nama Layanan: " . $data['nama_layanan'] . ")";
            $this->logActivity($logMessage);

            return redirect()->to(base_url('home/grooming'))->with('success', 'Layanan Grooming berhasil ditambahkan!');
        } 
    }

    public function edit_grooming($id)
    {
        $M_grooming = new M_grooming();
        $layanan_grooming = $M_grooming->getLayananWithPetugas($id);

        if (!$layanan_grooming) {
            return redirect()->to(base_url('home/grooming'))->with('error', 'Data layanan tidak ditemukan.');
        }

        $M_petugas = new M_petugas(); 
        $data = [
            'title'   => 'Edit Layanan Grooming',
            'layanan_grooming' => $layanan_grooming,
            'petugas' => $M_petugas->tampil1()
        ];

        return view('edit4', $data);
    }

    public function edit4($id)
    {
        $M_grooming = new M_grooming();
        $modelLog = new M_log(); 
        $layanan_grooming = $M_grooming->getLayananWithPetugas($id);

        if (!$layanan_grooming) {
            return redirect()->to(base_url('home/grooming'))->with('error', 'Layanan tidak ditemukan.');
        }

        $dataLayanan = [
            'nama_layanan' => $this->request->getPost('nama_layanan'),
            'harga'        => str_replace('.', '', $this->request->getPost('harga')),
            'durasi'       => $this->request->getPost('durasi'),
            'deskripsi'    => $this->request->getPost('deskripsi'),
            'id_petugas'   => $this->request->getPost('id_petugas'),
        ];

        $changes = [];
        foreach ($dataLayanan as $key => $value) {
            if ($layanan_grooming->$key != $value) {
                $changes[] = ucfirst(str_replace('_', ' ', $key)) . " dari '" . $layanan_grooming->$key . "' ke '" . $value . "'";
            }
        }

        $M_grooming->update($id, $dataLayanan);
        
        if (!empty($changes)) {
                $aktivitas = "Mengedit Data Layanan Grooming ID $id - " . implode(', ', $changes);
                $this->logActivity($aktivitas);
            }

        return redirect()->to(base_url('home/grooming'))->with('success', 'Data layanan berhasil diperbarui.');
    }
    
    public function detail_grooming($id)
    {
        $this->logActivity("Mengakses Tabel Detail Grooming");
        $session = session();
        $user_id = $session->get('id_user'); 
        $user_level = $session->get('level'); 

        $logModel = new \App\Models\M_log();
        $M_grooming = new M_grooming();
        $M_petugas = new M_petugas(); 
        $petugas = $M_petugas->tampil1();

        $layanan_grooming = $M_grooming->getLayananWithPetugas($id);
       
        $logModel->saveLog($user_id, "id_user={$user_id} berhasil melihat detail layanan grooming ID {$id}", $ip_address);

        $data = [
            'title' => 'Detail Layanan Grooming',
            'layanan_grooming' => $layanan_grooming
        ];

        return view('detaillayanan', $data);
    }

    public function grooming1()
    {
        $this->logActivity("Mengakses Tabel Layanan Grooming yang Dihapus");

        if (!session()->has('id_user')) { 
            return redirect()->to('home/login');
        }

        if (!in_array(session()->get('level'), [1, 4])) {
            return redirect()->to('home/dashboard'); 
        }

        $M_grooming = new M_grooming();
        $data = [
            'title' => 'Data Layanan Grooming yang Dihapus',
            'deleted_grooming' => $M_grooming->getDeletedGrooming(),
            'showWelcome' => false 
        ];

        return view('grooming1', $data);
    }

    public function hapusGrooming($id)
    {
        $M_grooming = new M_grooming();
        if ($M_grooming->deletePermanen($id)) {
            $this->logActivity("Menghapus permanen layanan grooming ID: $id");

            return redirect()->to('home/grooming1')->with('success', 'Layanan Grooming berhasil dihapus secara permanen');
        }
        return redirect()->to('home/grooming1')->with('error', 'Layanan Grooming tidak ditemukan atau gagal dihapus');
    }

    public function deleteGrooming($id)
    {
        $M_grooming = new M_grooming();
        if ($M_grooming->softDelete($id)) {

            $this->logActivity("Menghapus layanan grooming ID: $id (soft delete)");

            return redirect()->to('home/grooming1')->with('success', 'Layanan Grooming berhasil dihapus (soft delete)');
        }
        return redirect()->to('home/grooming')->with('error', 'Layanan Grooming tidak ditemukan atau gagal dihapus');
    }

    public function restoreGrooming($id)
    {
        $M_grooming = new M_grooming();

        if ($M_grooming->restore($id)) {
            $this->logActivity("Mengembalikan layanan grooming ID: $id (soft delete)");
            return redirect()->to('home/grooming')->with('success', 'Layanan Grooming berhasil direstore');
        }
        return redirect()->to('home/grooming1')->with('error', 'Layanan Grooming tidak ditemukan');
    }


    public function jadwal_grooming()
    {
        $this->logActivity("Mengakses Tabel Jadwal Grooming");

        if (!session()->has('id_user')) { 
            return redirect()->to('home/login');
        }

        if (!in_array(session()->get('level'), [1, 3, 4])) {
            return redirect()->to('home/dashboard'); 
        }

        $M_jadwal = new M_jadwal(); 
        $data = [
            'title' => 'Data Jadwal Layanan Grooming',
            'showWelcome' => false, 
            'jadwal' => $M_jadwal->getJadwal(),
        ];

        return view('jadwal_grooming', $data);
    }

    public function input_grooming()
    {
        $data = [
            'title' => 'Input Jadwal Layanan Grooming'
        ];

        return view('tgroomingriyal', $data);
    }

    public function tgroomingriyal()
    {
        $M_jadwal = new M_jadwal(); 

        $data = [
            'hari'         => $this->request->getPost('hari'),
            'jam_mulai'    => $this->request->getPost('jam_mulai'),
            'jam_selesai'  => $this->request->getPost('jam_selesai'),
            'status'       => 'Tersedia'
        ];

        $M_jadwal->insert($data); 

        $logMessage = "Menambahkan Jadwal Grooming: Hari " . $data['hari'] . 
                      ", Jam: " . $data['jam_mulai'] . " - " . $data['jam_selesai'];
        $this->logActivity($logMessage);

        return redirect()->to(base_url('home/jadwal_grooming'))->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function edit_jadwal($id)
    {
        $M_jadwal = new M_jadwal();
        $jadwal = $M_jadwal->getJadwalById($id);

        if (!$jadwal) {
            return redirect()->to(base_url('home/jadwal_grooming'))->with('error', 'Data jadwal tidak ditemukan.');
        }

        $data = [
            'title'   => 'Edit Jadwal Layanan Grooming',
            'jadwal' => $jadwal,
        ];

        return view('edit5', $data);
    }

    public function edit5($id)
    {
        $M_jadwal = new M_jadwal();
        $modelLog = new M_log(); 
        $jadwal = $M_jadwal->getJadwalById($id);

        if (!$jadwal) {
            return redirect()->to(base_url('home/jadwal_grooming'))->with('error', 'Jadwal tidak ditemukan.');
        }

        $dataLayanan = [
            'hari'         => $this->request->getPost('hari'),
            'jam_mulai'    => $this->request->getPost('jam_mulai'),
            'jam_selesai'  => $this->request->getPost('jam_selesai'),
            'status'    => $this->request->getPost('status'),
        ];

        $changes = [];
        foreach ($dataLayanan as $key => $value) {
            if ($jadwal->$key != $value) {
                $changes[] = ucfirst(str_replace('_', ' ', $key)) . " dari '" . $jadwal->$key . "' ke '" . $value . "'";
            }
        }

        $M_jadwal->update($id, $dataLayanan);
        
        if (!empty($changes)) {
                $aktivitas = "Mengedit Data Jadwal Layanan Grooming ID $id - " . implode(', ', $changes);
                $this->logActivity($aktivitas);
            }

        return redirect()->to(base_url('home/jadwal_grooming'))->with('success', 'Data jadwal berhasil diperbarui.');
    }

    public function detail_jadwal($id)
    {
        $session = session();
        $user_id = $session->get('id_user'); 
        $user_level = $session->get('level'); 

        $logModel = new \App\Models\M_log();
        $M_jadwal = new M_jadwal();

        $jadwal = $M_jadwal->getJadwalById($id);

        $logModel->saveLog($user_id, "id_user={$user_id} berhasil melihat detail jadwal layanan grooming ID {$id}", $ip_address);

        $data = [
            'title' => 'Detail Jadwal Layanan Grooming',
            'jadwal' => $jadwal
        ];

        return view('detailriyal', $data);
    }

    public function jadwal_grooming1()
    {
        $this->logActivity("Mengakses Tabel Jadwal Layanan Grooming yang Dihapus");

        if (!session()->has('id_user')) { 
            return redirect()->to('home/login');
        }

        if (!in_array(session()->get('level'), [1, 4])) {
            return redirect()->to('home/dashboard'); 
        }

        $M_jadwal = new M_jadwal();
        $data = [
            'title' => 'Data Jadwal Layanan Grooming yang Dihapus',
            'deleted_jadwal' => $M_jadwal->getDeletedJadwal(),
            'showWelcome' => false 
        ];

        return view('jadwal_grooming1', $data);
    }

    public function hapusJadwal($id)
    {
        $M_jadwal = new M_jadwal();
        if ($M_jadwal->deletePermanen($id)) {
            $this->logActivity("Menghapus permanen jadwal layanan grooming ID: $id");

            return redirect()->to('home/jadwal_grooming1')->with('success', 'Jadwal Layanan Grooming berhasil dihapus secara permanen');
        }
        return redirect()->to('home/jadwal_grooming1')->with('error', 'Jadwal Layanan Grooming tidak ditemukan atau gagal dihapus');
    }

    public function deleteJadwal($id)
    {
        $M_jadwal = new M_jadwal();
        if ($M_jadwal->softDelete($id)) {

            $this->logActivity("Menghapus jadwal layanan grooming ID: $id (soft delete)");

            return redirect()->to('home/jadwal_grooming1')->with('success', 'Jadwal Layanan Grooming berhasil dihapus (soft delete)');
        }
        return redirect()->to('home/jadwal_grooming')->with('error', 'Jadwal Layanan Grooming tidak ditemukan atau gagal dihapus');
    }

    public function restoreJadwal($id)
    {
        $M_jadwal = new M_jadwal();

        if ($M_jadwal->restore($id)) {
            $this->logActivity("Mengembalikan jadwal layanan grooming ID: $id (soft delete)");
            return redirect()->to('home/jadwal_grooming')->with('success', 'Jadwal Layanan Grooming berhasil direstore');
        }
        return redirect()->to('home/jadwal_grooming1')->with('error', 'Jadwal Layanan Grooming tidak ditemukan');
    }

    public function rekap_pesanan()
    {
        $this->logActivity("Mengakses Tabel Rekap Pesanan");

        $db = \Config\Database::connect();
        $builder = $db->table('pesanan');
        $builder->select('pesanan.*, pelanggan.nama as nama_pelanggan');
        $builder->join('pelanggan', 'pesanan.id_pelanggan = pelanggan.id_pelanggan');
        $builder->orderBy('pesanan.created_at', 'DESC');
        $pesanan = $builder->get()->getResult();

        return view('rekap_pesanan', ['pesanan' => $pesanan]);
    }

    public function detail_pesanan($id)
    {
        $this->logActivity("Mengakses Tabel Detail Pesanan");

        $db = \Config\Database::connect();
        $session = session();
        $id_user = $session->get('id_user');
        $level_user = $session->get('level');  

        $pesanan = $db->table('pesanan')
            ->select('pesanan.*, pelanggan.nama as nama_pelanggan')
            ->join('pelanggan', 'pelanggan.id_pelanggan = pesanan.id_pelanggan')
            ->where('pesanan.id_pesanan', $id)
            ->get()->getRow();

        $detail = $db->table('detail_pesanan')
            ->select('detail_pesanan.*, produk.nama_produk, produk.kode_produk, produk.harga')
            ->join('produk', 'produk.id_produk = detail_pesanan.id_produk')
            ->where('detail_pesanan.id_pesanan', $id)
            ->get()->getResult();

        if ($level_user == 1 || $level_user == 3 || $level_user == 4) {  
            return view('detail_pesanan', [
                'pesanan' => $pesanan,
                'detail' => $detail
            ]);
        } else {  
            return view('detail_pesanan1', [
                'pesanan' => $pesanan,
                'detail' => $detail
            ]);
        }
    }

    public function cetak_nota($id)
    {
        $db = \Config\Database::connect();

        $this->logActivity("Mencetak nota pesanan dengan ID: $id");

        $pesanan = $db->table('pesanan')
            ->select('pesanan.*, pelanggan.nama as nama_pelanggan')
            ->join('pelanggan', 'pelanggan.id_pelanggan = pesanan.id_pelanggan')
            ->where('pesanan.id_pesanan', $id)
            ->get()->getRow();

        $detail = $db->table('detail_pesanan')
            ->select('detail_pesanan.*, produk.nama_produk, produk.kode_produk, produk.harga')
            ->join('produk', 'produk.id_produk = detail_pesanan.id_produk')
            ->where('detail_pesanan.id_pesanan', $id)
            ->get()->getResult();

        $html = view('nota_pdf', [
            'pesanan' => $pesanan,
            'detail' => $detail
        ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Nota-Pesanan-{$id}.pdf", array("Attachment" => false));
    }
    
    public function inputp()
    {
        $M_Pelanggan = new M_Pelanggan();
        $M_Produk = new M_Produk();

        $data['pelanggan'] = $M_Pelanggan->tampil1();
        $data['produk'] = $M_Produk->tampil1();

       $db = \Config\Database::connect();
        $query = $db->query("SHOW COLUMNS FROM produk LIKE 'kategori'");
        $row = $query->getRow();
        preg_match("/^enum\(\'(.*)\'\)$/", $row->Type, $matches);
        $enum_values = explode("','", $matches[1]);

        $data['kategori_enum'] = $enum_values;

        return view('input_pesanan', $data);
    }

    public function input_pesanan()
    {
        $M_Pesanan = new M_Pesanan();
        $M_Detail = new M_DetailPesanan();

        $id_pelanggan = $this->request->getPost('id_pelanggan');
        $produk = $this->request->getPost('produk'); 
        $jumlah = $this->request->getPost('jumlah'); 
        $total_harga = $this->request->getPost('total_harga'); 

        $M_Pesanan->insert([
            'id_pelanggan' => $id_pelanggan,
            'total_harga' => $total_harga,
            'status' => 'Menunggu Pembayaran',
        ]);

        $id_pesanan = $M_Pesanan->getInsertID();

        foreach ($produk as $i => $id_produk) {
            $subtotal = 0;
            if (!empty($jumlah[$i]) && is_numeric($jumlah[$i]) && isset($_POST['subtotal'][$i])) {
                $subtotal = (int) str_replace(['Rp', '.'], '', $_POST['subtotal'][$i]); 
            }

            $M_Detail->insert([
                'id_pesanan' => $id_pesanan,
                'id_produk' => $id_produk,
                'jumlah' => $jumlah[$i],
                'subtotal' => $subtotal,
            ]);
        }

        $this->logActivity("Petugas menambahkan pesanan manual untuk pelanggan ID: $id_pelanggan");

        return redirect()->to('home/rekap_pesanan')->with('success', 'Pesanan berhasil disimpan!');
    }   


    public function rekap_grooming()
    {
        $this->logActivity("Mengakses Tabel Rekap Grooming");

        $db = \Config\Database::connect();
        $builder = $db->table('booking_grooming');
        $builder->select('
            booking_grooming.*,
            pelanggan.nama as nama_pelanggan,
            hewan_pelanggan.nama_hewan,
            jadwal_grooming.jam_mulai,
            jadwal_grooming.jam_selesai,
            GROUP_CONCAT(layanan_grooming.nama_layanan SEPARATOR ", ") as layanan
        ');
        $builder->join('pelanggan', 'pelanggan.id_pelanggan = booking_grooming.id_pelanggan');
        $builder->join('hewan_pelanggan', 'hewan_pelanggan.id_hewan = booking_grooming.id_hewan');
        $builder->join('jadwal_grooming', 'jadwal_grooming.id_jadwal = booking_grooming.id_jadwal');
        $builder->join('detail_grooming', 'detail_grooming.id_booking = booking_grooming.id_booking');
        $builder->join('layanan_grooming', 'layanan_grooming.id_layanan = detail_grooming.id_layanan');
        $builder->groupBy('booking_grooming.id_booking');
        $builder->orderBy('booking_grooming.created_at', 'DESC');

        $booking = $builder->get()->getResult();

        return view('rekap_grooming', ['booking' => $booking]);
    }

    public function detail_gr($id_booking)
    {
        $this->logActivity("Mengakses Tabel Detail Layanan Grooming");

        $level_user = session()->get('level');


        $db = \Config\Database::connect();

        $booking = $db->table('booking_grooming bg')
            ->select('bg.*, p.nama AS nama_pelanggan, h.nama_hewan, h.jenis, j.jam_mulai, j.jam_selesai, j.hari')
            ->join('pelanggan p', 'p.id_pelanggan = bg.id_pelanggan')
            ->join('hewan_pelanggan h', 'h.id_hewan = bg.id_hewan')
            ->join('jadwal_grooming j', 'j.id_jadwal = bg.id_jadwal')
            ->where('bg.id_booking', $id_booking)
            ->get()->getRow();

        $query = $db->table('detail_grooming d')
            ->select('l.nama_layanan, l.harga, l.durasi, pt.nama AS nama_petugas')
            ->join('layanan_grooming l', 'l.id_layanan = d.id_layanan')
            ->join('petugas pt', 'l.id_petugas = pt.id_petugas', 'left')
            ->where('d.id_booking', $id_booking)
            ->get();

        $layanan = $query->getResult();

        $durasi_total = 0;
        foreach ($layanan as $lay) {
            $durasi_total += $lay->durasi;
        }

        $datetime_mulai = new \DateTime($booking->tanggal . ' ' . $booking->jam_mulai);
        $datetime_selesai = clone $datetime_mulai;
        $datetime_selesai->modify("+$durasi_total minutes");


         if ($level_user == 2) {
            return view('detail_grooming1', [
                'booking' => $booking,
                'layanan' => $layanan,
                'jam_mulai' => $datetime_mulai->format('H:i'),
                'jam_selesai' => $datetime_selesai->format('H:i'),
            ]);
        } else {
            return view('detail_grooming', [
                'booking' => $booking,
                'layanan' => $layanan,
                'jam_mulai' => $datetime_mulai->format('H:i'),
                'jam_selesai' => $datetime_selesai->format('H:i'),
            ]);
        }

    }

    public function inputg()
    {
        $M_pelanggan = new M_pelanggan();
        $M_hewan = new M_hewan();
        $M_grooming = new M_grooming();
        $M_Jadwal = new M_jadwal();

        $data['pelanggan'] = $M_pelanggan->tampil1();
        $data['hewan'] = $M_hewan->tampil1();
        $data['layanan'] = $M_grooming->tampil1();
        $data['jadwal'] = $M_Jadwal->tampil1();

        return view('tbooking', $data);
    }

    public function tbooking()
    {
        $db = \Config\Database::connect();
        $M_booking = new M_booking();

        $id_pelanggan = $this->request->getPost('id_pelanggan');
        $id_hewan     = $this->request->getPost('id_hewan');
        $id_jadwal    = $this->request->getPost('id_jadwal');
        $tanggal      = $this->request->getPost('tanggal');
        $layananArray = $this->request->getPost('layanan'); // layanan[]

        $dataBooking = [
            'id_pelanggan' => $id_pelanggan,
            'id_hewan'     => $id_hewan,
            'id_jadwal'    => $id_jadwal,
            'tanggal'      => $tanggal,
            'status'       => 'Menunggu',
            'created_at'   => date('Y-m-d H:i:s'),
            'total_harga'  => 0, 
        ];

        $M_booking->insert($dataBooking);
        $id_booking = $db->insertID(); 

        $total = 0;
        foreach ($layananArray as $id_layanan) {
            $layanan = $db->table('layanan_grooming')->where('id_layanan', $id_layanan)->get()->getRow();
            $total += $layanan->harga;

            $db->table('detail_grooming')->insert([
                'id_booking'  => $id_booking,
                'id_layanan'  => $id_layanan,
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        }

        $db->table('booking_grooming')
            ->where('id_booking', $id_booking)
            ->update(['total_harga' => $total]);

        $this->logActivity("Petugas menambahkan booking manual untuk pelanggan ID: $id_pelanggan");

        return redirect()->to(base_url('home/rekap_grooming'))->with('success', 'Booking berhasil ditambahkan!');
    }

    public function get_jadwal_by_hari($hari)
    {
        $M_jadwal = new \App\Models\M_jadwal();
        $jadwal = $M_jadwal->where('hari', $hari)->findAll();
        echo json_encode($jadwal);
    }

    public function get_hewan_by_pelanggan($id)
    {
        $M_hewan = new M_hewan();
        $hewan = $M_hewan->getHewanByPelanggan($id); 
        echo json_encode($hewan);
    }

    public function cetak_nota1($id)
    {
        $db = \Config\Database::connect();

        $this->logActivity("Mencetak nota grooming dengan ID: $id");

        $booking = $db->table('booking_grooming bg')
            ->select('
                bg.*, 
                p.nama AS nama_pelanggan, 
                h.nama_hewan, 
                h.jenis, 
                j.jam_mulai, 
                j.jam_selesai
            ')
            ->join('pelanggan p', 'p.id_pelanggan = bg.id_pelanggan')
            ->join('hewan_pelanggan h', 'h.id_hewan = bg.id_hewan')
            ->join('jadwal_grooming j', 'j.id_jadwal = bg.id_jadwal')
            ->where('bg.id_booking', $id)
            ->get()->getRow();

        $layanan = $db->table('detail_grooming d')
            ->select('
                l.nama_layanan, 
                l.harga, 
                l.durasi, 
                pt.nama AS nama_petugas
            ')
            ->join('layanan_grooming l', 'l.id_layanan = d.id_layanan')
            ->join('petugas pt', 'l.id_petugas = pt.id_petugas', 'left')
            ->where('d.id_booking', $id)
            ->get()->getResult();

        $total = 0;
        foreach ($layanan as $lay) {
            $total += $lay->harga;
        }

        $html = view('nota_pdf1', [
            'booking' => $booking,
            'layanan' => $layanan,
            'total' => $total
        ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Nota-Grooming-{$id}.pdf", array("Attachment" => false));
    }

    public function pembayaran()
    {
        $this->logActivity("Mengakses Tabel Pembayaran");

        if (!session()->has('id_user')) { 
            return redirect()->to('home/login');
        }

        if (!in_array(session()->get('level'), [1, 3, 4])) {
            return redirect()->to('home/dashboard'); 
        }

        $M_pembayaran = new M_pembayaran();

        $data = [
            'title' => 'Data Pembayaran',
            'showWelcome' => false,
            'pembayaran' => $M_pembayaran->getAllPembayaran(), 
        ];

        return view('pembayaran', $data);
    }

    public function selesai_grooming($id_booking)
    {
        \Config\Database::connect()->table('booking_grooming')
            ->where('id_booking', $id_booking)
            ->update(['status' => 'Menunggu Pembayaran']);

        $this->logActivity("Menandai grooming ID $id_booking sebagai selesai.");

        return redirect()->to(base_url('home/rekap_grooming'))->with('success', 'Grooming ditandai selesai.');
    }

    public function simpan_pembayaran_grooming()
    {
        $id_booking = $this->request->getPost('id_booking');
        $metode = $this->request->getPost('metode');
        $bukti = $this->request->getFile('bukti_pembayaran');

        $db = \Config\Database::connect();
        $nama_file = null;

        if ($bukti && $bukti->isValid() && !$bukti->hasMoved()) {
            $nama_file = $bukti->getRandomName();
            $bukti->move('uploads/bukti/', $nama_file);
        }

        $db->table('pembayaran')->insert([
        'id_booking' => $id_booking,
        'metode' => $metode,
        'status' => 'Menunggu Konfirmasi',
        'bukti_pembayaran' => $nama_file,
        'created_at' => date('Y-m-d H:i:s'),
        'jenis_transaksi' => 'Grooming' 
    ]);

        $db->table('booking_grooming')
            ->where('id_booking', $id_booking)
            ->update(['status' => 'Menunggu Konfirmasi']);

        $this->logActivity("Mengirim bukti pembayaran grooming ID $id_booking dengan metode $metode.");

        return redirect()->to(base_url('home/rekap_grooming'))->with('success', 'Pembayaran berhasil dikonfirmasi dan menunggu persetujuan admin.');
    }

    public function selesai_pesanan($id_pesanan)
    {
        \Config\Database::connect()->table('pesanan')
            ->where('id_pesanan', $id_pesanan)
            ->update(['status' => 'Selesai']);

         $this->logActivity("Menandai pesanan ID $id_pesanan sebagai selesai.");

        return redirect()->to(base_url('home/rekap_pesanan'))->with('success', 'Pesanan ditandai selesai.');
    }

  public function simpan_pbyrpsn()
    {
        $pesananModel = new M_pesanan();
        $pengirimanModel = new M_pengiriman();
        $id_pesanan = $this->request->getPost('id_pesanan');
        $metode = $this->request->getPost('metode');
        $bukti_pembayaran = $this->request->getFile('bukti_pembayaran');

        $pesanan = $pesananModel->find($id_pesanan);
        if (!$pesanan) {
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan.');
        }

        if ($pesanan->jenis_pengiriman == 'Pick Up') {
            $biaya_pengiriman = 0;
        } else {
            $biaya_pengiriman = $pesanan->total_harga * 0.10;
        }

        if ($metode != 'Cash') {
            if ($bukti_pembayaran && $bukti_pembayaran->isValid()) {
                $fileName = $bukti_pembayaran->getRandomName();
                $bukti_pembayaran->move(WRITEPATH . 'uploads', $fileName);
            }
        } else {
            $fileName = null; 
        }

        $pesananModel->update($id_pesanan, [
            'status' => 'Menunggu Konfirmasi',
            'metode' => $metode,
            'bukti_pembayaran' => $fileName,
            'biaya_pengiriman' => $biaya_pengiriman
        ]);

        if ($pesanan->jenis_pengiriman == 'Pengiriman') {
            $pengirimanModel->save([
                'id_pesanan' => $id_pesanan,
                'biaya' => $biaya_pengiriman,
                'status' => 'Diproses'
            ]);
        }

        return redirect()->to('home/riwayat_pengiriman')->with('success', 'Pembayaran berhasil disimpan, menunggu konfirmasi.');
    }


    public function konfirmasi($id_pembayaran)
    {
        $db = \Config\Database::connect();

        $pembayaran = $db->table('pembayaran')->where('id_pembayaran', $id_pembayaran)->get()->getRow();

        if ($pembayaran) {
            $db->table('pembayaran')->where('id_pembayaran', $id_pembayaran)
                ->update(['status' => 'Dikonfirmasi']);

            if ($pembayaran->jenis_transaksi === 'Grooming') {
                $db->table('booking_grooming')->where('id_booking', $pembayaran->id_booking)
                    ->update(['status' => 'Selesai']);

             $this->logActivity("Mengonfirmasi pembayaran grooming ID pembayaran $id_pembayaran (booking ID {$pembayaran->id_booking}).");

            } elseif ($pembayaran->jenis_transaksi === 'Produk') {
                $db->table('pesanan')->where('id_pesanan', $pembayaran->id_pesanan)
                    ->update(['status' => 'Selesai']);

                $this->logActivity("Mengonfirmasi pembayaran produk ID pembayaran $id_pembayaran (pesanan ID {$pembayaran->id_pesanan}).");
            }

            return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
        }

        return redirect()->back()->with('error', 'Data pembayaran tidak ditemukan.');
    }

    public function tolak($id_pembayaran)
    {
        $db = \Config\Database::connect();

        $pembayaran = $db->table('pembayaran')->where('id_pembayaran', $id_pembayaran)->get()->getRow();

        if ($pembayaran) {
            $db->table('pembayaran')->where('id_pembayaran', $id_pembayaran)
                ->update(['status' => 'Ditolak']);

            if ($pembayaran->jenis_transaksi === 'Grooming') {
                $db->table('booking_grooming')->where('id_booking', $pembayaran->id_booking)
                    ->update(['status' => 'Dibatalkan']);

            $this->logActivity("Menolak pembayaran grooming ID pembayaran $id_pembayaran (booking ID {$pembayaran->id_booking}).");

            } elseif ($pembayaran->jenis_transaksi === 'Produk') {
                $db->table('pesanan')->where('id_pesanan', $pembayaran->id_pesanan)
                    ->update(['status' => 'Dibatalkan']);

            $this->logActivity("Menolak pembayaran produk ID pembayaran $id_pembayaran (pesanan ID {$pembayaran->id_pesanan}).");

            }
            return redirect()->back()->with('success', 'Pembayaran ditolak.');
        }
        return redirect()->back()->with('error', 'Data pembayaran tidak ditemukan.');
    }

    public function profile()
    { 
        $this->logActivity("Mengakses Profil");

        if (!session()->has('id_user')) {
            return redirect()->to('home/login')->with('error', 'Silakan login terlebih dahulu!');
        }

        $userModel = new M_user();
        $pelangganModel = new M_pelanggan();
        $petugasModel = new M_petugas();

        $user = $userModel->find(session()->get('id_user'));

        if (!$user) {
            return redirect()->to('/login')->with('error', 'User tidak ditemukan!');
        }

        $extraData = null;
        if ($user['level'] == 2) {
            $extraData = $pelangganModel->where('id_user', $user['id_user'])->get()->getRowArray();
            return view('profile', ['user' => $user, 'extraData' => $extraData]);

        } elseif ($user['level'] == 3 || $user['level'] == 1 || $user['level'] == 4) {
            $extraData = ($user['level'] == 3) ? $petugasModel->getPetugasById($user['id_user']) : null;
            return view('profilew', ['user' => $user, 'extraData' => $extraData]);
        }

        return redirect()->to('/login')->with('error', 'Akses tidak diizinkan!');
    }

    public function updateProfile()
    {
        $session = session();
        $id_user = $session->get('id_user');
        $userModel = new M_User();
        
        $data = [
            'nama' => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),  
            'alamat' => $this->request->getPost('alamat'), 
        ];
        
        $file = $this->request->getFile('foto');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('uploads/', $newName);
            $data['foto'] = $newName; 
        }

        $userModel->update($id_user, $data);

         $this->logActivity("Mengubah profil pengguna dengan ID $id_user.");
        
        return redirect()->to('home/profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function settings() 
    {
        $this->logActivity("Mengakses Settings");
        
        $M_setting = new \App\Models\M_setting();
        $data['setting'] = $M_setting->tampil1();

        if (!$data['setting']) {
            $data['setting'] = [
                'nama'  => 'Website Default',
                'foto'  => 'default_logo.png',
            ];
        }

        return view('setting', $data);
    }

    public function update_setting()
    {
        $settingsModel = new M_setting(); 
        
        $data = [
            'nama'  => $this->request->getPost('nama'),
            'foto' => $this->request->getPost('foto'),
        ];

        $file = $this->request->getFile('foto');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('uploads/', $newName);
            $data['foto'] = $newName;
        }

        $settingsModel->updateSetting($data); 

        $this->logActivity("Mengupdate pengaturan website: " . json_encode($data));

        return redirect()->to(base_url('home/settings'))->with('success', 'Pengaturan berhasil diperbarui.');
    }

    public function lpn()
    {
        $this->logActivity("Mengakses Laporan Pemesanan Produk");

        $produkModel = new \App\Models\M_produk();
        $produkResult = $produkModel->findAll();

        $id_produk = $this->request->getGet('id_produk');

        $data = [
            'produkResult' => $produkResult,
            'id_produk' => $id_produk
        ];

        return view('lpn', $data);
    }


    public function lpnctk()
    {
        $this->logActivity("Mencetak Laporan Pemesanan Produk");

        $pesananModel = new \App\Models\M_pesanan();
        $produkModel = new \App\Models\M_produk(); 
        $tanggal_mulai = $this->request->getGet('tanggal_mulai') ?? date('Y-m-d');
        $tanggal_selesai = $this->request->getGet('tanggal_selesai') ?? date('Y-m-d');
        $id_produk = $this->request->getGet('id_produk');

        $produk = $produkModel->findAll();

        $query = $pesananModel->select('pesanan.*, pelanggan.nama, detail_pesanan.jumlah, detail_pesanan.subtotal, produk.nama_produk')
            ->join('pelanggan', 'pelanggan.id_pelanggan = pesanan.id_pelanggan')
            ->join('detail_pesanan', 'detail_pesanan.id_pesanan = pesanan.id_pesanan')
            ->join('produk', 'produk.id_produk = detail_pesanan.id_produk')
            ->where('pesanan.created_at >=', $tanggal_mulai)
            ->where('pesanan.created_at <=', $tanggal_selesai);

        if (!empty($id_produk)) {
            $query->where('produk.id_produk', $id_produk);
        }

        $data = [
            'pemesanan' => $query->findAll(),
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'id_produk' => $id_produk,
            'produk' => $produk
        ];

        $logMessage = "Mencetak Laporan Pemesanan Produk dari {$tanggal_mulai} sampai {$tanggal_selesai}" .
            (!empty($id_produk) ? " untuk produk ID {$id_produk}" : " untuk semua produk.");
        $this->logActivity($logMessage);
        log_message('info', $logMessage);

        return view('lpnctk_produk', $data);
    }

    public function lpnpdf()
    {
        $this->logActivity("Membuat PDF Laporan Pemesanan Produk");

        $pesananModel = new \App\Models\M_pesanan();
        $produkModel = new \App\Models\M_produk();

        $tanggal_mulai = $this->request->getGet('tanggal_mulai') ?? date('Y-m-d');
        $tanggal_selesai = $this->request->getGet('tanggal_selesai') ?? date('Y-m-d');
        $id_produk = $this->request->getGet('id_produk');

        $produkResult = $produkModel->findAll();

        $query = $pesananModel->select('pesanan.*, pelanggan.nama, detail_pesanan.jumlah, detail_pesanan.subtotal, produk.nama_produk')
            ->join('pelanggan', 'pelanggan.id_pelanggan = pesanan.id_pelanggan')
            ->join('detail_pesanan', 'detail_pesanan.id_pesanan = pesanan.id_pesanan')
            ->join('produk', 'produk.id_produk = detail_pesanan.id_produk')
            ->where('pesanan.created_at >=', $tanggal_mulai)
            ->where('pesanan.created_at <=', $tanggal_selesai);

        if (!empty($id_produk)) {
            $query->where('produk.id_produk', $id_produk);
        }

        $data['pemesanan'] = $query->findAll();
        $data['produkResult'] = $produkResult;
        $data['tanggal_mulai'] = $tanggal_mulai;
        $data['tanggal_selesai'] = $tanggal_selesai;
        $data['id_produk'] = $id_produk;

        $logMessage = "Mencetak PDF Laporan Pemesanan Produk dari {$tanggal_mulai} sampai {$tanggal_selesai}" .
            (!empty($id_produk) ? " untuk produk ID {$id_produk}" : " untuk semua produk.");
        $this->logActivity($logMessage);
        log_message('info', $logMessage);

        $dompdf = new \Dompdf\Dompdf();
        $html = view('lpnpdf_produk', $data);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("laporan_pemesanan_produk.pdf", array("Attachment" => false));
    }

    public function lpgrooming()
    {
        $this->logActivity("Mengakses Laporan Layanan Grooming");

        $tanggal_mulai = $this->request->getGet('tanggal_mulai');
        $tanggal_selesai = $this->request->getGet('tanggal_selesai');
        $id_petugas = $this->request->getGet('id_petugas');

        $model = new M_booking();
        $petugasModel = new M_petugas(); 

        $data['petugasResult'] = $petugasModel->findAll();

        $data['groomingResult'] = [];
        
        if ($tanggal_mulai && $tanggal_selesai) {
            $data['groomingResult'] = $model->getLaporanGrooming($tanggal_mulai, $tanggal_selesai, $id_petugas);
        }

        $data['tanggal_mulai'] = $tanggal_mulai;
        $data['tanggal_selesai'] = $tanggal_selesai;
        $data['id_petugas'] = $id_petugas;

        return view('lpgrooming', $data);
    }


    public function lpgrooming_cetak()
    {
        $this->logActivity("Mencetak Laporan Grooming");

        $tanggal_mulai = $this->request->getGet('tanggal_mulai');
        $tanggal_selesai = $this->request->getGet('tanggal_selesai');
        $id_petugas = $this->request->getGet('id_petugas');

        $groomingModel = new \App\Models\M_booking();
        $groomingResult = $groomingModel->getLaporanGrooming($tanggal_mulai, $tanggal_selesai, $id_petugas);

        $petugasModel = new \App\Models\M_petugas();
        $petugasResult = $petugasModel->findAll();

        $data = [
            'groomingResult' => $groomingResult,
            'petugasResult' => $petugasResult,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'id_petugas' => $id_petugas
        ];

        return view('lpgrooming_cetak', $data);
    }

    public function lpgrooming_pdf()
    {
        $this->logActivity("Membuat PDF Laporan Grooming");

        $tanggal_mulai = $this->request->getGet('tanggal_mulai');
        $tanggal_selesai = $this->request->getGet('tanggal_selesai');
        $id_petugas = $this->request->getGet('id_petugas');

        $groomingModel = new \App\Models\M_booking();
        $groomingResult = $groomingModel->getLaporanGrooming($tanggal_mulai, $tanggal_selesai, $id_petugas);

        $petugasModel = new \App\Models\M_petugas();
        $petugasResult = $petugasModel->findAll();

        $data = [
            'groomingResult' => $groomingResult,
            'petugasResult' => $petugasResult,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'id_petugas' => $id_petugas
        ];

        $html = view('lpgrooming_pdf', $data);

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('laporan_grooming.pdf', array('Attachment' => 0));
    }

    public function lpkeuangan()
    {
        $this->logActivity("Mengakses Laporan Keuangan");

        $tanggal_mulai = $this->request->getGet('tanggal_mulai');
        $tanggal_selesai = $this->request->getGet('tanggal_selesai');

        $model = new M_pembayaran();

        $data['tanggal_mulai'] = $tanggal_mulai;
        $data['tanggal_selesai'] = $tanggal_selesai;

        if ($tanggal_mulai && $tanggal_selesai) {
            $data['keuanganResult'] = $model->getLaporanKeuangan($tanggal_mulai, $tanggal_selesai);
        } else {
            $data['keuanganResult'] = [];
        }

        return view('lpkeuangan', $data);
    }

    public function lpkeuangan_excel()
    {
        $this->logActivity("Membuat Excel Laporan Keuangan");

        $tanggal_mulai = $this->request->getGet('tanggal_mulai');
        $tanggal_selesai = $this->request->getGet('tanggal_selesai');

        $model = new M_pembayaran();
        $laporanKeuangan = $model->getLaporanKeuangan($tanggal_mulai, $tanggal_selesai);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'LAPORAN KEUANGAN');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'No');
        $sheet->setCellValue('B2', 'Tanggal');
        $sheet->setCellValue('C2', 'Nama Item');
        $sheet->setCellValue('D2', 'Metode Pembayaran');
        $sheet->setCellValue('E2', 'Pendapatan');
        $sheet->setCellValue('F2', 'Pengeluaran');
        $sheet->setCellValue('G2', 'Total Keuangan');

        $row = 3;
        $no = 1;

        foreach ($laporanKeuangan as $laporan) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $laporan['created_at']);
            $sheet->setCellValue('C' . $row, $laporan['nama_item']);
            $sheet->setCellValue('D' . $row, $laporan['metode']);
            $sheet->setCellValue('E' . $row, $laporan['total_harga']);
            $sheet->setCellValue('F' . $row, '=E' . $row . '*0.3');
            $sheet->setCellValue('G' . $row, '=E' . $row . '-F' . $row);
            $row++;
        }

        $sheet->setCellValue('D' . $row, 'TOTAL');
        $sheet->setCellValue('E' . $row, '=SUM(E3:E' . ($row - 1) . ')');
        $sheet->setCellValue('F' . $row, '=SUM(F3:F' . ($row - 1) . ')');
        $sheet->setCellValue('G' . $row, '=SUM(G3:G' . ($row - 1) . ')');

        $sheet->getStyle('D' . $row . ':G' . $row)->getFont()->setBold(true);
        $sheet->getStyle('E3:G' . $row)->getNumberFormat()->setFormatCode('#,##0');

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $sheet->getStyle('A2:G' . $row)->applyFromArray($styleArray);

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan-Keuangan-' . date('Ymd');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function lpkeuangan_pdf()
    {
        $this->logActivity("Membuat PDF Laporan Keuangan");

        $tanggal_mulai = $this->request->getGet('tanggal_mulai');
        $tanggal_selesai = $this->request->getGet('tanggal_selesai');

        $model = new M_pembayaran();
        $laporanKeuangan = $model->getLaporanKeuangan($tanggal_mulai, $tanggal_selesai);

        $totalPendapatan = 0;
        $totalPengeluaran = 0;
        $totalKeuangan = 0;

        if (is_array($laporanKeuangan) && count($laporanKeuangan) > 0) {
            foreach ($laporanKeuangan as &$item) {
                $item['pengeluaran'] = $item['total_harga'] * 0.3; 
                $item['total_keuangan'] = $item['total_harga'] - $item['pengeluaran'];

                $totalPendapatan += $item['total_harga'];
                $totalPengeluaran += $item['pengeluaran'];
                $totalKeuangan += $item['total_keuangan'];
            }
        } else {
            echo "Data laporan keuangan kosong atau tidak valid.";
        }

        $data = [
            'laporan' => $laporanKeuangan,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'totalPendapatan' => $totalPendapatan,
            'totalPengeluaran' => $totalPengeluaran,
            'totalKeuangan' => $totalKeuangan,
        ];

        $html = view('lpkeuangan_pdf', $data);

        $options = new Options();
        $options->set('isRemoteEnabled', true); 

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape'); 
        $dompdf->render();

        $dompdf->stream('Laporan-Keuangan-' . date('Ymd') . '.pdf', ['Attachment' => true]);
    }

    public function pengiriman()
    {
        $this->logActivity("Mengakses Tabel Pengiriman");

        if (!session()->has('id_user')) { 
            return redirect()->to('home/login');
        }

        if (!in_array(session()->get('level'), [1, 3, 4])) {
            return redirect()->to('home/dashboard');
        }

        $pengirimanModel = new M_pengiriman();
        $data = [
            'title' => 'Data Pengiriman',
            'pengiriman' => $pengirimanModel->getPengirimanLengkap(),
            'showWelcome' => false
        ];

        return view('pengiriman', $data);
    }

   public function update_status_pengiriman()
    {
        $pengirimanModel = new M_pengiriman();

        $id = $this->request->getPost('id_pengiriman');
        $status = $this->request->getPost('status');

        $pengirimanModel->update($id, ['status' => $status]);

        return redirect()->back()->with('success', 'Status pengiriman berhasil diperbarui.');
    }

    public function proses_pengiriman($id_pembayaran)
    {
        $PembayaranModel = new M_pembayaran();
        $PesananModel = new M_pesanan(); 
        $PengirimanModel = new M_pengiriman();

        $pembayaran = $PembayaranModel->find($id_pembayaran);

        if (!$pembayaran) {
            return redirect()->back()->with('error', 'Pembayaran tidak ditemukan.');
        }

        $id_pesanan = $pembayaran['id_pesanan'];

        if (!$id_pesanan) {
            return redirect()->back()->with('error', 'ID Pesanan tidak ditemukan di pembayaran.');
        }

        $pesanan = $PesananModel->find($id_pesanan);

        if (!$pesanan) {
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan.');
        }

        $subtotal = $pesanan['total_harga'];

        if ($subtotal === NULL || $subtotal == 0) {
            return redirect()->back()->with('error', 'Subtotal pesanan tidak valid.');
        }

        $biaya_pengiriman = $subtotal * 0.10;

        $data_pengiriman = [
            'id_pesanan' => $id_pesanan,
            'biaya' => $biaya_pengiriman,
            'status' => 'Diproses',
            'tanggal_status' => date('Y-m-d H:i:s')
        ];

        $insert = $PengirimanModel->insert($data_pengiriman);

        if ($insert) {
            $PembayaranModel->update($id_pembayaran, ['status' => 'Dikonfirmasi']);
            return redirect()->back()->with('success', 'Pengiriman berhasil diproses.');
        } else {
            return redirect()->back()->with('error', 'Gagal memproses pengiriman.');
        }
    }

     public function riwayat_pengiriman()
    {
        if (session()->get('level') != '2') {
            return redirect()->to(base_url('login'))->with('error', 'Silakan login sebagai pelanggan.');
        }

        $id_user = session()->get('id_user');  

        $pengirimanModel = new M_pengiriman();
        $pengiriman = $pengirimanModel->getPengirimanByUser($id_user);

        foreach ($pengiriman as $kirim) {
            $kirim->produk_dipesan = $pengirimanModel->getProdukByPesanan($kirim->id_pesanan);
        }
        
        $data = [
            'pengiriman' => $pengiriman,
        ];

        return view('riwayat_pengiriman', $data);
    }

    public function konfirmasi_sampai($id_pengiriman)
    {
        $this->logActivity("Mengonfirmasi pengiriman sampai untuk ID: $id_pengiriman");

        if (!session()->has('id_user')) {
            return redirect()->to('home/login');
        }

        if (session()->get('level') != 2) {
            return redirect()->to('home/dashboard');
        }

        $pengirimanModel = new M_pengiriman();
        $pengiriman = $pengirimanModel->find($id_pengiriman);

        if ($pengiriman) {
            $pengirimanModel->update($id_pengiriman, ['status' => 'Selesai']);

            return redirect()->to('home/riwayat_pengiriman')->with('success', 'Pengiriman telah dikonfirmasi diterima.');
        } else {
            return redirect()->to('home/riwayat_pengiriman')->with('error', 'Pengiriman tidak ditemukan.');
        }
    }

    public function konfirmasiKedatangan($id_booking)
{
    $status = $this->request->getPost('status');

    $data = [
        'konfirmasi_kedatangan' => $status
    ];

    if ($status == 'Batal Datang') {
        $data['status'] = 'Dibatalkan';
    } else {
        // Jika bukan batal, ubah status jadi 'Menunggu'
        $data['status'] = 'Menunggu';
    }

    $db = \Config\Database::connect(); 

    $db->table('booking_grooming')
        ->where('id_booking', $id_booking)
        ->update($data);

    session()->setFlashdata('success', 'Konfirmasi kedatangan berhasil.');

    return redirect()->back();
}


}