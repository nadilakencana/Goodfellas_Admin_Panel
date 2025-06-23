  <div class="col-12 px-0 col-sm-8 col-md-4 fixed-bottom mx-auto p-0">
      {{-- <div class="col-12 px-0 col-sm-8 col-md-4 mx-auto fixed-bottom"> --}}
          <div class="px-4 py-3 bg-white h-100  shadow"
              style="border-top-left-radius: 23px; border-top-right-radius: 23px;">
              <div class="d-flex gap-2 h-100 justify-content-between">
                  <a href="{{route('Orders.customer')}}">
                      <div class="menu-nav d-flex flex-column align-items-center ">
                          <img src="{{ request()->routeIs('Orders.customer') ? '{{ asset('asset/assets/image/icon/Home-1.png') }}': '{{ asset('asset/assets/image/icon/Home.png') }}' }}" alt="" width="23"
                              height="23">
                          <span class="small {{ request()->routeIs('Orders.customer') ? 'text-black': 'text-grey' }}">Home</span>
                      </div>
                  </a>
                  <a href="">
                      <div class="menu-nav d-flex flex-column align-items-center ">
                          <img src="{{ asset('asset/assets/image/icon/Meal.png') }}" alt="" width="23"
                              height="23">
                          <span class="small {{ request()->routeIs('Orders.customer') ? 'text-black': 'text-grey' }}">Food</span>
                      </div>
                  </a>
                  <a href="">
                      <div class="menu-nav d-flex flex-column align-items-center ">
                          <img src="{{ asset('asset/assets/image/icon/Soda.png') }}" alt="" width="23"
                              height="23">
                          <span class="small {{ request()->routeIs('Orders.customer') ? 'text-black': 'text-grey' }}">Drink</span>
                      </div>
                  </a>
                  <a href="">
                      <div class="menu-nav d-flex flex-column align-items-center ">
                          <img src="{{ asset('asset/assets/image/icon/Shopping Cart.png') }}" alt=""
                              width="23" height="23">
                          <span class="small {{ request()->routeIs('Orders.customer') ? 'text-black': 'text-grey' }}">Your Order</span>
                      </div>
                  </a>
              </div>
          </div>
      {{-- </div> --}}
  </div>
