<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md mt-8">

    <h2 class="text-3xl font-bold text-gray-800 mb-4"><?= $materi['judul'] ?></h2>
    <div class="bg-blue-50 text-blue-800 p-3 rounded mb-6">
        Kode Mata Kuliah: <strong><?= $materi['kode_mk'] ?></strong>
    </div>

    <div class="mt-6">
        <?php if(empty($isiMateri)): ?>
            <p class="text-gray-500 italic">Materi belum tersedia sob.</p>
        <?php else: ?>
            <?php foreach($isiMateri as $c): ?>
                <div class="mb-8 border-b pb-4">
                    <h3 class="text-2xl font-semibold text-gray-700 mb-2"><?= $c['bab'] ?></h3>
                    <h4 class="text-xl text-gray-500 mb-4"><?= $c['sub_bab'] ?></h4>

                    <div class="prose prose-blue max-w-none prose-p:my-2 prose-headings:my-3 prose-ul:my-1 prose-li:my-0 prose-img:-my-5  prose-pre:-my-10 text-gray-800 ">
                        <?= $c['isi_materi'] ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/monokai-sublime.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
<script src="/js/sweetalert2.min.js"></script>

<style>
    /* CSS tambahan untuk merapikan tombol Copy */
    .code-container {
        position: relative;
        margin-bottom: 1.5rem;
    }
    .btn-copy {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background-color: rgba(255, 255, 255, 0.2);
        color: #fff;
        border: none;
        padding: 0.25rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .btn-copy:hover {
        background-color: rgba(255, 255, 255, 0.4);
    }


</style>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {

        // 1. Cari semua blok kode dari Quill
        document.querySelectorAll('.ql-syntax').forEach((block) => {

            // 2. Warnai kodenya pakai Highlight.js
            hljs.highlightElement(block);

            // 3. Bikin 'wadah' baru untuk menampung kode & tombol copy
            const container = document.createElement('div');
            container.className = 'code-container bg-[#23241f] rounded-lg overflow-hidden'; // Warna background monokai

            // Pindahkan blok kode ke dalam wadah baru
            block.parentNode.insertBefore(container, block);
            container.appendChild(block);

            // 4. Bikin tombol Copy (Pakai icon clipboard SVG)
            const button = document.createElement('button');
            button.className = 'btn-copy';
            button.innerHTML = `
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                </svg> Copy
            `;

            // 5. Logika saat tombol diklik
            button.addEventListener('click', async () => {
                try {
                    // Ambil teks asli dari dalam blok kode
                    const codeText = block.innerText;

                    // Salin ke clipboard browser
                    await navigator.clipboard.writeText(codeText);

                    // Ubah teks tombol sementara biar kelihatan sukses
                    button.innerHTML = '✅ Copied!';
                    setTimeout(() => {
                        button.innerHTML = `
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                            </svg> Copy
                        `;
                    }, 2000);

                } catch (err) {
                    console.error('Gagal copy: ', err);
                    Swal.fire('Error', 'Gagal menyalin kode. Browser Anda mungkin tidak mendukung fitur ini.', 'error');
                }
            });

            // Masukkan tombol ke dalam wadah (di atas kodenya)
            container.appendChild(button);

            // Sedikit styling untuk blok <pre> agar tidak tertutup tombol
            block.style.padding = "1rem";
            block.style.paddingTop = "2.5rem"; // Kasih ruang kosong di atas untuk tombol
            block.style.margin = "0";
            block.style.borderRadius = "0";
        });
    });
</script>
<?= $this->endSection() ?>
