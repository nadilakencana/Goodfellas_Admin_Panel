<div class="col-12 px-0 col-sm-8 col-md-4 fixed-top mx-auto" id="mainNavbar">
    <div class="d-flex gap-3 justify-content-between align-items-center nav-fixed p-3  ">
        <div class="logo-nav">
            <img src="{{ asset('asset/assets/image/LOGO BLACK.png') }}" alt="" width="23" height="23">
        </div>
        <div class="search-menu w-100 relative">
            <input type="text" class="form-control" placeholder="Search your menu">
            <img class="icon-search" src="{{ asset('asset/assets/image/icon _search_.png') }}" alt=""
                width="20" height="20">
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mainNavbar = document.getElementById('mainNavbar');
        const scrollThreshold = 50; 

        window.addEventListener('scroll', () => {
            if (window.scrollY > scrollThreshold) {
                mainNavbar.classList.add('scrolled');
            } else {
                mainNavbar.classList.remove('scrolled');
            }
        });

      
        if (window.scrollY > scrollThreshold) {
            mainNavbar.classList.add('scrolled');
        }
    });
    
</script>
