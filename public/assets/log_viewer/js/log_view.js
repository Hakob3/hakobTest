$(document).ready(function () {
    $.fn.dataTable.ext.search.push(
        function (settings, searchData, index, rowData, counter) {
            var levels = $('input:checkbox[name="level"]:checked').map(function () {
                return this.value;
            }).get();

            if (levels.length === 0) {
                return true;
            }

            if (levels.indexOf(searchData[2]) !== -1) {
                return true;
            }
            return false;
        }
    );

    var table = $('#myTable').DataTable();

    $('input:checkbox').on('change', function () {
        table.draw();
    });
});



