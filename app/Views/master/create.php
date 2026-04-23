<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md mt-10" >
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Tambah Modul Baru</h2>
            <a href="/master" class="text-gray-500 hover:text-gray-700">← Kembali</a>
        </div>

        <form action="/master/store" method="POST">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Judul Materi</label>
                <input type="text" name="judul" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Belajar PHP" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Mata Kuliah</label>
                <select name="kode_mk" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white" required>
                    <option value="" disabled selected>-- Pilih Mata Kuliah --</option>

                    <?php if(isset($makul['data']) && !empty($makul['data'])): ?>
                        <?php foreach($makul['data'] as $m): ?>
                            <option value="<?= $m['kdmk'] ?>">
                                <?= $m['kdmk'] ?> - <?= $m['nama'] ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled>Gagal memuat data API / Token Expired</option>
                    <?php endif; ?>

                </select>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Email Pembuat</label>
                <input type="email" name="creator_email" class="w-full px-3 py-2 border rounded-lg bg-gray-100" value="<?= session()->get('email') ?? 'email_tidak_terbaca@sistem.com' ?>" readonly disabled>
                <p class="text-xs text-gray-500 mt-1">*Otomatis terisi dari sesi login.</p>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                Simpan Materi
            </button>
        </form>
    </div>

<?= $this->endSection(); ?>
