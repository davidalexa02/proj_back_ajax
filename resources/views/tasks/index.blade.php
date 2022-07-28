<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tasks List
        </h2>
    </x-slot>
    <div>
        <div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="block mb-8">
                <!-- <a href="{{ route('tasks.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Add Task</a> -->
                <button type="button" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded add">Add Task</button>
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">


                            <table id="datatable" class="table min-w-full divide-y divide-gray-200 w-full">
                                <thead>
                                    <tr>
                                        <th scope="col" width="50" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            ID
                                        </th>
                                        <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Description
                                        </th>
                                        <th scope="col" width="200" class="px-6 py-3 bg-gray-50">

                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                </tbody>
                            </table>

                            <div class="modal" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <form class="form" action="" method="POST">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">New Task</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="id">

                                                <div class="form-group">
                                                    <label for="name">Name</label>
                                                    <input type="text" name="name" class="form-control input-sm">
                                                </div>
                                                <div class="form-group">
                                                    <label for="phone">Phone</label>
                                                    <input type="text" name="phone" class="form-control input-sm">
                                                </div>
                                                <div class="form-group">
                                                    <label for="dob">DOB</label>
                                                    <input type="date" name="dob" class="form-control input-sm">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary btn-save">Save</button>
                                                <button type="button" class="btn btn-primary btn-update">Update</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
                            <script>
                                $(document).ready(function() {

                                    $('#datatable').DataTable({
                                        "processing": true,
                                        "serverSide": true,
                                        "ajax": "{{ route('tasks.index') }}",
                                        "columns": [{
                                                "data": "id"
                                            },
                                            {
                                                "data": "description"
                                            }
                                        ]
                                    });
                                    var token = ''

                                    var table = $('#datatable').DataTable({
                                        ajax: '',
                                        serverSide: true,
                                        processing: true,
                                        aaSorting: [
                                            [0, "desc"]
                                        ],
                                        columns: [{
                                            data: 'description',
                                            name: 'description'
                                        }, ]
                                    });

                                    $('.btnAdd').click(function() {
                                        $('.modal').modal()
                                        $('.form').trigger('reset')
                                        $('.modal').find('.modal-title').text('Add New')
                                        $('.btnSave').show();
                                        $('.btnUpdate').hide()
                                    })

                                    $('.btnSave').click(function(e) {
                                        e.preventDefault();
                                        var data = $('.form').serialize()
                                        console.log(data)
                                        $.ajax({
                                            type: "POST",
                                            url: "",
                                            data: data + '&_token=' + token,
                                            success: function(data) {
                                                if (data.success) {
                                                    table.draw();
                                                    $('.form').trigger("reset");
                                                    $('.modal').modal('hide');
                                                } else {
                                                    alert('Delete Fail');
                                                }
                                            }
                                        }); //end ajax
                                    })


                                    $(document).on('click', '.btn-edit', function() {
                                        $('.btnSave').hide();
                                        $('.btnUpdate').show();


                                        $('.modal').find('.modal-title').text('Update Record')
                                        $('.modal').find('.modal-footer button[type="submit"]').text('Update')

                                        var rowData = table.row($(this).parents('tr')).data()

                                        $('.form').find('input[name="description"]').val(rowData.description)

                                        $('.modal').modal()
                                    })

                                    $('.btnUpdate').click(function() {
                                        if (!confirm("Are you sure?")) return;
                                        var formData = $('.form').serialize() + '&_method=PUT&_token=' + token
                                        var updateId = $('.form').find('input[name="id"]').val()
                                        $.ajax({
                                            type: "POST",
                                            url: "/" + updateId,
                                            data: formData,
                                            success: function(data) {
                                                if (data.success) {
                                                    table.draw();
                                                    $('.modal').modal('hide');
                                                }
                                            }
                                        }); //end ajax
                                    })


                                    $(document).on('click', '.btn-delete', function() {
                                        if (!confirm("Are you sure?")) return;

                                        var rowid = $(this).data('rowid')
                                        var el = $(this)
                                        if (!rowid) return;


                                        $.ajax({
                                            type: "POST",
                                            dataType: 'JSON',
                                            url: "/" + rowid,
                                            data: {
                                                _method: 'delete',
                                                _token: token
                                            },
                                            success: function(data) {
                                                if (data.success) {
                                                    table.row(el.parents('tr'))
                                                        .remove()
                                                        .draw();
                                                }
                                            }
                                        }); //end ajax
                                    })
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>