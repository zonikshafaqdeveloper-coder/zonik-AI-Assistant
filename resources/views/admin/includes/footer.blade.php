<!-- footer start-->
<div class="container-fluid">
    <footer class="footer">
        <div class="row">
            <div class="col-md-12 footer-copyright text-center">
               <p class="mb-0">
  © <script>document.write(new Date().getFullYear());</script> MOTOGLOW TECHNOLOGY PRIVATE LIMITED. All rights reserved.
</p>

            </div>
        </div>
    </footer>
</div>
<!-- footer End-->
</div>
<!-- index body end -->

</div>
<!-- Page Body End -->
</div>
<!-- page-wrapper End-->

<!-- Modal Start -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <h5 class="modal-title" id="staticBackdropLabel">Logging Out</h5>
                <p>Are you sure you want to log out?</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="button-box">
                    <button type="button" class="btn btn--no" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn  btn--yes btn-primary">Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal End -->

<!-- latest js -->
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>

<!-- Bootstrap js -->
<script src="{{ asset('js/bootstrap/bootstrap.bundle.min.js') }}"></script>

<!-- feather icon js -->
<script src="{{ asset('js/icons/feather-icon/feather.min.js') }}"></script>
<script src="{{ asset('js/icons/feather-icon/feather-icon.js') }}"></script>

<!-- scrollbar simplebar js -->
<script src="{{ asset('js/scrollbar/simplebar.js') }}"></script>
<script src="{{ asset('js/scrollbar/custom.js') }}"></script>

<!-- Sidebar jquery -->
<script src="{{ asset('js/config.js') }}"></script>

<!-- tooltip init js -->
<script src="{{ asset('js/tooltip-init.js') }}"></script>

<!-- Plugins JS -->
<script src="{{ asset('js/sidebar-menu.js') }}"></script>
<script src="{{ asset('js/notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('js/notify/index.js') }}"></script>

<!-- Apexchar js -->
<script src="{{ asset('js/chart/apex-chart/apex-chart1.js') }}"></script>
<script src="{{ asset('js/chart/apex-chart/moment.min.js') }}"></script>
<script src="{{ asset('js/chart/apex-chart/apex-chart.js') }}"></script>
<script src="{{ asset('js/chart/apex-chart/stock-prices.js') }}"></script>
<script src="{{ asset('js/chart/apex-chart/chart-custom1.js') }}"></script>


<!-- slick slider js -->
<script src="{{ asset('js/slick.min.js') }}"></script>
<script src="{{ asset('js/custom-slick.js') }}"></script>

<!-- customizer js -->
<script src="{{ asset('js/customizer.js') }}"></script>

<!-- ratio js -->
<script src="{{ asset('js/ratio.js') }}"></script>

<!-- sidebar effect -->
<script src="{{ asset('js/sidebareffect.js') }}"></script>

<!-- Theme js -->
<script src="{{ asset('js/script.js') }}"></script>

<script  rel="stylesheet" type="text/css" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"></script>

<link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


@if (session('success'))
    <script type="text/javascript">
        toastr.success("{{ session('success') }}", 'Success');
    </script>
@endif

@foreach ($errors->all() as $error)
    <script type="text/javascript">
        // alert('okk')
        toastr.error("{{ $error }}", 'Error');
    </script>
@endforeach
</body>

<!-- Mirrored from themes.pixelstrap.com/fastkart/back-end/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 29 Jul 2023 08:42:22 GMT -->

</html>





<script>
    $(document).ready(function() {
        $('#brands').mousedown(function(e) {
            if (e.target.tagName == 'OPTION' && e.ctrlKey) {
                e.preventDefault();

                $(this).focus();
                $(e.target).prop('selected', !$(e.target).prop('selected'));

                // Trigger change event
                $(this).trigger('change');
            }
        });
    });
</script>
