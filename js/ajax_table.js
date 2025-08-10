//mở modal và load dữ liệu AJAX
/**
 * @param {string} openBtnSelector - Nút mở modal
 * @param {string} modalSelector - ID modal
 * @param {string} closeBtnSelector - Nút đóng modal
 * @param {string} searchInputSelector - Ô tìm kiếm
 * @param {string} tableBodySelector - tbody để render dữ liệu
 * @param {string} sortThSelector - selector cho các <th> sort
 * @param {string} ajaxUrl - URL file PHP xử lý AJAX
 * @param {string} defaultSort - Giá trị sắp xếp mặc định
 * @param {string} defaultOrder - Thứ tự sắp xếp mặc định (asc/desc)
 * @param {object} extraParams - Tham số bổ sung (vd: id phòng ban, id project,...)
 */

function initAjaxTable({
  openBtnSelector,
  modalSelector,
  closeBtnSelector,
  searchInputSelector,
  tableBodySelector,
  sortThSelector,
  ajaxUrl,
  defaultSort = 'id',
  defaultOrder = 'asc',
  extraParams = {}
}) {
  let currentSort = defaultSort;
  let currentOrder = defaultOrder;

  function loadData() {
    const keyword = $(searchInputSelector).val();
    const params = {
      search: keyword,
      sort: currentSort,
      order: currentOrder,
      ...extraParams
    };

    $.get(ajaxUrl, params, function (data) {
      $(tableBodySelector).html(data);

      // Tìm bảng cha của tbody hiện tại
      const $table = $(tableBodySelector).closest('table');

      // Reset all icons chỉ trong bảng này
      $table.find(sortThSelector).find('i').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');

      // Update icon cho cột đang sort
      if (currentSort) {
        const icon = $table.find(`${sortThSelector}[data-sort="${currentSort}"] i`);
        if (currentOrder === 'asc') {
          icon.removeClass('fa-sort').addClass('fa-sort-up');
        } else {
          icon.removeClass('fa-sort').addClass('fa-sort-down');
        }
      }
    });
  }

  if (openBtnSelector) {
    $(openBtnSelector).click(function () {
      $(modalSelector).show();
      loadData();
    });
  } else {
    // Không dùng modal → load luôn
    loadData();
  }

  $(closeBtnSelector).click(function () {
    $(modalSelector).hide();
  });

  $(searchInputSelector).on('keyup', function () {
    loadData();
  });

  $(document).on('click', sortThSelector, function () {
    const clickedSort = $(this).data('sort');
    let icon = $(this).find('i');

    if (currentSort === clickedSort) {
      currentOrder = currentOrder === 'asc' ? 'desc' : 'asc';
    } else {
      currentSort = clickedSort;
      currentOrder = 'asc';
    }

    // Reset all icons
    $(sortThSelector).find('i').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');

    // Update icon for current column
    if (currentOrder === 'asc') {
      icon.removeClass('fa-sort').addClass('fa-sort-up');
    } else {
      icon.removeClass('fa-sort').addClass('fa-sort-down');
    }

    loadData();
  });

}