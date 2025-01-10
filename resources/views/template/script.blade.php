<!-- Bootstrap core JavaScript-->
<script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>

<script src="//cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
<script>
    let table = new DataTable('#myTable');

    document.querySelector('#table-search').appendChild(document.getElementsByClassName('dt-layout-row')[0]);
    document.querySelector('#table-pagination').appendChild(document.getElementsByClassName('dt-layout-row')[2]);

    const tableSearch = document.getElementById('table-search');
    const dtLayoutRow = tableSearch.querySelector('.dt-layout-row');
    dtLayoutRow.style.display = 'flex';
    dtLayoutRow.style.justifyContent = 'space-between';
    dtLayoutRow.style.width = '100%';
</script>
