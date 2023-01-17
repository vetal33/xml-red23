<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">Menu</li>

                <li>
                    <a href="{{ route('map.index') }}" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards">Dashboards</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('xml-validator.index') }}" class="waves-effect">
                        <i class="bx bx-check-shield"></i>
                        <span key="t-chat">Xml Validator</span>
                    </a>
                </li>

                <li>
                    <a href="chat" class="waves-effect">
                        <i class="bx bx-shape-circle"></i>
                        <span key="t-chat">Feature convertor</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
