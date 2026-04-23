<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('logo.png') ?>">
    <title><?= $title . '- Notion LMS' ?? 'Notion LMS' ?></title>
    <link rel="stylesheet" href="/css/app.css">
    <script src="/js/sweetalert2.min.js"></script>
    <meta name="csrf_header_name" content="<?= csrf_header() ?>">
    <meta name="csrf_hash" content="<?= csrf_hash() ?>">
</head>
<body class="bg-gray-100">

    <?= $this->renderSection('content') ?>

    <script>
        <?php if(session()->getFlashdata('success')) : ?>
            Swal.fire({
                title: "Berhasil!",
                text: "<?= session()->getFlashdata('success') ?>",
                icon: "success",
                timer: 3000,
                showConfirmButton: false
            });
        <?php endif; ?>

        <?php if(session()->getFlashdata('error')) : ?>
            Swal.fire({
                title: "Waduh!",
                text: "<?= session()->getFlashdata('error') ?>",
                icon: "error",
                timer: 3000,
                showConfirmButton: false
            });
        <?php endif; ?>
    </script>

    <?= $this->renderSection('scripts') ?>

</body>
</html>
