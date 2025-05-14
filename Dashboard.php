<html lang="en">
<?php 
include './database/checkAdmin.php';


?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Header</title>
    <link rel="stylesheet" href="styles/sidebar.css">
    <link rel="stylesheet" href="styles/admin.css">
    <link rel="stylesheet" href="styles/adminheader.css">
</head>

<body>
    <div class="dashboard">
        <?php 
        include './components/adminSidebar.php'
        ?>
        <div class="mainTab">
        <?php
            include './components/adminHeader.php'
                ?>
        </div>
    </div>
    </div>
    <script>
        function togglePopover() {
            var popover = document.getElementById('popover');
            if (popover.style.display === 'none' || popover.style.display === '') {
                popover.style.display = 'block';
            } else {
                popover.style.display = 'none';
            }
        }
        function logout() {
            window.location.href = '/';
        }
    </script>
</body>

</html>