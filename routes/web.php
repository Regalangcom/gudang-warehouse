<?php

use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\BarangkeluarController;
use App\Http\Controllers\Admin\BarangmasukController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JenisBarangController;
use App\Http\Controllers\Admin\LapBarangKeluarController;
use App\Http\Controllers\Admin\LapBarangMasukController;
use App\Http\Controllers\Admin\LapStokBarangController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\MerkController;
use App\Http\Controllers\Admin\SatuanController;
use App\Http\Controllers\Master\AksesController;
use App\Http\Controllers\Master\AppreanceController;
use App\Http\Controllers\Master\MenuController;
use App\Http\Controllers\Master\RoleController;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Admin\StockOpnameControllers;
use App\Http\Controllers\Admin\PickerController;
use App\Http\Controllers\Admin\ArsipStockOpnameController;
use Illuminate\Support\Facades\Route;;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// login admin
Route::middleware(['preventBackHistory'])->group(function () {
    Route::get('/admin/login', [LoginController::class, 'index'])->middleware('useractive');
    Route::post('/admin/proseslogin', [LoginController::class, 'proseslogin'])->middleware('useractive');
    Route::get('/admin/logout', [LoginController::class, 'logout']);
});

// admin
Route::group(['middleware' => 'userlogin'], function () {

    // Profile
    Route::get('/admin/profile/{user}', [UserController::class, 'profile']);
    Route::post('/admin/updatePassword/{user}', [UserController::class, 'updatePassword']);
    Route::post('/admin/updateProfile/{user}', [UserController::class, 'updateProfile']);
    Route::get('/admin/appreance/', [AppreanceController::class, 'index']);
    Route::post('/admin/appreance/{setting}', [AppreanceController::class, 'update']);

    Route::middleware(['checkRoleUser:/dashboard,menu'])->group(function () {
        Route::get('/', [DashboardController::class, 'index']);
        Route::get('/admin', [DashboardController::class, 'index']);
        Route::get('/admin/dashboard', [DashboardController::class, 'index']);
    });

    Route::middleware(['checkRoleUser:/jenisbarang,submenu'])->group(function () {
        // Jenis Barang
        Route::get('/admin/jenisbarang', [JenisBarangController::class, 'index']);
        Route::get('/admin/jenisbarang/show/', [JenisBarangController::class, 'show'])->name('jenisbarang.getjenisbarang');
        Route::post('/admin/jenisbarang/proses_tambah/', [JenisBarangController::class, 'proses_tambah'])->name('jenisbarang.store');
        Route::post('/admin/jenisbarang/proses_ubah/{jenisbarang}', [JenisBarangController::class, 'proses_ubah']);
        Route::post('/admin/jenisbarang/proses_hapus/{jenisbarang}', [JenisBarangController::class, 'proses_hapus']);
    });

    Route::middleware(['checkRoleUser:/satuan,submenu'])->group(function () {
        // Satuan
        Route::resource('/admin/satuan', \App\Http\Controllers\Admin\SatuanController::class);
        Route::get('/admin/satuan/show/', [SatuanController::class, 'show'])->name('satuan.getsatuan');
        Route::post('/admin/satuan/proses_tambah/', [SatuanController::class, 'proses_tambah'])->name('satuan.store');
        Route::post('/admin/satuan/proses_ubah/{satuan}', [SatuanController::class, 'proses_ubah']);
        Route::post('/admin/satuan/proses_hapus/{satuan}', [SatuanController::class, 'proses_hapus']);
    });

    Route::middleware(['checkRoleUser:/merk,submenu'])->group(function () {
        // Merk
        Route::resource('/admin/merk', \App\Http\Controllers\Admin\MerkController::class);
        Route::get('/admin/merk/show/', [MerkController::class, 'show'])->name('merk.getmerk');
        Route::post('/admin/merk/proses_tambah/', [MerkController::class, 'proses_tambah'])->name('merk.store');
        Route::post('/admin/merk/proses_ubah/{merk}', [MerkController::class, 'proses_ubah']);
        Route::post('/admin/merk/proses_hapus/{merk}', [MerkController::class, 'proses_hapus']);
    });

    Route::middleware(['checkRoleUser:/barang,submenu'])->group(function () {
        // Barang
        Route::resource('/admin/barang', \App\Http\Controllers\Admin\BarangController::class);
        Route::get('/admin/barang/show/', [BarangController::class, 'show'])->name('barang.getbarang');
        Route::post('/admin/barang/proses_tambah/', [BarangController::class, 'proses_tambah'])->name('barang.store');
        Route::post('/admin/barang/proses_ubah/{barang}', [BarangController::class, 'proses_ubah']);
        Route::post('/admin/barang/proses_hapus/{barang}', [BarangController::class, 'proses_hapus']);
    });

    Route::middleware(['checkRoleUser:/customer,menu'])->group(function () {
        // Customer
        Route::resource('/admin/customer', \App\Http\Controllers\Admin\CustomerController::class);
        Route::get('/admin/customer/show/', [CustomerController::class, 'show'])->name('customer.getcustomer');
        Route::post('/admin/customer/proses_tambah/', [CustomerController::class, 'proses_tambah'])->name('customer.store');
        Route::post('/admin/customer/proses_ubah/{customer}', [CustomerController::class, 'proses_ubah']);
        Route::post('/admin/customer/proses_hapus/{customer}', [CustomerController::class, 'proses_hapus']);
    });

    Route::middleware(['checkRoleUser:/supplier,menu'])->group(function () {
        // Supplier
        Route::resource('/admin/supplier', \App\Http\Controllers\Admin\SupplierController::class);
        Route::get('/admin/supplier/show/', [SupplierController::class, 'show'])->name('supplier.getsupplier');
        Route::post('/admin/supplier/proses_tambah/', [SupplierController::class, 'proses_tambah'])->name('supplier.store');
        Route::post('/admin/supplier/proses_ubah/{supplier}', [SupplierController::class, 'proses_ubah']);
        Route::post('/admin/supplier/proses_hapus/{supplier}', [SupplierController::class, 'proses_hapus']);
    });

    Route::middleware(['checkRoleUser:/barang-masuk,submenu'])->group(function () {
        // Barang Masuk
        Route::resource('/admin/barang-masuk', \App\Http\Controllers\Admin\BarangmasukController::class);
        Route::get('/admin/barang-masuk/show/', [BarangmasukController::class, 'show'])->name('barang-masuk.getbarang-masuk');
        Route::post('/admin/barang-masuk/proses_tambah/', [BarangmasukController::class, 'proses_tambah'])->name('barang-masuk.store');
        Route::post('/admin/barang-masuk/proses_ubah/{barangmasuk}', [BarangmasukController::class, 'proses_ubah']);
        Route::post('/admin/barang-masuk/proses_hapus/{barangmasuk}', [BarangmasukController::class, 'proses_hapus']);
        Route::get('/admin/barang/getbarang/{id}', [BarangController::class, 'getbarang']);
        Route::get('/admin/barang/listbarang/{param}', [BarangController::class, 'listbarang']);
    });

    Route::middleware(['checkRoleUser:/lap-barang-masuk,submenu'])->group(function () {
        // Barang Keluar
        Route::resource('/admin/barang-keluar', \App\Http\Controllers\Admin\BarangkeluarController::class);
        Route::get('/admin/barang-keluar/show/', [BarangkeluarController::class, 'show'])->name('barang-keluar.getbarang-keluar');
        Route::post('/admin/barang-keluar/proses_tambah/', [BarangkeluarController::class, 'proses_tambah'])->name('barang-keluar.store');
        Route::post('/admin/barang-keluar/proses_ubah/{barangkeluar}', [BarangkeluarController::class, 'proses_ubah']);
        Route::post('/admin/barang-keluar/proses_hapus/{barangkeluar}', [BarangkeluarController::class, 'proses_hapus']);
    });

    Route::middleware(['checkRoleUser:/lap-barang-masuk,submenu'])->group(function () {
        // Laporan Barang Masuk
        Route::resource('/admin/lap-barang-masuk', \App\Http\Controllers\Admin\LapBarangMasukController::class);
        Route::get('/admin/lapbarangmasuk/print/', [LapBarangMasukController::class, 'print'])->name('lap-bm.print');
        Route::get('/admin/lapbarangmasuk/pdf/', [LapBarangMasukController::class, 'pdf'])->name('lap-bm.pdf');
        Route::get('/admin/lap-barang-masuk/show/', [LapBarangMasukController::class, 'show'])->name('lap-bm.getlap-bm');
    });

    Route::middleware(['checkRoleUser:/lap-barang-keluar,submenu'])->group(function () {
        // Laporan Barang Keluar
        Route::resource('/admin/lap-barang-keluar', \App\Http\Controllers\Admin\LapBarangKeluarController::class);
        Route::get('/admin/lapbarangkeluar/print/', [LapBarangKeluarController::class, 'print'])->name('lap-bk.print');
        Route::get('/admin/lapbarangkeluar/pdf/', [LapBarangKeluarController::class, 'pdf'])->name('lap-bk.pdf');
        Route::get('/admin/lap-barang-keluar/show/', [LapBarangKeluarController::class, 'show'])->name('lap-bk.getlap-bk');
    });

    Route::middleware(['checkRoleUser:/lap-stok-barang,submenu'])->group(function () {
        // Laporan Stok Barang
        Route::resource('/admin/lap-stok-barang', \App\Http\Controllers\Admin\LapStokBarangController::class);
        Route::get('/admin/lapstokbarang/print/', [LapStokBarangController::class, 'print'])->name('lap-sb.print');
        Route::get('/admin/lapstokbarang/pdf/', [LapStokBarangController::class, 'pdf'])->name('lap-sb.pdf');
        Route::get('/admin/lap-stok-barang/show/', [LapStokBarangController::class, 'show'])->name('lap-sb.getlap-sb');
    });

    // Arsip Stock Opname
    Route::middleware(['checkRoleUser:/lap-arsip-stkOp,submenu'])->group(function () {
        Route::resource('/admin/lap-arsip-stkOp', ArsipStockOpnameController::class);
        Route::get('/admin/lap-arsip-stkOp', [ArsipStockOpnameController::class, 'index'])->name('arsip-stock-opname.index');
        Route::get('/admin/arsip-stock-opname/{id}', [ArsipStockOpnameController::class, 'detail'])->name('arsip-stock-opname.detail');
        Route::get('/admin/arsip-stock-opname/{id}/print', [ArsipStockOpnameController::class, 'print'])->name('arsip-stock-opname.print');
        Route::get('/admin/arsip-stock-opname/{id}/pdf', [ArsipStockOpnameController::class, 'pdf'])->name('arsip-stock-opname.pdf');
    });

    Route::middleware(['checkRoleUser:1,othermenu'])->group(function () {

        Route::middleware(['checkRoleUser:2,othermenu'])->group(function () {
            // Menu
            Route::resource('/admin/menu', \App\Http\Controllers\Master\MenuController::class);
            Route::post('/admin/menu/hapus', [MenuController::class, 'hapus']);
            Route::get('/admin/menu/sortup/{sort}', [MenuController::class, 'sortup']);
            Route::get('/admin/menu/sortdown/{sort}', [MenuController::class, 'sortdown']);
        });

        Route::middleware(['checkRoleUser:3,othermenu'])->group(function () {
            // Role
            Route::resource('/admin/role', \App\Http\Controllers\Master\RoleController::class);
            Route::get('/admin/role/show/', [RoleController::class, 'show'])->name('role.getrole');
            Route::post('/admin/role/hapus', [RoleController::class, 'hapus']);
        });

        Route::middleware(['checkRoleUser:4,othermenu'])->group(function () {
            // List User
            Route::resource('/admin/user', \App\Http\Controllers\Master\UserController::class);
            Route::get('/admin/user/show/', [UserController::class, 'show'])->name('user.getuser');
            Route::post('/admin/user/hapus', [UserController::class, 'hapus']);
        });

        Route::middleware(['checkRoleUser:5,othermenu'])->group(function () {
            // Akses
            Route::get('/admin/akses/{role}', [AksesController::class, 'index']);
            Route::get('/admin/akses/addAkses/{idmenu}/{idrole}/{type}/{akses}', [AksesController::class, 'addAkses']);
            Route::get('/admin/akses/removeAkses/{idmenu}/{idrole}/{type}/{akses}', [AksesController::class, 'removeAkses']);
            Route::get('/admin/akses/setAll/{role}', [AksesController::class, 'setAllAkses']);
            Route::get('/admin/akses/unsetAll/{role}', [AksesController::class, 'unsetAllAkses']);
        });

        Route::middleware(['checkRoleUser:6,othermenu'])->group(function () {
            // Web
            Route::resource('/admin/web', \App\Http\Controllers\Master\WebController::class);
        });
        // Stock Opname Routes
    });

    Route::middleware(['checkRoleUser:/stkopname,menu'])->group(function () {
        // Halaman utama daftar request stockopname
        Route::get('/admin/stkopname', [StockOpnameControllers::class, 'index'])
            ->name('stock-opname.index');
        // Halaman detail request stockopname
        Route::get('/admin/stkopname/{id}', [StockOpnameControllers::class, 'show'])
            ->name('stock-opname.show');
        // API untuk update status request (approve/reject)
        Route::post('/admin/opname/update-status/{id}', [StockOpnameControllers::class, 'updateStatus'])
            ->name('stock-opname.update-status');
    });


    Route::middleware(['checkRoleUser:/picker,menu'])->group(function () {
        // Halaman utama picker (daftar request yang dibuat picker)
        Route::get('/admin/picker', [PickerController::class, 'index'])
            ->name('picker.index');

        // Halaman form tambah request baru
        Route::get('/admin/picker/create', [PickerController::class, 'create'])
            ->name('picker.create');

        // API untuk simpan request baru
        Route::post('/admin/picker/save', [PickerController::class, 'store'])
            ->name('picker.store');

        // Halaman detail request (untuk input stok aktual)
        Route::get('/admin/picker/opname/{id}', [PickerController::class, 'show'])
            ->name('picker.show');

        // API untuk update stok aktual
        Route::post('/admin/picker/opname/update-stock/{id}', [PickerController::class, 'updateStock'])
            ->name('picker.updateStock');

        // API untuk approve request (tidak digunakan dalam flow ini)
        Route::post('/admin/picker/opname/approve/{id}', [PickerController::class, 'approve'])
            ->name('picker.approve');

        // API untuk reject request (tidak digunakan dalam flow ini)
        Route::post('/admin/picker/opname/reject/{id}', [PickerController::class, 'reject'])
            ->name('picker.reject');
    });


    //list datatables
    //admin
    //stockopname
    Route::get('/opname/getdata', [StockOpnameControllers::class, 'getdata'])->name('stock-opname.getdata');


    //picker
    Route::get('/picker/opname/data', [PickerController::class, 'getStockOpname'])->name('picker.getstockopname');
});
