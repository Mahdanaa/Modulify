<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-lg mt-10 mb-20 border border-gray-100">

    <div class="flex justify-between items-center mb-8 border-b pb-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-800">Kelola Isi Materi</h2>
            <p class="text-gray-500 mt-2 text-lg">
                Modul: <span class="font-bold text-blue-600"><?= $tutorial['judul'] ?></span>
                <span class="text-sm bg-blue-100 text-blue-700 px-2 py-1 rounded-full ml-2"><?= $tutorial['kode_mk'] ?></span>
            </p>
        </div>
        <a href="/master" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-5 py-2 rounded-lg font-bold transition-colors shadow-sm">
            Kembali ke Katalog
        </a>
    </div>

    <div class="mb-6">
        <a href="/master/detail/create/<?= $tutorial['id'] ?>" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 inline-block">
            Tambah Sub-bab Baru
        </a>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-200 text-gray-700 uppercase text-sm">
                <tr>
                    <th class="p-4 font-bold text-center w-12">No</th>
                    <th class="p-4 font-bold">Bab</th>
                    <th class="p-4 font-bold">Sub-bab</th>
                    <th class="p-4 font-bold text-center">Status Tayang</th>
                    <th class="p-4 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if(empty($contents)): ?>
                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-500 bg-gray-50/50 italic">
                            Belum ada isi materi di modul ini. Yuk tambahkan sub-bab pertamamu!
                        </td>
                    </tr>
                <?php else: ?>
                    <?php
                          $currentPage = $pager->getCurrentPage('contents');
                          $perPage = $pager->getPerPage('contents');
                          $startNumber = ($currentPage - 1) * $perPage;
                    ?>
                    <?php foreach($contents as $i => $c): ?>
                        <tr class="hover:bg-blue-50/50 transition-colors">
                            <td class="p-4 text-center text-gray-500 font-semibold text-sm"><?= $startNumber + $i + 1 ?></td>
                            <td class="p-4 font-semibold text-gray-800"><?= $c['bab'] ?></td>
                            <td class="p-4 text-gray-600 font-medium"><?= $c['sub_bab'] ?></td>
                            <td class="p-4 text-center">
                                <button type="button" onclick="toggleStatus(<?= $c['id'] ?>, this)"
                                        class="px-4 py-2 rounded-full text-xs font-bold transition-all duration-300 focus:outline-none shadow-sm
                                        <?= $c['is_visible'] == 1 ? 'bg-blue-100 text-blue-700 hover:bg-blue-200' : 'bg-gray-200 text-gray-600 hover:bg-gray-300' ?>">
                                    <?= $c['is_visible'] == 1 ? 'Tampil' : 'Sembunyi' ?>
                                </button>
                            </td>

                            <td class="p-4 flex gap-2 justify-center items-center">
                                <a href="/master/detail/edit/<?= $c['id'] ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition-colors">
                                    Edit
                                </a>
                                <button type="button" onclick="konfirmasiHapusDetail(<?= $c['id'] ?>, '<?= $c['sub_bab'] ?>')" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm transition-colors">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($pager->getPageCount('contents') > 1): ?>
    <div class="mt-8 flex justify-center">
        <?= $pager->links('contents', 'tailwind_pagination') ?>
    </div>
    <?php endif; ?>

</div>
<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function toggleStatus(id, btnElement) {
        let teksAsli = btnElement.innerHTML;
        btnElement.innerHTML = 'Proses...';

        // Masukkan ke dalam Fetch API
        fetch('/master/detail/toggle/' + id, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if(data.success) {
                if(data.new_status == 1) {
                    btnElement.innerHTML = 'Tampil';
                    btnElement.className = 'px-4 py-2 rounded-full text-xs font-bold transition-all duration-300 focus:outline-none shadow-sm bg-blue-100 text-blue-700 hover:bg-blue-200';
                } else {
                    btnElement.innerHTML = 'Sembunyi';
                    btnElement.className = 'px-4 py-2 rounded-full text-xs font-bold transition-all duration-300 focus:outline-none shadow-sm bg-gray-200 text-gray-600 hover:bg-gray-300';
                }
            } else {
                Swal.fire('Gagal', data.message || 'Terjadi kesalahan pada sistem', 'error');
                btnElement.innerHTML = teksAsli;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Gagal menghubungi server: ' + error.message, 'error');
            btnElement.innerHTML = teksAsli;
        });
    }

    function konfirmasiHapusDetail(id, judulMateri) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Sub-bab '" + judulMateri + "' akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Gunakan fetch untuk POST request
                fetch('/master/detail/delete/' + id, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        window.location.reload();
                    } else {
                        Swal.fire('Error', 'Gagal menghapus materi', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Terjadi kesalahan', 'error');
                });
            }
        });
    }
</script>
<?= $this->endSection() ?>
