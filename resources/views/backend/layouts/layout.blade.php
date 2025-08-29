<!doctype html>
<html lang="en">

@include('backend.inc.head')

<body data-sidebar="dark">
    <div id="layout-wrapper">
        @include('backend.inc.header')
        @include('backend.inc.sidebar')
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>



            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> © <a target="_blank" class="text-uppercase" href="https://166tech.az/">166tech.az</a>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Design & Develop by <a target="_blank"  class="text-uppercase" href="https://166tech.az/">166tech.az</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

    </div>

    <div class="rightbar-overlay"></div>



    @include('backend.inc.footer')

    @stack('js')


</body>

</html>
