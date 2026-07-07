document.addEventListener('DOMContentLoaded', function () {
    var items = document.querySelectorAll('.main-nav-list > li.has-submenu');

    items.forEach(function (item) {
        var link = item.querySelector('.nav-link');

        item.addEventListener('mouseenter', function () {
            closeAll();
            item.classList.add('is-open');
        });

        item.addEventListener('mouseleave', function () {
            item.classList.remove('is-open');
        });

        // Suporte a toque/teclado: alterna o submenu ao ativar o link principal.
        link.addEventListener('click', function (event) {
            if (!isTouchDevice()) {
                return;
            }

            if (!item.classList.contains('is-open')) {
                event.preventDefault();
                closeAll();
                item.classList.add('is-open');
            }
        });
    });

    document.addEventListener('click', function (event) {
        if (!event.target.closest('.has-submenu')) {
            closeAll();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeAll();
        }
    });

    function closeAll() {
        items.forEach(function (item) {
            item.classList.remove('is-open');
        });
    }

    function isTouchDevice() {
        return window.matchMedia('(hover: none)').matches;
    }
});
