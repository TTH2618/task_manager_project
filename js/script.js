document.addEventListener('DOMContentLoaded', function () {
    // Hiển thị thông báo lỗi nếu có
    if (window.location.search.includes('error=')) {
        const params = new URLSearchParams(window.location.search);
        const error = params.get('error');
        if (error) {
            Swal.fire({
                icon: 'error',
                title: decodeURIComponent(error),
                showConfirmButton: true
            });
        }
    }
    // Hiển thị thông báo thành công nếu có
    if (window.location.search.includes('success=')) {
        const params = new URLSearchParams(window.location.search);
        const success = params.get('success');
        if (success) {
            Swal.fire({
                icon: 'success',
                title: decodeURIComponent(success),
                showConfirmButton: true
            });
        }
    }


    // Lấy tên trang hiện tại (không có .php)
    var currentPage = window.location.pathname.split('/').pop().replace('.php', '');

    // Đánh dấu nav active theo data-nav
    var navLinks = document.querySelectorAll("#navList a[data-nav]");
    var found = false;
    navLinks.forEach(function (link) {
        if (link.getAttribute('data-nav') === currentPage) {
            localStorage.setItem('lastNav', link.getAttribute('data-nav'));
            link.classList.add('active');
            found = true;
        }
        // Lưu lại nav khi click
        link.addEventListener('click', function () {
            localStorage.setItem('lastNav', link.getAttribute('data-nav'));
        });
    });
    // Nếu không tìm thấy nav phù hợp, chọn nav từ localStorage
    if (!found) {
        var lastNav = localStorage.getItem('lastNav');
        if (lastNav) {
            navLinks.forEach(function (link) {
                if (link.getAttribute('data-nav') === lastNav) {
                    link.classList.add('active');
                }
            });
        }
    }

    // Khi bấm vào profile thì xóa hết nav active
    var profileLink = document.querySelector('.user-p a[data-nav="profile"]');
    if (profileLink) {
        profileLink.addEventListener('click', function () {
            document.querySelectorAll("#navList a, #navList li").forEach(function (el) {
                el.classList.remove('active');
            });
            // Nếu muốn lưu trạng thái này, có thể xóa lastNav trong localStorage:
            localStorage.removeItem('lastNav');
        });
    }
});

$(document).ready(function () {
    // Dropdown handler sử dụng event delegation để áp dụng cho cả nội dung AJAX
    $(document).on('click', '.dropbtn', function (e) {
        e.stopPropagation();

        // Đóng tất cả dropdown khác
        $('.dropdown').not($(this).parent()).removeClass('open');

        // Toggle dropdown hiện tại
        $(this).parent().toggleClass('open');
    });

    // Đóng dropdown khi click ra ngoài
    $(document).on('click', function () {
        $('.dropdown').removeClass('open');
    });

    // Ngăn dropdown đóng khi click vào nội dung dropdown
    $(document).on('click', '.dropdown-content', function (e) {
        e.stopPropagation();
    });

});