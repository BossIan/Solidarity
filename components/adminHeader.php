

<header class="header"
    style="background-color: white; height: 60px; cursor: pointer; display: flex; align-items: center; padding: 0 20px; position: relative">
    <div class="profile" style="display: flex; align-items: center; width: 100%; justify-content: space-between;">
        <div style="display: flex; align-items: center;">
            <img class="schoolLogo" src="./logo.png" alt="School Logo" style="height: 40px; margin-right: 10px;">
            <div class="text-profile">
                <p><?php echo $_SESSION['user_name']; ?></p>
                <p>Admin</p>
            </div>
        </div>
    </div>
</header>