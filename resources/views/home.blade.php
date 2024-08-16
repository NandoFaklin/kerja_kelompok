<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Home</title>
    <style>
        /* Tambahkan CSS untuk styling */
        /* Tambahkan CSS untuk styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        header {
            background-color: #f0f0f0;
            padding: 0;
        }

        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-title {
            color: #6c9bee; /* Biru muda */
            font-family: 'Arial';
            font-size: 1.5rem;
            margin-right: 20px; /* Memberikan jarak antara header title dan tab */
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 10px; /* Jarak antar tab dan logout button */
        }

        nav ul li {
            margin-right: 0;
        }

        nav ul li a {
            text-decoration: none;
            padding: 10px 20px;
            display: inline-block;
            color: #6c9bee; /* Warna tulisan hijau */
            background-color: #fff; /* Latar belakang putih */
            border: none; /* Menghilangkan border */
            border-radius: 5px;
        }

        nav ul li a.active {
            background-color: #6c9bee; /* Latar belakang hijau untuk tab aktif */
            color: #fff; /* Tulisan putih untuk tab aktif */
        }

        nav ul li a:hover {
            background-color: #ddd;
        }

        .logout {
            color: #dc3545;
            background-color: #fff;
            border: none; /* Menghilangkan border */
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none; /* Hapus garis bawah dari link */
        }

        .logout:hover {
            background-color: #dc3545;
            color: #fff;
        }

        .card {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card .button-group {
            display: flex;
            gap: 10px; /* Jarak antar tombol */
        }

        .card button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .card .validate-button {
            background-color: #6c9bee;
            color: #fff;
        }

        .card .validate-button:hover {
            background-color: #6c9bee;
        }

        .card .delete-button {
            background-color: #dc3545;
            color: #fff;
        }

        .card .delete-button:hover {
            background-color: #c82333;
        }

    </style>
</head>
<body>
    <header>
        <nav>
            <div class="header-title">Admin Aplikasi Kerja Kelompok</div>
            <ul>
                <li><a href="#tab1" class="active">Validasi Users</a></li>
                <li><a href="#tab2">Tab 2</a></li>
                <li style="margin-left: auto;">
                    <form action="{{ url('/logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="logout">Logout</button>
                    </form>
                </li>
            </ul>
        </nav>
    </header>

    <div id="content">
        <div id="tab1" class="tab-content">
            <h2>Validasi Users</h2>
            <div id="user-list"></div>
        </div>
        <div id="tab2" class="tab-content" style="display:none;">
            <h2>Daftar Pengguna</h2>
            <div id="user-list-tab2"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch data dari server untuk tab 1
            fetch('/admin/validasi-users')
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        let userList = document.getElementById('user-list');
                        data.data.forEach(user => {
                            let card = document.createElement('div');
                            card.className = 'card';
                            
                            let userInfo = document.createElement('div');
                            userInfo.textContent = `${user.name} (${user.email}) - ${user.role}`;
                            
                            let buttonGroup = document.createElement('div');
                            buttonGroup.className = 'button-group'; // Tambahkan div untuk grup tombol
                            
                            let validateButton = document.createElement('button');
                            validateButton.className = 'validate-button';
                            validateButton.textContent = 'Validasi';
                            validateButton.addEventListener('click', function() {
                                let requestData = { id: user.id };
                                alert('Data yang dikirim: ' + JSON.stringify(requestData));
                                validateUser(user.id);
                            });

                            let deleteButton = document.createElement('button');
                            deleteButton.className = 'delete-button';
                            deleteButton.textContent = 'Delete';
                            deleteButton.addEventListener('click', function() {
                                let requestData = { id: user.id };
                                alert('Data yang dikirim: ' + JSON.stringify(requestData));
                                deleteValidasi(user.id);
                            });

                            buttonGroup.appendChild(validateButton);
                            buttonGroup.appendChild(deleteButton);
                            card.appendChild(userInfo);
                            card.appendChild(buttonGroup); // Tambahkan grup tombol ke kartu
                            userList.appendChild(card);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });

            // Fetch data dari server untuk tab 2
            fetch('/admin/showuser')
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        let userListTab2 = document.getElementById('user-list-tab2');
                        data.data.forEach(user => {
                            let card = document.createElement('div');
                            card.className = 'card';
                            
                            let userInfo = document.createElement('div');
                            userInfo.textContent = `${user.name} (${user.email}) - ${user.role}`;
                            
                            let deleteButton = document.createElement('button');
                            deleteButton.className = 'delete-button';
                            deleteButton.textContent = 'Delete';
                            deleteButton.addEventListener('click', function() {
                                let requestData = { id: user.id };
                                alert('Data yang dikirim: ' + JSON.stringify(requestData));
                                deleteUser(user.id);
                            });
                            
                            card.appendChild(userInfo);
                            card.appendChild(deleteButton); // Tambahkan tombol Delete ke kartu
                            userListTab2.appendChild(card);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });

            // Fungsi untuk melakukan validasi user
            function validateUser(userId) {
                fetch('/admin/registerValidasi', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ id: userId })
                })
                .then(response => {
                    alert('Status code: ' + response.status);
                    return response.json();
                })
                .then(data => {
                    alert('Respons server: ' + JSON.stringify(data));
                    if (data.status) {
                        alert('User berhasil divalidasi!');
                        location.reload(); // Refresh halaman setelah validasi
                    } else {
                        console.error('Validation failed:', data.message);
                        alert(`Gagal memvalidasi user: ${data.message}`);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memvalidasi user.');
                });
            }

            // Fungsi untuk menghapus validasi user
            function deleteValidasi(userId) {
                fetch('/admin/deleteValidasi', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ id: userId })
                })
                .then(response => {
                    alert('Status code: ' + response.status);
                    return response.json();
                })
                .then(data => {
                    alert('Respons server: ' + JSON.stringify(data));
                    if (data.status) {
                        alert('Validasi user berhasil dihapus!');
                        location.reload(); // Refresh halaman setelah penghapusan
                    } else {
                        console.error('Deletion failed:', data.message);
                        alert(`Gagal menghapus validasi user: ${data.message}`);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus validasi user.');
                });
            }

            // Fungsi untuk menghapus user
            function deleteUser(userId) {
                fetch('/admin/deleteUser', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ id: userId })
                })
                .then(response => {
                    alert('Status code: ' + response.status);
                    return response.json();
                })
                .then(data => {
                    alert('Respons server: ' + JSON.stringify(data));
                    if (data.status) {
                        alert('User berhasil dihapus!');
                        location.reload(); // Refresh halaman setelah penghapusan
                    } else {
                        console.error('Deletion failed:', data.message);
                        alert(`Gagal menghapus user: ${data.message}`);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus user.');
                });
            }

            // Mengelola tab switching
            document.querySelectorAll('nav ul li a').forEach(tab => {
                tab.addEventListener('click', function(event) {
                    event.preventDefault();
                    
                    // Hapus kelas aktif dari semua tab
                    document.querySelectorAll('nav ul li a').forEach(tab => {
                        tab.classList.remove('active');
                    });
                    
                    // Tambahkan kelas aktif pada tab yang dipilih
                    tab.classList.add('active');
                    
                    // Sembunyikan semua tab konten
                    document.querySelectorAll('.tab-content').forEach(tabContent => {
                        tabContent.style.display = 'none';
                    });
                    
                    // Tampilkan konten tab yang dipilih
                    document.querySelector(tab.getAttribute('href')).style.display = 'block';
                });
            });
        });
    </script>
</body>
</html>
