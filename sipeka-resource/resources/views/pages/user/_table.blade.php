<div class="d-flex align-items-center position-relative my-1">

    <span class="svg-icon svg-icon-1 position-absolute ms-6">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
            viewBox="0 0 24 24" version="1.1">
            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <rect x="0" y="0" width="24" height="24"></rect>
                <path
                    d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z"
                    fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                <path
                    d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z"
                    fill="#000000" fill-rule="nonzero"></path>
            </g>
        </svg>
    </span>

    <a href="#" id="search_reset" class="position-absolute end-0 pe-auto me-6 invisible">
        <span class="pe-auto svg-icon svg-icon-1"><svg xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                <g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)"
                    fill="#000000">
                    <rect fill="#000000" x="0" y="7" width="16" height="2" rx="1" />
                    <rect fill="#000000" opacity="0.5"
                        transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000) "
                        x="0" y="7" width="16" height="2" rx="1" />
                </g>
            </svg></span>
    </a>



    <input type="text" data-kt-docs-table-filter="search" class="form-control form-control-solid ps-15"
        placeholder="Cari Data">


</div>

<table id="kt_datatable" class="table table-row-dashed gy-5 gs-7 rounded">
    <thead>
        <tr class="fw-bolder fs-6 text-gray-800 text-uppercase px-7">
            <th class="text-center min-w-50 w-50px">Act</th>
            <th class="min-w-200px w-200px">Nama Lengkap</th>
            <th class="min-w-100px w-100px">Email</th>
            <th class="min-w-100px w-100px">Roles</th>
            <th class="min-w-90px w-90px">Diperbarui</th>
        </tr>
    </thead>
    <tbody class="text-gray-700 fw-bold">
    </tbody>
</table>

@push('scripts')
    <script>
        // init datatable error
        $.fn.dataTable.ext.errMode = 'none';
        // data table
        const dataTable = $("#kt_datatable")
            // datatable error handler
            .on('error.dt', function(e, settings, techNote, message) {
                swal(message, "error")
            })
            .DataTable({
                // responsive: true,
                searchDelay: 500,
                processing: true,
                stateSave: false,
                // scrollX: true,
                ordering: false,
                serverSide: true,
                ajax: `{{ route('users.datatable') }}`,
                // fixedColumns: {
                //   leftColumns: 1,
                // },
                columns: [
                    {
                        data: 'action',
                        className: 'text-center',
                        orderable: false,
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: null,
                        searchable: false,
                        render: function(data, type, row) {

                            return row.roles.map((item, index) =>
                                `<span class="badge badge-light-info">${item.name}</span>`).join(' ')
                        }
                    },
                    {
                        data: 'updated_at'
                    },
                ]
            });

        const searchResetButton = $('#search_reset')
        const filterSearch = document.querySelector('[data-kt-docs-table-filter="search"]');
        filterSearch.addEventListener('keyup', () => {
            searchResetButton.addClass('visible')
            searchResetButton.removeClass('invisible')
        });
        filterSearch.addEventListener('keyup', debounce((e) => {
            dataTable.search(e.target.value).draw();
        }, 500));
        searchResetButton.on('click', () => {
            searchResetButton.addClass('invisible')
            searchResetButton.removeClass('visible')
            filterSearch.value = ''
            dataTable.search('').draw();
        })
    </script>
@endpush
