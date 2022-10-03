$(document).ready(function () {
    $.fn.dataTable.ext.search.push(
        function (settings, searchData) {
            var levels = $('input:checkbox[name="level"]:checked').map(function () {
                return this.value;
            }).get();

            if (levels.length === 0) {
                return true;
            }

            return levels.indexOf(searchData[2]) !== -1;
        }
    );

    var table = $('#logsTable').DataTable({
        ajax: {
            url: '/admin/log_lines/0',
            dataSrc: 'log'
        },
        columns: [
            {data: 'dateTime'},
            {data: 'channel'},
            {
                data: 'level',
                render: function (data) {
                    return '<div class="' + data.toLowerCase() + ' d-inline-block rounded shadow-sm p-3 text-center w-100">' + data + '</div>'
                },
            },
            {
                data: 'message',
                render: function (data) {
                    return '<div class="row h-100 mw-100 justify-content-between">\n' +
                        '<div class="col-9 text-wrap">\n' +
                        '<code class="text-danger">' + data + '</code>\n' +
                        '</div>\n' +
                        '<div class="col-2 text-center">\n' +
                        '<button type="button" class="btn shadow-sm btn-primary"\n' +
                        ' data-toggle="modal"\n' +
                        ' data-target="#exampleModal">\n' +
                        '<i class="fas fa-fw fa-info"></i> Excepiton\n' +
                        '</button>\n' +
                        '</div>\n' +
                        '</div>';
                },
            },
        ],
        columnDefs: [
            {
                targets: '_all',
                className: 'p-3',
            }
        ],
    });

    function syntaxHighlight(json) {
        if (typeof json != 'string') {
            json = JSON.stringify(json, undefined, 2);
        }
        json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
            var cls = 'number';
            if (/^"/.test(match)) {
                if (/:$/.test(match)) {
                    cls = 'key';
                } else {
                    cls = 'value';
                }
            } else if (/true|false/.test(match)) {
                cls = 'boolean';
            } else if (/null/.test(match)) {
                cls = 'null';
            }
            return '<span class="' + cls + '">' + match + '</span>';
        });
    }

    $('#logsTable tbody').on('click', 'button', function () {
        var data = table.row($(this).parents('tr')).data();
        $('#modalMessage').text(data.message);

        let contextJson = JSON.parse(data.context);

        $('#modalContext').html(syntaxHighlight(contextJson));
    });

    $('input:checkbox').on('change', function () {
        table.draw();
    });
});



