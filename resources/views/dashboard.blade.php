@extends('layout.master')

@section('content')



<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-3">
        <h1 class="m-0">Dashboard</h1>
      </div><!-- /.col -->
      <div class="col-sm-4">
        <div class="d-flex justify-content-center align-items-center gap-5">
          <label for="daterange" class="mx-3">Periode: </label>
          <input type="text" name="daterange" class="form-control mx-3" />
          <div class="filter mt-0">
            <img src="{{ asset('asset/assets/image/icon/filter_icon.png') }}" alt="" srcset="">
          </div>
        </div>

      </div>
      <!-- /.col -->
      <div class="col-sm-4">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>

      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->

</div>


<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
          <div class="info-box-content">
            <span class="info-box-text">New Order</span>
            <span class="info-box-number">
              {{ $pesanan_baru }}
            </span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->
      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
          <div class="info-box-content">
            <span class="info-box-text">Order Cancel</span>
            <span class="info-box-number">{{ $pesanan_batal }}</span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
          <div class="info-box-content">
            <span class="info-box-text">Order Completed</span>
            <span class="info-box-number">{{ $pesanan_selesai }}</span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
          <div class="info-box-content">
            <span class="info-box-text">Gross Sales</span>
            <span class="info-box-number">Rp. {{number_format( $allGrandSales, 0, ',','.')}}</span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
          <div class="info-box-content">
            <span class="info-box-text">Net Sales</span>
            <span class="info-box-number">Rp. {{number_format( $allGrandNet, 0, ',','.')}}</span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
          <div class="info-box-content">
            <span class="info-box-text">Gross Profit</span>
            <span class="info-box-number">Rp. {{number_format( $allGrandNet, 0, ',','.')}}</span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
          <div class="info-box-content">
            <span class="info-box-text">Average Sale Per Transaction</span>
            <span class="info-box-number">Rp. {{number_format( $avrg_order_bill, 0, ',','.')}}</span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
     
      <!-- /.col -->

      <!-- fix for small devices only -->

      <div class="clearfix hidden-md-up"></div>
      <!-- /.col -->
    </div>

  </div>
  <div class="container-fluid">
    <div class="card-body  mt-4 ">
      <div class="row">
        <div class="col-lg-12 col-md-6 col-sm-6 mb-4">
          <h5 class="mt-1 mb-2">DAILY GROSS SALES AMOUNT</h4>
          <div class="card-body tabel-summary" >
            <canvas id="grossSales" class="chart"></canvas>
          </div>
        </div>
        <div class="col-lg-12 col-md-6 col-sm-6 mb-4">
          <div class="row">
            <div class="col-lg-5 col-md-6"  width = "50%">
              <h5 class="mt-1 mb-2">DAY OF THE WEEK GROSS SALES AMOUNT</h4>
                <div class="card-body tabel-summary" >
                  <canvas id="grossSalesWeek" class="chart"></canvas>
                </div>
            </div>
            <div class="col-lg-7 col-md-6"  width = "50%">
              <h5 class="mt-1 mb-2">HOURLY OF THE WEEK GROSS SALES AMOUNT</h4>
                <div class="card-body tabel-summary" >
                  <canvas id="grossSalesHour" class="chart"></canvas>
                </div>
            </div>
          </div>
        </div>
        {{-- <div class="col-lg-12 col-md-6 col-sm-6 mb-4">
          <h5 class="mt-1 mb-2">Top Selling Items</h4>
          <div class="card-body tabel-summary" style="background: rgb(241, 241, 241)">
            <canvas id="topSellingItemsChart" class="chart"></canvas>
          </div>
        </div> --}}
        
      </div>
      
    </div>
    <div class="card-body tabel-summary ">
      <h3 class="mt-1 mb-2">Top Item Summary</h3>
      <table class="tebel-item">
        <thead>
          <tr class="row-itm">
            <th class="head-row-item list-nama">Item Sold</th>
            <th class="head-row-item">Sold</th>
            <th class="head-row-item">Gross Sales</th>
            <th class="head-row-item">Net Sales</th>
            <th class="head-row-item">Gross Profit</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($itemSalesMenu as $items)
          <tr class="body-data">
            <td class="data-item list-nama">{{ $items['Name']}}</td>
            <td class="data-item">{{ $items['itemSold'] }}</td>
            <td class="data-item">Rp. {{ number_format($items['GrossSalse'], 0, ',', '.') }}</td>
            <td class="data-item">Rp. {{ number_format($items['NetSales'], 0, ',', '.') }}</td>
            <td class="data-item">Rp. {{ number_format($items['NetSales'], 0, ',', '.') }}</td>
          </tr>
          @endforeach

        </tbody>
      </table>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-6">
          <h5 class="mt-1 mb-2">CATEGORY BY VOLUME</h5>
          <div class="card-body tabel-summary" >
            <canvas id="categoryByVolumeChart" width="330" height="330"></canvas>
          </div>
          
        </div>
        <div class="col-6">
          <h5 class="mt-1 mb-2">CATEGORY BY SALES</h5>
          <div class="card-body tabel-summary" >
            <canvas id="categoryBySalesChart" width="330" height="330"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body  tabel-summary">
      <h5 class="mt-1 mb-2">TOP ITEMS BY CATEGORY</h5>
      <div class="row gap-3">
       
        @foreach($chartData_items_cat as $category => $data)
          <div class="col-3">
              <h5 >{{ $category }}</h5>
              <canvas id="chart-{{ Str::slug($category) }}" width="400" height="400"></canvas>
          </div>
        @endforeach
      </div>
    
  </div>

  </div>
</section>



@endsection
@section('script')
<script src="{{ asset('asset/tamplate/plugins/chart.js/chartjs-plugin-datalabels.min.js') }}"></script>
<script>
  $(()=>{

    // top sales item
      // const chartData = @json( $chartData );
      // const ctx = document.getElementById('topSellingItemsChart').getContext('2d');
      // const myChart = new Chart(ctx, {
      //     type: 'bar',
      //     data: chartData,
      //     options: {
      //         scales: {
      //             y: {
      //                 beginAtZero: true
      //             }
      //         }
      //     }
      // });
      // daily report gross
      const chartDataGrossDaily = @json( $chartDailyGrossSales );
      const ctx_grossDaily = document.getElementById('grossSales').getContext('2d');
      const myChart2 = new Chart(ctx_grossDaily, {
          type: 'line',
          data: chartDataGrossDaily,
          options: {
              scales: {
                  y: {
                      beginAtZero: true
                  }
              }
          }
      });

      // daily week report gross
      const grossSalesWeek = @json( $chartDayOfWeekGrossSales );
      const ctx_grossDailyWeek = document.getElementById('grossSalesWeek').getContext('2d');
      const myChart3 = new Chart(ctx_grossDailyWeek, {
          type: 'bar',
          data: grossSalesWeek,
          options: {
              scales: {
                  y: {
                      beginAtZero: true
                  }
              }
          }
      });

      // hourly  gross sales
      const grossSaleshour = @json( $chartHourlyGrossSales );
      const ctx_grossDailyHour = document.getElementById('grossSalesHour').getContext('2d');
      const myChart4 = new Chart(ctx_grossDailyHour, {
          type: 'line',
          data: grossSaleshour,
          options: {
              scales: {
                  y: {
                      beginAtZero: true
                  }
              }
          }
      });

      {{-- CATEGORY BY VOLUME   --}}
        var ctx = document.getElementById('categoryByVolumeChart').getContext('2d');
        var categoryByVolumeChart = new Chart(ctx, {
            type: 'pie',
            data: @json($chartCategoryByVolume),
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                var label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.raw.toLocaleString();
                                return label;
                            }
                        }
                    },
                    datalabels: {
                        formatter: (value, context) => {
                           
                            let data = context.chart.data.datasets[0].data.map(Number);
                            let sum = data.reduce((a, b) => a + b, 0);

                            console.log('Data:', data);
                            console.log('Sum:', sum);

                            let percentage = sum ? Math.round((value / sum) * 100) : 0;

                            console.log('Value:', value, 'Percentage:', percentage);

                            return percentage + "%";
                            
                        },
                        color: '#fff',
                    }
                }
            },
            plugins: [ChartDataLabels]
           
        });

        // Catregory by sale
       

        var ctx_category_sale = document.getElementById('categoryBySalesChart').getContext('2d');
        var categoryBySalesChart = new Chart(ctx_category_sale, {
            type: 'pie',
            data:  @json($chartCategoryBySales),
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                var label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.raw.toLocaleString();
                                return label;
                            }
                        }
                    },
                    datalabels: {
                        formatter: (value, context) => {
                            let data = context.chart.data.datasets[0].data;
                            let sum = data.reduce((a, b) => a + b, 0);
                            let percentage = sum ? Math.round((value / sum) * 100) : 0;
                            return percentage + "%";
                        },
                        color: '#fff',
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // top item by category
        @foreach($chartData_items_cat as $category => $data)
        var ctx_items_cat = document.getElementById('chart-{{ Str::slug($category) }}').getContext('2d');
        new Chart(ctx_items_cat, {
            type: 'bar', // Atau 'bar' atau 'line' sesuai kebutuhan
            data: {
                labels: {!! json_encode($data['labels']) !!},
                datasets: [{
                    label: '{{ $category }}',
                    data: {!! json_encode($data['data']) !!},
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                        '#FF9F40', '#FFCD56', '#C9CBCF', '#FF6384', '#36A2EB',
                        '#4A1313', '#D0A024', '#51D024', '#245FD0', '#C493CC',
                        '#F4D3D5', '#F6076C', '#F65D07', '#FAFF1B', '#140A3A',
                    ],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                var label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.raw.toLocaleString();
                                return label;
                            }
                        }
                    },
                }
            }
        });
      @endforeach
      
    @if(request()->has('daterange'))
      $('input[name="daterange"]').daterangepicker({
        locale: {
            format: 'YYYY-MM-DD',
            separator: " / "
          },
          startDate: '{{$startDate}}',
          endDate: '{{$endDate}}'
      });
    @else
        var DateTime = luxon.DateTime;
        var dt = DateTime.now();
        var current = dt.toFormat('yyyy-MM-dd');
          $('.date_search #date').val(current);
          
          $('input[name="daterange"]').daterangepicker({
          locale: {
            format: 'YYYY-MM-DD',
            separator: " / "
          },
          startDate: '{{$startDate}}',
          endDate: '{{$endDate}}'
        });
    @endif


      $('.filter').click(function(e){
          e.preventDefault();
          filterReportDate();
      });

      // top sales item
        // function updateChart(startDate, endDate) {
        //   fetch(`/Dashboard?startDate=${startDate}&endDate=${endDate}`)
        //       .then(response => response.json())
        //       .then(data => {
        //           const ctx = document.getElementById('topSellingItemsChart').getContext('2d');
        //           if (window.myChart) {
        //               window.myChart.destroy();
        //           }
        //           window.myChart = new Chart(ctx, {
        //               type: 'bar',
        //               data: @json($chartData),
        //               options: {
        //                   scales: {
        //                       y: {
        //                           beginAtZero: true
        //                       }
        //                   }
        //               }
        //           });
        //       });
        // }
    
      // gross daily sales
      function updateChartGrossDaily(startDate, endDate) {
            fetch(`/Dashboard?startDate=${startDate}&endDate=${endDate}`)
            .then(response => response.json())
            .then(data => {
                const ctx_grossDaily = document.getElementById('grossSales').getContext('2d');
                if (window.myChart2) {
                    window.myChart2.destroy();
                }
                window.myChart2 = new Chart(ctx_grossDaily, {
                    type: 'line',
                    data: @json($chartDailyGrossSales),
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            });
      }

        // gross weekly sales
        function updateChartGrossDailyWeek(startDate, endDate) {
              fetch(`/Dashboard?startDate=${startDate}&endDate=${endDate}`)
              .then(response => response.json())
              .then(data => {
                  const ctx_grossDailyWeek = document.getElementById('grossSalesWeek').getContext('2d');
                  if (window.myChart3) {
                      window.myChart3.destroy();
                  }
                  window.myChart3 = new Chart(ctx_grossDailyWeek, {
                      type: 'bar',
                      data: @json($chartDayOfWeekGrossSales),
                      options: {
                          scales: {
                              y: {
                                  beginAtZero: true
                              }
                          }
                      }
                  });
              });
        }
        // gross Hour sales
        function updateChartGrossHour(startDate, endDate) {
              fetch(`/Dashboard?startDate=${startDate}&endDate=${endDate}`)
              .then(response => response.json())
              .then(data => {
                  const ctx_grossDailyWeek = document.getElementById('grossSalesHour').getContext('2d');
                  if (window.myChart4) {
                      window.myChart4.destroy();
                  }
                  window.myChart4 = new Chart(ctx_grossDailyHour, {
                      type: 'line',
                      data: @json($chartHourlyGrossSales),
                      options: {
                          scales: {
                              y: {
                                  beginAtZero: true
                              }
                          }
                      }
                  });
              });
        }

        function filterReportDate(){
            var daterange = $('input[name="daterange"]').val();
            var daterange = daterange.split(" / ");
            var startDate = daterange[0];
            var endDate = daterange[1];
            var url = "{{route('Dashboard')}}";
            url = url+'?startDate='+startDate+'&endDate='+endDate;
            // updateChart(startDate, endDate);
            updateChartGrossDaily(startDate, endDate)
            updateChartGrossDailyWeek(startDate, endDate)
            updateChartGrossHour(startDate, endDate)
            window.location = url;
        }

      // updateChart('{{ $startDate }}', '{{ $endDate }}');
      updateChartGrossDaily('{{ $startDate }}', '{{ $endDate }}');
      updateChartGrossDailyWeek('{{ $startDate }}', '{{ $endDate }}');
      updateChartGrossHour('{{ $startDate }}', '{{ $endDate }}');
     

})
  
</script>

@endsection