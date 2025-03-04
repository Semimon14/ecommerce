document.addEventListener('DOMContentLoaded', function () {
    const currentUrl = window.location.href.split('?')[0].replace(/(-edit|-add|-list)\.php/, '.php');
    const menuItems = document.querySelectorAll('.sidebar-menu .nav-item a');

    menuItems.forEach(function (menuItem) {
        const menuItemUrl = menuItem.href.split('?')[0].replace(/(-edit|-add|-list)\.php/, '.php');
        if (menuItemUrl === currentUrl || (menuItemUrl.endsWith('/admin/index.php') && currentUrl.endsWith('/admin/'))) {
            menuItem.classList.add('active');
        }
    });
});
