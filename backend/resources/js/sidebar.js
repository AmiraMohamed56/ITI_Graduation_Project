document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const toggleBtn = document.getElementById('toggle-btn');
    const menuTexts = document.querySelectorAll('.menu-text');
    const logoText = document.getElementById('logo-text');
    const labels = ['main-label', 'healthcare-label', 'manage-label'];

    let isCollapsed = false;

    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            isCollapsed = !isCollapsed;

            if (isCollapsed) {
                sidebar.classList.remove('sidebar-expanded');
                sidebar.classList.add('sidebar-collapsed');
                mainContent.classList.remove('main-content-expanded');
                mainContent.classList.add('main-content-collapsed');

                // Hide text elements
                menuTexts.forEach(text => text.classList.add('hidden'));
                logoText.classList.add('hidden');
                labels.forEach(id => {
                    const element = document.getElementById(id);
                    if (element) element.classList.add('hidden');
                });
            } else {
                sidebar.classList.remove('sidebar-collapsed');
                sidebar.classList.add('sidebar-expanded');
                mainContent.classList.remove('main-content-collapsed');
                mainContent.classList.add('main-content-expanded');

                // Show text elements
                menuTexts.forEach(text => text.classList.remove('hidden'));
                logoText.classList.remove('hidden');
                labels.forEach(id => {
                    const element = document.getElementById(id);
                    if (element) element.classList.remove('hidden');
                });
            }
        });
    }
});
