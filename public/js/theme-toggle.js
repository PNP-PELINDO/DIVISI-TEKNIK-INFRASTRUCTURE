function updateDarkIcon() {
    const darkIcon = document.getElementById('dark-icon');
    if (!darkIcon) return;
    if (document.documentElement.classList.contains('dark')) {
        darkIcon.classList.remove('fa-moon');
        darkIcon.classList.add('fa-sun');
    } else {
        darkIcon.classList.remove('fa-sun');
        darkIcon.classList.add('fa-moon');
    }
}

function toggleDarkMode() {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('dark-mode', isDark);
    updateDarkIcon();
}

// Initial icon update
document.addEventListener('DOMContentLoaded', updateDarkIcon);
