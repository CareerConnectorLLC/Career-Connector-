<template>
  <slot></slot>
  <!-- Sales Overview -->
  <!-- <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card">
                  <div class="card-header">
                    <div class="d-flex justify-content-between">
                      <small class="d-block mb-1 text-muted">Sales Overview</small>
                      <p class="card-text text-success">+18.2%</p>
                    </div>
                    <h4 class="card-title mb-1">$42.5k</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-4">
                        <div class="d-flex gap-2 align-items-center mb-2">
                          <span class="badge bg-label-info p-1 rounded"
                            ><i class="ti ti-shopping-cart ti-xs"></i
                          ></span>
                          <p class="mb-0">Order</p>
                        </div>
                        <h5 class="mb-0 pt-1 text-nowrap">62.2%</h5>
                        <small class="text-muted">6,440</small>
                      </div>
                      <div class="col-4">
                        <div class="divider divider-vertical">
                          <div class="divider-text">
                            <span class="badge-divider-bg bg-label-secondary">VS</span>
                          </div>
                        </div>
                      </div>
                      <div class="col-4 text-end">
                        <div class="d-flex gap-2 justify-content-end align-items-center mb-2">
                          <p class="mb-0">Visits</p>
                          <span class="badge bg-label-primary p-1 rounded"><i class="ti ti-link ti-xs"></i></span>
                        </div>
                        <h5 class="mb-0 pt-1 text-nowrap ms-lg-n3 ms-xl-0">25.5%</h5>
                        <small class="text-muted">12,749</small>
                      </div>
                    </div>
                    <div class="d-flex align-items-center mt-4">
                      <div class="progress w-100" style="height: 8px">
                        <div
                          class="progress-bar bg-info"
                          style="width: 70%"
                          role="progressbar"
                          aria-valuenow="70"
                          aria-valuemin="0"
                          aria-valuemax="100"></div>
                        <div
                          class="progress-bar bg-primary"
                          role="progressbar"
                          style="width: 30%"
                          aria-valuenow="30"
                          aria-valuemin="0"
                          aria-valuemax="100"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card">
                  <div class="card-header">
                    <div class="d-flex justify-content-between">
                      <small class="d-block mb-1 text-muted">Sales Overview</small>
                      <p class="card-text text-success">+18.2%</p>
                    </div>
                    <h4 class="card-title mb-1">$42.5k</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-4">
                        <div class="d-flex gap-2 align-items-center mb-2">
                          <span class="badge bg-label-info p-1 rounded"
                            ><i class="ti ti-shopping-cart ti-xs"></i
                          ></span>
                          <p class="mb-0">Order</p>
                        </div>
                        <h5 class="mb-0 pt-1 text-nowrap">62.2%</h5>
                        <small class="text-muted">6,440</small>
                      </div>
                      <div class="col-4">
                        <div class="divider divider-vertical">
                          <div class="divider-text">
                            <span class="badge-divider-bg bg-label-secondary">VS</span>
                          </div>
                        </div>
                      </div>
                      <div class="col-4 text-end">
                        <div class="d-flex gap-2 justify-content-end align-items-center mb-2">
                          <p class="mb-0">Visits</p>
                          <span class="badge bg-label-primary p-1 rounded"><i class="ti ti-link ti-xs"></i></span>
                        </div>
                        <h5 class="mb-0 pt-1 text-nowrap ms-lg-n3 ms-xl-0">25.5%</h5>
                        <small class="text-muted">12,749</small>
                      </div>
                    </div>
                    <div class="d-flex align-items-center mt-4">
                      <div class="progress w-100" style="height: 8px">
                        <div
                          class="progress-bar bg-info"
                          style="width: 70%"
                          role="progressbar"
                          aria-valuenow="70"
                          aria-valuemin="0"
                          aria-valuemax="100"></div>
                        <div
                          class="progress-bar bg-primary"
                          role="progressbar"
                          style="width: 30%"
                          aria-valuenow="30"
                          aria-valuemin="0"
                          aria-valuemax="100"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div> -->
  <!--/ Sales Overview -->

  <!-- Revenue Generated -->
  <!-- <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                <div class="card">
                  <div class="card-body pb-0">
                    <div class="card-icon">
                      <span class="badge bg-label-success rounded-pill p-2">
                        <i class="ti ti-credit-card ti-sm"></i>
                      </span>
                    </div>
                    <h5 class="card-title mb-0 mt-2">97.5k</h5>
                    <small>Revenue Generated</small>
                  </div>
                  <div id="revenueGenerated"></div>
                </div>
              </div> -->
  <!--/ Revenue Generated -->


  <!-- Earning Reports -->
  <!-- <div class="col-lg-6 mb-4">
                <div class="card h-100">
                  <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4">
                    <div class="card-title mb-0">
                      <h5 class="mb-0">Earning Reports</h5>
                      <small class="text-muted">Weekly Earnings Overview</small>
                    </div>
                    <div class="dropdown">
                      <button
                        class="btn p-0"
                        type="button"
                        id="earningReportsId"
                        data-bs-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false">
                        <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                      </button>
                      <div class="dropdown-menu dropdown-menu-end" aria-labelledby="earningReportsId">
                        <a class="dropdown-item" href="javascript:void(0);">View More</a>
                        <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-12 col-md-4 d-flex flex-column align-self-end">
                        <div class="d-flex gap-2 align-items-center mb-2 pb-1 flex-wrap">
                          <h1 class="mb-0">$468</h1>
                          <div class="badge rounded bg-label-success">+4.2%</div>
                        </div>
                        <small class="text-muted">You informed of this week compared to last week</small>
                      </div>
                      <div class="col-12 col-md-8">
                        <div id="weeklyEarningReports"></div>
                      </div>
                    </div>
                    <div class="border rounded p-3 mt-2">
                      <div class="row gap-4 gap-sm-0">
                        <div class="col-12 col-sm-4">
                          <div class="d-flex gap-2 align-items-center">
                            <div class="badge rounded bg-label-primary p-1">
                              <i class="ti ti-currency-dollar ti-sm"></i>
                            </div>
                            <h6 class="mb-0">Earnings</h6>
                          </div>
                          <h4 class="my-2 pt-1">$545.69</h4>
                          <div class="progress w-75" style="height: 4px">
                            <div
                              class="progress-bar"
                              role="progressbar"
                              style="width: 65%"
                              aria-valuenow="65"
                              aria-valuemin="0"
                              aria-valuemax="100"></div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-4">
                          <div class="d-flex gap-2 align-items-center">
                            <div class="badge rounded bg-label-info p-1"><i class="ti ti-chart-pie-2 ti-sm"></i></div>
                            <h6 class="mb-0">Profit</h6>
                          </div>
                          <h4 class="my-2 pt-1">$256.34</h4>
                          <div class="progress w-75" style="height: 4px">
                            <div
                              class="progress-bar bg-info"
                              role="progressbar"
                              style="width: 50%"
                              aria-valuenow="50"
                              aria-valuemin="0"
                              aria-valuemax="100"></div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-4">
                          <div class="d-flex gap-2 align-items-center">
                            <div class="badge rounded bg-label-danger p-1">
                              <i class="ti ti-brand-paypal ti-sm"></i>
                            </div>
                            <h6 class="mb-0">Expense</h6>
                          </div>
                          <h4 class="my-2 pt-1">$74.19</h4>
                          <div class="progress w-75" style="height: 4px">
                            <div
                              class="progress-bar bg-danger"
                              role="progressbar"
                              style="width: 65%"
                              aria-valuenow="65"
                              aria-valuemin="0"
                              aria-valuemax="100"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div> -->
  <!--/ Earning Reports -->

  <!-- Support Tracker -->
  <!-- <div class="col-md-6 mb-4">
            <div class="card">
              <div class="card-header d-flex justify-content-between pb-0">
                <div class="card-title mb-0">
                  <h5 class="mb-0">Support Tracker</h5>
                  <small class="text-muted">Last 7 Days</small>
                </div>
                <div class="dropdown">
                  <button
                    class="btn p-0"
                    type="button"
                    id="supportTrackerMenu"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false">
                    <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-end" aria-labelledby="supportTrackerMenu">
                    <a class="dropdown-item" href="javascript:void(0);">View More</a>
                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-12 col-sm-4 col-md-12 col-lg-4">
                    <div class="mt-lg-4 mt-lg-2 mb-lg-4 mb-2 pt-1">
                      <h1 class="mb-0">164</h1>
                      <p class="mb-0">Total Tickets</p>
                    </div>
                    <ul class="p-0 m-0">
                      <li class="d-flex gap-3 align-items-center mb-lg-3 pt-2 pb-1">
                        <div class="badge rounded bg-label-primary p-1"><i class="ti ti-ticket ti-sm"></i></div>
                        <div>
                          <h6 class="mb-0 text-nowrap">New Tickets</h6>
                          <small class="text-muted">142</small>
                        </div>
                      </li>
                      <li class="d-flex gap-3 align-items-center mb-lg-3 pb-1">
                        <div class="badge rounded bg-label-info p-1">
                          <i class="ti ti-circle-check ti-sm"></i>
                        </div>
                        <div>
                          <h6 class="mb-0 text-nowrap">Open Tickets</h6>
                          <small class="text-muted">28</small>
                        </div>
                      </li>
                      <li class="d-flex gap-3 align-items-center pb-1">
                        <div class="badge rounded bg-label-warning p-1"><i class="ti ti-clock ti-sm"></i></div>
                        <div>
                          <h6 class="mb-0 text-nowrap">Response Time</h6>
                          <small class="text-muted">1 Day</small>
                        </div>
                      </li>
                    </ul>
                  </div>
                  <div class="col-12 col-sm-8 col-md-12 col-lg-8">
                    <div id="radialbar"></div>
                  </div>
                </div>
              </div>
            </div>
          </div> -->
  <!--/ Support Tracker -->

</template>

<script setup>
import { onMounted } from 'vue';
import ApexCharts from 'apexcharts';

onMounted(() => {
  $('document').ready(function () {

    // Average Daily Sales Js Start
    var revenueGeneratedOptions = {
      series: [{
        data: [31, 40, 28, 51, 42, 109, 100]
      }],
      chart: {
        height: 130,
        type: 'area',
        parentHeightOffset: 0,
        toolbar: {
          show: false
        },
        sparkline: {
          enabled: true
        }
      },
      markers: {
        colors: 'transparent',
        strokeColors: 'transparent'
      },
      grid: {
        show: false
      },
      colors: ['#7367f0'],
      fill: {
        type: 'gradient',
        gradient: {
          shade: '#7367f0',
          shadeIntensity: 0.8,
          opacityFrom: 0.6,
          opacityTo: 0.1
        }
      },

      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth'
      },

      legend: {
        show: false
      },
      tooltip: {
        enabled: false  // Disable tooltip
      },
      xaxis: {
        show: false,  // Completely hide the x-axis
        lines: {
          show: false  // Hide x-axis line
        }
      },
      yaxis: {
        show: false,  // Completely hide the y-axis
        lines: {
          show: false  // Hide y-axis line
        }
      }
    };


    var revenueGenerated = new ApexCharts(document.querySelector("#revenueGenerated"), revenueGeneratedOptions);
    revenueGenerated.render();
    // Average Daily Sales Js End

    // Earnings Charts Js Start


    var weeklyEarningReportOptions = {
      series: [{
        name: 'Earnings',
        data: [5000, 7000, 2000, 10000, 6000, 8000, 3200]  // Data corresponding to values: 0, 0.5k, 5k, 10k etc.
      }],
      chart: {
        height: 202,
        parentHeightOffset: 0,
        type: 'bar',
        toolbar: {
          show: false
        }
      },
      plotOptions: {
        bar: {
          barHeight: '60%',
          columnWidth: '38%',
          startingShape: 'rounded',
          endingShape: 'rounded',
          borderRadius: 4,
          distributed: true
        }
      },
      grid: {
        show: false,
        padding: {
          top: -30,
          bottom: 0,
          left: -10,
          right: -10
        }
      },
      dataLabels: {
        enabled: false
      },
      legend: {
        show: false
      },

      xaxis: {
        categories: ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'],
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          style: {
            colors: '#000',
            fontSize: '13px',
            fontFamily: 'Public Sans'
          }
        }
      },
      yaxis: {
        labels: {
          show: false
        }
      },
      tooltip: {
        enabled: false
      },
      responsive: [
        {
          breakpoint: 1025,
          options: {
            chart: {
              height: 199
            }
          }
        }
      ],
      colors: ['#7367f0'], // Set the background color to rgba(255, 121, 63, 0.1) (light orange)
    };

    var weeklyEarningReports = new ApexCharts(document.querySelector("#weeklyEarningReports"), weeklyEarningReportOptions);
    weeklyEarningReports.render();

    //   Radialbar Charts js Start
    var radialbarOptions = {
      series: [85],
      labels: ['Completed Task'],
      chart: {
        height: 360,
        type: 'radialBar'
      },
      plotOptions: {
        radialBar: {

          startAngle: -140,
          endAngle: 130,
          track: {
            background: '#FFFFFF',
            strokeWidth: '100%'
          },
          dataLabels: {
            value: {
              offsetY: -20,
              color: '#FF793F',
              formatter: function (val) {
                return val + "%";  // Display percentage with '%'
              },
              textAnchor: 'middle',  // Horizontally center the value label
            },
            name: {
              fontWeight: '400',
              fontFamily: 'Roboto',
              fontSize: '13px',
              color: '#32125E',  // Set the name label color to purple (#32125E)
              offsetY: -15,  // Move the name label downwards (after the value label)

            }
          }
        }

      },
      hover: {
        enabled: false,  // Disable hover effect
        shade: false,    // Disable the shade effect on hover
        opacity: 1       // Keep opacity unchanged
      },
      fill: {
        type: 'gradient',  // Use solid color for the radial bar fill
        gradient: {
          shade: 'dark',
          shadeIntensity: 0.5,
          gradientToColors: ['#FF793F'],
          inverseColors: true,
          opacityFrom: 1,
          opacityTo: 0.6,
          stops: [30, 70, 100]
        },
        background: '#FFFFFF',  // Set the radial background color to white (#FFFFFF)
      },
      stroke: {
        dashArray: 10
      },
      grid: {
        padding: {
          top: -20,
          bottom: 5
        }
      },
      states: {
        hover: {
          filter: {
            type: 'none'
          }
        },
        active: {
          filter: {
            type: 'none'
          }
        }
      },
      responsive: [
        {
          breakpoint: 1025,
          options: {
            chart: {
              height: 330
            }
          }
        },
        {
          breakpoint: 769,
          options: {
            chart: {
              height: 280
            }
          }
        }
      ]
    }; var radialbarchart = new ApexCharts(document.querySelector("#radialbar"), radialbarOptions);
    radialbarchart.render();

  })
});

</script>
