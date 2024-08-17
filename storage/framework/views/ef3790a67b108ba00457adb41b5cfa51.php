<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ILHY | Case Management</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="icon" type="image/png" sizes="16x16" href="\logo.png">
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/> -->
    <!-- <link href="<?php echo e(asset('backend/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css"> -->
<!-- <link href="<?php echo e(asset('backend/css/bunnycss.css')); ?>" rel="stylesheet" type="text/css"> -->

  <!-- <link href="<?php echo e(asset('backend/css/all.min.css')); ?>" rel="stylesheet" type="text/css"/> -->

</head>

<body class="hold-transition sidebar-mini">
    <div id="app"></div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleMenuIcon = document.getElementById('toggleMenuIcon');
            const body = document.querySelector('body');

            // toggleMenuIcon.addEventListener('click', () => {
            //     if (body.classList.contains('sidebar-collapse')) {
            //         localStorage.setItem('sidebarState', 'expanded');
            //     } else {
            //         localStorage.setItem('sidebarState', 'collapsed');
            //     }
            // });

            const sidebarState = localStorage.getItem('sidebarState');
            if (sidebarState === 'collapsed') {
                body.classList.add('sidebar-collapse');
            }
        });
        window.user = <?php echo json_encode(auth()->user(), 15, 512) ?>;
       
    </script>
</body>

</html>
<?php /**PATH /home/marcus/shared/inventory/resources/views/admin/layouts/app.blade.php ENDPATH**/ ?>