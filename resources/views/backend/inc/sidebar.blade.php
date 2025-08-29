 <!-- ========== Left Sidebar Start ========== -->
 <div class="vertical-menu">

<div data-simplebar class="h-100">

    <!--- Sidemenu -->
    <div id="sidebar-menu">
        <!-- Left Menu Start -->
        <ul class="metismenu list-unstyled" id="side-menu">
            <li class="menu-title" key="t-menu">Menu</li>


            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="bx bx-cog"></i>
                    <span key="t-dashboards">Tənzimləmələr</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    {{-- <li>
                        <a href="{{ route('admin.languages.index') }}" key="t-default">
                            <i class="bx bx-globe"></i> Dillər
                        </a>
                    </li> --}}
                    <li>
                        <a href="{{ route('admin.translations.index') }}" key="t-default">
                            <i class="bx bx-globe"></i> Tərcümələr
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.settings.index') }}" key="t-default">
                            <i class="bx bx-globe"></i> Ayarlar
                        </a>
                    </li>
                  
                    
                   
                    <li>
                        <a href="{{ route('admin.catalogs.index') }}" key="t-default">
                            <i class="bx bx-book-content"></i> Kataloq
                        </a>
                    </li>


                    <li>
                        <a href="{{ route('admin.admins.index') }}" key="t-default">
                            <i class="bx bx-book-content"></i> Adminlər
                        </a>
                    </li>
                   
                   
                </ul>
            </li>
            



            <li class="menu-title" key="t-apps">Apps</li>


            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="bx bx-info-circle"></i>
                    <span key="t-chat">Ana Səhifə</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li>
                        <a href="{{ route('admin.brends.index') }}" class="waves-effect">
                            <i class="bx bx-purchase-tag-alt"></i>
                            <span key="t-brands">Brendlər</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.banners.index') }}" class="waves-effect">
                            <i class="bx bx-image-alt"></i>
                            <span key="t-banners">Banners</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.banner_details.index') }}" class="waves-effect">
                            <i class="bx bx-detail"></i>
                            <span key="t-banner-details">Banner detallar</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="bx bx-info-circle"></i>
                    <span key="t-chat">Haqqımızda</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li>
                        <a href="{{ route('admin.about.index') }}" class="waves-effect">
                            <i class="bx bx-info-circle"></i>
                             <span key="t-chat">Haqqımızda</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.teams.index') }}" key="t-default">
                            <i class="bx bx-group"></i> Komandamız
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.certificates.index') }}" key="t-default">
                            <i class="bx bx-certification"></i> Sertifikatlar
                        </a>
                    </li>
                </ul>
            </li>


            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="bx bx-layout"></i>
                    <span key="t-dashboards">Statik səhifələr</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li>
                        <a href="{{ route('admin.rating.index') }}" class="waves-effect">
                            <i class="bx bx-star"></i>
                            <span key="t-rating">Qiymətləndirmə</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.returnpolicy.index') }}" class="waves-effect">
                            <i class="bx bx-undo"></i>
                            <span key="t-return">Geri qaytarma siyasəti</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.complainmanagement.index') }}" class="waves-effect">
                            <i class="bx bx-credit-card"></i>
                            <span key="t-complain">Şikayətlərin idarə olunması</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.deliverypayment.index') }}" class="waves-effect">
                            <i class="bx bx-credit-card"></i>
                            <span key="t-delivery">Çatdırılma və ödəmə</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.ouronmap.index') }}" class="waves-effect">
                            <i class="bx bx-map"></i>
                            <span key="t-map">Biz xəritədə</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="bx bx-briefcase-alt-2"></i>
                    <span key="t-vacancies">Vakansiyalar</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li>
                        <a href="{{ route('admin.vacancies.index') }}" class="waves-effect">
                            <i class="bx bx-briefcase-alt-2"></i>
                            <span key="t-vacancies">Vakansiyalar</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.vacancy-share-socials.index') }}" key="t-default">
                            <i class="bx bx-share-alt"></i> Vakansiya share linkləri
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.vacancy-banner.index') }}" key="t-default">
                            <i class="bx bx-image-alt"></i> Vakansiya banner
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="bx bx-box"></i>
                    <span key="t-dashboards">Məhsullar</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li>
                        <a href="{{ route('admin.properties.index') }}" class="waves-effect">
                            <i class="bx bx-slider-alt"></i>
                            <span key="t-properties">Özəlliklər</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.sub-properties.index') }}" class="waves-effect">
                            <i class="bx bx-slider"></i>
                            <span key="t-sub-properties">Özəlliklər Alt</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.products.index') }}" class="waves-effect">
                            <i class="bx bx-cube"></i>
                            <span key="t-products">Məhsullar</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.weekly_selections.index') }}" class="waves-effect">
                            <i class="bx bx-star"></i>
                            <span key="t-weekly">Həftənin seçimləri</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.categories.index') }}" key="t-default">
                            <i class="bx bx-book-content"></i> Kateqoriyalar
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript: void(0);" class="has-arrow waves-effect">
                    <i class="bx bx-cart"></i>
                    <span key="t-dashboards">Sifarişlər</span>
                </a>
                <ul class="sub-menu" aria-expanded="false">
                    <li>
                        <a href="{{ route('admin.orders.index') }}" class="waves-effect">
                            <i class="bx bx-receipt"></i>
                            <span key="t-orders">Sifarişlər</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.cities.index') }}" key="t-default">
                            <i class="bx bx-buildings"></i> Şəhərlər
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.order_cancellation_reasons.index') }}" key="t-default">
                            <i class="bx bx-x-circle"></i> Sifarişlərin ləğvi səbəbləri
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="{{ route('admin.users.index') }}" class="waves-effect">
                    <i class="bx bx-group"></i>
                    <span key="t-chat">İstifadəçilər</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.blognews.index') }}" class="waves-effect">
                    <i class="bx bx-news"></i>
                    <span key="t-chat">Xəbərlər və yeniliklər</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('admin.social_links.index') }}" class="waves-effect">
                    <i class="bx bxl-facebook-square"></i>
                    <span key="t-social-links">Sosial linklər</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('admin.price-quotes.index') }}" class="waves-effect">
                    <i class="bx bx-money"></i>
                    <span key="t-price-quote">Qiymət təklifi al</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('admin.vacancy_applications.index') }}" class="waves-effect">
                    <i class="bx bx-envelope"></i>
                    <span key="t-vac-apps">Vakansiyaya müraciətlər</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.subscribers.index') }}" class="waves-effect">
                   <i class="bx bx-briefcase"></i>
                    <span key="t-vac-apps">Sayta abunə olanlar</span>
                </a>
            </li>
            
           


          



           
            



        </ul>
    </div>
    <!-- Sidebar -->
</div>
</div>
<!-- Left Sidebar End -->
