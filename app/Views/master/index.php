<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

    <nav class="bg-white shadow-md sticky top-0 p-4 flex justify-between items-center mb-8 z-10">
        <h1 class="text-xl font-bold text-gray-800">Notion LMS - Dashboard Dosen</h1>
        <a href="/logout" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold transition">Logout</a>
    </nav>

    <div class="max-w-full mx-auto px-6">

        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded shadow-sm">
            <h3 class="font-bold text-blue-700">Cek Data dari API:</h3>
            <?php if(isset($makul['data']) && !empty($makul['data'])): ?>
            <ul class="list-disc ml-5 text-sm mt-2">
                <?php foreach($makul['data'] as $m): ?>
                    <li><?= $m['kdmk'] ?> - <?= $m['nama'] ?></li>
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
                <p class="text-red-500 text-sm mt-2">Gagal ngambil data mata kuliah dari server pusat.</p>
            <?php endif; ?>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md overflow-x-auto">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-lg font-bold text-gray-800">Daftar Modul Tutorial</h2>
                <a href="/master/create" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-semibold transition">+ Tambah Modul</a>
            </div>

            <table class="w-full text-left border-collapse" style="min-width: 1100px;">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-sm">
                        <th class="p-3 border-b w-10 text-center">No</th>
                        <th class="p-3 border-b">Judul Materi</th>
                        <th class="p-3 border-b w-32">Kode Matkul</th>
                        <th class="p-3 border-b w-48">Link Presentasi</th>
                        <th class="p-3 border-b w-48">Link Presentasi All</th>
                        <th class="p-3 border-b text-center" style="min-width: 380px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($tutorials)): ?>
                        <tr>
                            <td colspan="6" class="p-6 text-center text-gray-500 italic">Belum ada modul yang dibuat. Klik tombol hijau untuk menambah.</td>
                        </tr>
                    <?php else: ?>
                        <?php
                      $currentPage = $pager->getCurrentPage('tutorials');
                      $perPage = $pager->getPerPage('tutorials');
                      $startNumber = ($currentPage - 1) * $perPage;
                      ?>
                      <?php foreach($tutorials as $i => $t): ?>
                        <tr class="border-b hover:bg-gray-50 align-middle">

                            <td class="p-3 text-center text-gray-500 font-semibold text-sm">
                                <?= $startNumber + $i + 1 ?>
                            </td>

                            <td class="p-3 font-bold text-gray-800"><?= esc($t['judul']) ?></td>

                            <td class="p-3 text-blue-600 font-semibold"><?= esc($t['kode_mk']) ?></td>

                            <td class="p-3">
                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs border border-gray-200 block truncate" title="<?= esc($t['url_presentation']) ?>">
                                    <?= esc($t['url_presentation']) ?>
                                </span>
                            </td>

                            <td class="p-3">
                                <span class="bg-blue-50 text-blue-600 px-2 py-1 rounded text-xs border border-blue-100 block truncate" title="<?= esc($t['url_finished']) ?>">
                                    <?= esc($t['url_finished']) ?>
                                </span>
                            </td>

                            <td class="p-3">
                                <div class="flex gap-2 justify-center items-center flex-nowrap">
                                    <a href="/materi/<?= $t['url_presentation'] ?>" target="_blank"
                                       class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-1.5 rounded text-xs transition font-bold shadow-sm whitespace-nowrap">
                                        Lihat Perlangkah
                                    </a>
                                    <a href="/materi/<?= $t['url_finished'] ?>" target="_blank"
                                       class="bg-slate-600 hover:bg-slate-700 text-white px-3 py-1.5 rounded text-xs transition font-bold shadow-sm whitespace-nowrap">
                                        Lihat Lengkap
                                    </a>
                                    <a href="/master/detail/<?= $t['id'] ?>"
                                       class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded text-xs transition whitespace-nowrap">
                                        Kelola Materi
                                    </a>
                                    <a href="/master/edit/<?= $t['id'] ?>"
                                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded text-xs transition whitespace-nowrap">
                                        Edit
                                    </a>
                                    <button onclick="konfirmasiHapus(<?= $t['id'] ?>, '<?= esc($t['judul']) ?>')"
                                       class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded text-xs transition whitespace-nowrap cursor-pointer">
                                        Hapus
                                    </button>
                                </div>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php if($pager->getPageCount('tutorials') > 1): ?>
            <div class="mt-6 flex justify-center">
                <?= $pager->links('tutorials', 'tailwind_pagination') ?>
            </div>
            <?php endif; ?>
        </div>

    </div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  function konfirmasiHapus(id, judulModul) {
        Swal.fire({
            title: 'Apakah Anda Yakin Menghapus Modul ini?',
            text: "Modul '" + judulModul + "' dan semua sub-babnya akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Gunakan fetch untuk POST request
                fetch('/master/delete/' + id, {
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
                        Swal.fire('Error', 'Gagal menghapus modul', 'error');
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
