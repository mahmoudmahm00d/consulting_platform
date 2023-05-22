<!--begin::Search-->
<div class="d-flex align-items-center position-relative my-1 w-100 order-2 order-sm-0">
    <span class="svg-icon svg-icon-1 position-absolute ms-6 "><svg xmlns="http://www.w3.org/2000/svg" width="24"
            height="24" viewBox="0 0 24 24" fill="none">
            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
            <path
                d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                fill="currentColor"></path>
        </svg></span>
    <input type="text" data-kt-docs-table-filter="search"
        class="form-control form-control-solid w-m-150px w-xs-70px w-lg-250px ps-15" placeholder="Search Stores" />
</div>
<!--end::Search-->

<div class="card">
    <table id="data-table" class="table table-striped table-row-bordered align-middle fs-6 gs-7 gy-5">
        {{ $slot }}
    </table>
</div>

@section('scripts')
    <script>
        let dt = $('#data-table').DataTable();
        let handleSearch = function() {
            const filterSearch = document.querySelector('[data-kt-docs-table-filter="search"]');
            filterSearch.addEventListener('keyup', function(e) {
                dt.search(e.target.value).draw();
            });
        }();
    </script>
@endsection
