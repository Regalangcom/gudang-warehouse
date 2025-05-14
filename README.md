jadi role yang ada saat ini adalah super admin , picker , dan kepala gudang,

dari saat ini sudah memiliki input data barang yang tercatat , lalu saat user picker login maka terdapat satu fitur yaitu data penyesuaian , jadi fitur ini di gunakan agar saaat picker melakukan stockOpname maka picker me request data ke super admin , lalu super admin menerima req dari picker dan mengirimkan data product yang tercatat beserta stock nya bro , sehingga output nya nanti picker bisa mendapatkan data field id , nama product , dan colum stock , namu coloum stock disini masih berisi field kosong , sehingga saat picker trigger melalui checkbox maka field stock tersebut bisa terlihat stock yang tercatat bro

MENGATUR HAK AKSES
VIEW:
`INSERT INTO tbl_akses (menu_id, role_id, akses_type, created_at, updated_at) 
VALUES (1748872442, 1, 'view', NOW(), NOW());`

CREATE
`INSERT INTO tbl_akses (menu_id, role_id, akses_type, created_at, updated_at) 
VALUES (1748872442, 1, 'create', NOW(), NOW());`

UPDATE
`INSERT INTO tbl_akses (menu_id, role_id, akses_type, created_at, updated_at) 
VALUES (1748872442, 1, 'update', NOW(), NOW());`

DELETE
`INSERT INTO tbl_akses (menu_id, role_id, akses_type, created_at, updated_at) 
VALUES (1748872442, 1, 'delete', NOW(), NOW());`

ADMIN :
username : superadmin2
pw : 123456789

PICKER : Hudas
pw : 12345678

Kepala gudang
pw : 12345678

req ajak pake
