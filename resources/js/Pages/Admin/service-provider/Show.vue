<template>
  <!-- Content -->
  <!-- Header -->
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="user-profile-header-banner">
          <img src="/public/admin_assets/assets/img/pages/profile-banner.png" alt="Banner image" class="rounded-top" />
        </div>
        <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
          <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
            <img :src="props?.service_provider?.profile_photo_url" alt="user image"
              class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" />
          </div>
          <div class="flex-grow-1 mt-3 mt-sm-5">
            <div
              class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
              <div class="user-profile-info">
                <h4>{{ props?.service_provider?.full_name }}</h4>
                <ul
                  class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                  <li v-for="category in props?.categories" class="list-inline-item"><i
                      class="ti ti-color-swatch"></i>{{ category.name }}</li>
                  <li v-if="props?.service_provider?.location" class="list-inline-item"><i class="ti ti-map-pin"></i> {{ props?.service_provider?.location }}</li>
                  <li class="list-inline-item"><i class="ti ti-calendar"></i> {{
                    formateDate(props?.service_provider?.created_at) }}</li>
                </ul>
              </div>
              <a href="javascript:void(0)" class="btn btn-primary">
                <i class="ti ti-user-check me-1"></i>Connected
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Header -->

  <!-- Navbar pills -->
  <div class="row">
    <div class="col-md-12">
      <ul class="nav nav-pills flex-column flex-sm-row mb-4">
        <li class="nav-item">
          <button class="nav-link" :class="activeTab == 'profile' ? 'active' : ''" @click="activeTab = 'profile'"><i
              class="ti-xs ti ti-user-check me-1"></i>
            Profile</button>
        </li>
        <!-- <li class="nav-item">
          <button class="nav-link" :class="activeTab == 'services' ? 'active' : ''" @click="activeTab = 'services'"><i
              class="ti-xs ti ti-users me-1"></i> Services</button>
        </li> -->
        <!-- <li class="nav-item">
          <button class="nav-link" :class="activeTab == 'availabilities' ? 'active' : ''"
            @click="activeTab = 'availabilities'"><i class="ti-xs ti ti-layout-grid me-1"></i>
            Availabilities</button>
        </li> -->
        <li class="nav-item">
          <button class="nav-link" :class="activeTab == 'bookings' ? 'active' : ''" @click="activeTab = 'bookings'"><i
              class="ti-xs ti ti-layout-grid me-1"></i>
            Bookings</button>
        </li>
        <!-- <li class="nav-item">
                    <a class="nav-link" href="pages-profile-connections.html"><i class="ti-xs ti ti-link me-1"></i>
                      Connections</a>
                  </li> -->
      </ul>
    </div>
  </div>
  <!--/ Navbar pills -->

  <!-- User Profile Content -->
  <div v-if="activeTab == 'profile'" class="row">
    <div class="col-xl-4 col-lg-5 col-md-5">
      <!-- About User -->
      <div class="card mb-4">
        <div class="card-body">
          <small class="card-text text-uppercase">About</small>
          <ul class="list-unstyled mb-4 mt-3">
            <li class="d-flex align-items-center mb-3">
              <i class="ti ti-user"></i><span class="fw-bold mx-2">Full Name:</span> <span>{{
                props?.service_provider?.full_name }}</span>
            </li>
            <li class="d-flex align-items-center mb-3">
              <i class="ti ti-check"></i><span class="fw-bold mx-2">Status:</span>
              <span class="badge cursor-pointer" :class="{
                        'bg-label-danger': (props?.service_provider?.active == 0),
                        'bg-label-success': (props?.service_provider?.active == 1),
                        }">{{ props?.service_provider?.active == 1 ? "Active" : "Suspended" }}</span>
            </li>
            <!-- <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-crown"></i><span class="fw-bold mx-2">Role:</span> <span>Developer</span>
                      </li> -->
            <li class="d-flex align-items-center mb-3">
              <i class="ti ti-flag"></i><span class="fw-bold mx-2">Country:</span> <span>USA</span>
            </li>
            <!-- <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file-description"></i><span class="fw-bold mx-2">Languages:</span>
                        <span>English</span>
                      </li> -->
          </ul>
          <small class="card-text text-uppercase">Contacts</small>
          <ul class="list-unstyled mb-4 mt-3">
            <li class="d-flex align-items-center mb-3">
              <i class="ti ti-phone-call"></i><span class="fw-bold mx-2">Contact:</span>
              <span>{{ props?.service_provider?.phone }}</span>
            </li>
            <!-- <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-brand-skype"></i><span class="fw-bold mx-2">Skype:</span>
                        <span>john.doe</span>
                      </li> -->
            <li class="d-flex align-items-center mb-3">
              <i class="ti ti-mail"></i><span class="fw-bold mx-2">Email:</span>
              <span>{{ props?.service_provider?.email }}</span>
            </li>
          </ul>
          <!-- <small class="card-text text-uppercase">Teams</small>
                    <ul class="list-unstyled mb-0 mt-3">
                      <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-brand-angular text-danger me-2"></i>
                        <div class="d-flex flex-wrap">
                          <span class="fw-bold me-2">Backend Developer</span><span>(126 Members)</span>
                        </div>
                      </li>
                      <li class="d-flex align-items-center">
                        <i class="ti ti-brand-react-native text-info me-2"></i>
                        <div class="d-flex flex-wrap">
                          <span class="fw-bold me-2">React Developer</span><span>(98 Members)</span>
                        </div>
                      </li>
                    </ul> -->
        </div>
      </div>
      <!--/ About User -->
      <!-- Profile Overview -->
      <div class="card mb-4">
        <div class="card-body">
          <p class="card-text text-uppercase">Overview</p>
          <ul class="list-unstyled mb-0">
            <li class="d-flex align-items-center mb-3">
              <i class="ti ti-check"></i><span class="fw-bold mx-2">Jobs Completed:</span> <span>135</span>
            </li>
            <li class="d-flex align-items-center mb-3">
              <Icon icon="material-symbols:pending-actions-rounded" width="20" height="20" />
              <span class="fw-bold mx-2">Jobs Pending:</span>
              <span>4</span>
            </li>
            <li class="d-flex align-items-center">
              <Icon icon="si:cancel-presentation-fill" width="20" height="20" />
              <span class="fw-bold mx-2">Jobs Cancelled:</span> <span>23</span>
            </li>
          </ul>
        </div>
      </div>
      <!--/ Profile Overview -->
    </div>
    <div class="col-xl-8 col-lg-7 col-md-7">
      <!-- Activity Timeline -->
      <div class="card mb-4">
        <h5 class="card-header">Recent Login Attempts</h5>
        <div class="card-body pb-0">
          <ul class="timeline mb-0">
            <li v-for="(activity, index) in props?.login_activity" class="timeline-item timeline-item-transparent"
              :class="(index == props?.login_activity.length - 1) ? 'border-0' : null">
              <span class="timeline-point"
                :class="(activity.description === 'Successful' ? 'timeline-point-success' : 'timeline-point-danger')"></span>
              <div class="timeline-event">
                <div class="timeline-header mb-1">
                  <h6 class="mb-0">{{(activity.description === 'Successful') ? 'Successfull Login' : 'Failed Login attempt' }}</h6>
                  <small class="text-muted">{{ dayjs(activity.created_at).format('MMM D, YYYY h:mm A') }}</small>
                </div>
                <span class="mt-2 mb-1 small text-uppercase text-muted">Details</span>
                <div class="info-container">
                  <ul class="list-unstyled">
                    <li v-if="activity?.causer_id" class="">
                      <span class="fw-semibold me-1">User ID:</span>
                      <span>{{ activity.causer_id }}</span>
                    </li>
                    <li v-if="!activity?.causer_id" class="">
                      <span class="fw-semibold me-1">Email:</span>
                      <span>{{ activity.properties.email }}</span>
                    </li>
                    <li class="">
                      <span class="fw-semibold me-1">IP Address:</span>
                      <span>{{ activity.properties.ip }}</span>
                    </li>
                    <li class="">
                      <span class="fw-semibold me-1">Browser:</span>
                      <span>{{ activity.properties.browser }}</span>
                    </li>
                  </ul>
                </div>
              </div>
            </li>
          </ul>
          <div class="d-flex justify-content-center" v-if="props?.login_activity?.length === 0">
            <img class="w-50" src="/public/admin_assets/assets/img/illustrations/No data-found.svg" alt="" srcset="">
          </div>
        </div>
      </div>
      <!--/ Activity Timeline -->
      <div class="row">
        <!-- Connections -->
        <!-- <div class="col-lg-12 col-xl-6">
                    <div class="card card-action mb-4">
                      <div class="card-header align-items-center">
                        <h5 class="card-action-title mb-0">Connections</h5>
                        <div class="card-action-element">
                          <div class="dropdown">
                            <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown"
                              aria-expanded="false">
                              <i class="ti ti-dots-vertical text-muted"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                              <li><a class="dropdown-item" href="javascript:void(0);">Share connections</a></li>
                              <li><a class="dropdown-item" href="javascript:void(0);">Suggest edits</a></li>
                              <li>
                                <hr class="dropdown-divider" />
                              </li>
                              <li><a class="dropdown-item" href="javascript:void(0);">Report bug</a></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                      <div class="card-body">
                        <ul class="list-unstyled mb-0">
                          <li class="mb-3">
                            <div class="d-flex align-items-start">
                              <div class="d-flex align-items-start">
                                <div class="avatar me-2">
                                  <img src="../../assets/img/avatars/2.png" alt="Avatar" class="rounded-circle" />
                                </div>
                                <div class="me-2 ms-1">
                                  <h6 class="mb-0">Cecilia Payne</h6>
                                  <small class="text-muted">45 Connections</small>
                                </div>
                              </div>
                              <div class="ms-auto">
                                <button class="btn btn-label-primary btn-icon btn-sm">
                                  <i class="ti ti-user-check ti-xs"></i>
                                </button>
                              </div>
                            </div>
                          </li>
                          <li class="mb-3">
                            <div class="d-flex align-items-start">
                              <div class="d-flex align-items-start">
                                <div class="avatar me-2">
                                  <img src="../../assets/img/avatars/3.png" alt="Avatar" class="rounded-circle" />
                                </div>
                                <div class="me-2 ms-1">
                                  <h6 class="mb-0">Curtis Fletcher</h6>
                                  <small class="text-muted">1.32k Connections</small>
                                </div>
                              </div>
                              <div class="ms-auto">
                                <button class="btn btn-primary btn-icon btn-sm">
                                  <i class="ti ti-user-x ti-xs"></i>
                                </button>
                              </div>
                            </div>
                          </li>
                          <li class="mb-3">
                            <div class="d-flex align-items-start">
                              <div class="d-flex align-items-start">
                                <div class="avatar me-2">
                                  <img src="../../assets/img/avatars/10.png" alt="Avatar" class="rounded-circle" />
                                </div>
                                <div class="me-2 ms-1">
                                  <h6 class="mb-0">Alice Stone</h6>
                                  <small class="text-muted">125 Connections</small>
                                </div>
                              </div>
                              <div class="ms-auto">
                                <button class="btn btn-primary btn-icon btn-sm">
                                  <i class="ti ti-user-x ti-xs"></i>
                                </button>
                              </div>
                            </div>
                          </li>
                          <li class="mb-3">
                            <div class="d-flex align-items-start">
                              <div class="d-flex align-items-start">
                                <div class="avatar me-2">
                                  <img src="../../assets/img/avatars/7.png" alt="Avatar" class="rounded-circle" />
                                </div>
                                <div class="me-2 ms-1">
                                  <h6 class="mb-0">Darrell Barnes</h6>
                                  <small class="text-muted">456 Connections</small>
                                </div>
                              </div>
                              <div class="ms-auto">
                                <button class="btn btn-label-primary btn-icon btn-sm">
                                  <i class="ti ti-user-check ti-xs"></i>
                                </button>
                              </div>
                            </div>
                          </li>

                          <li class="mb-3">
                            <div class="d-flex align-items-start">
                              <div class="d-flex align-items-start">
                                <div class="avatar me-2">
                                  <img src="../../assets/img/avatars/12.png" alt="Avatar" class="rounded-circle" />
                                </div>
                                <div class="me-2 ms-1">
                                  <h6 class="mb-0">Eugenia Moore</h6>
                                  <small class="text-muted">1.2k Connections</small>
                                </div>
                              </div>
                              <div class="ms-auto">
                                <button class="btn btn-label-primary btn-icon btn-sm">
                                  <i class="ti ti-user-check ti-xs"></i>
                                </button>
                              </div>
                            </div>
                          </li>
                          <li class="text-center">
                            <a href="javascript:;">View all connections</a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div> -->
        <!--/ Connections -->
        <!-- Teams -->
        <!-- <div class="col-lg-12 col-xl-6">
                    <div class="card card-action mb-4">
                      <div class="card-header align-items-center">
                        <h5 class="card-action-title mb-0">Teams</h5>
                        <div class="card-action-element">
                          <div class="dropdown">
                            <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown"
                              aria-expanded="false">
                              <i class="ti ti-dots-vertical text-muted"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                              <li><a class="dropdown-item" href="javascript:void(0);">Share teams</a></li>
                              <li><a class="dropdown-item" href="javascript:void(0);">Suggest edits</a></li>
                              <li>
                                <hr class="dropdown-divider" />
                              </li>
                              <li><a class="dropdown-item" href="javascript:void(0);">Report bug</a></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                      <div class="card-body">
                        <ul class="list-unstyled mb-0">
                          <li class="mb-3">
                            <div class="d-flex align-items-center">
                              <div class="d-flex align-items-start">
                                <div class="avatar me-2">
                                  <img src="../../assets/img/icons/brands/react-label.png" alt="Avatar"
                                    class="rounded-circle" />
                                </div>
                                <div class="me-2 ms-1">
                                  <h6 class="mb-0">React Developers</h6>
                                  <small class="text-muted">72 Members</small>
                                </div>
                              </div>
                              <div class="ms-auto">
                                <a href="javascript:;"><span class="badge bg-label-danger">Developer</span></a>
                              </div>
                            </div>
                          </li>
                          <li class="mb-3">
                            <div class="d-flex align-items-center">
                              <div class="d-flex align-items-start">
                                <div class="avatar me-2">
                                  <img src="../../assets/img/icons/brands/support-label.png" alt="Avatar"
                                    class="rounded-circle" />
                                </div>
                                <div class="me-2 ms-1">
                                  <h6 class="mb-0">Support Team</h6>
                                  <small class="text-muted">122 Members</small>
                                </div>
                              </div>
                              <div class="ms-auto">
                                <a href="javascript:;"><span class="badge bg-label-primary">Support</span></a>
                              </div>
                            </div>
                          </li>
                          <li class="mb-3">
                            <div class="d-flex align-items-center">
                              <div class="d-flex align-items-start">
                                <div class="avatar me-2">
                                  <img src="../../assets/img/icons/brands/figma-label.png" alt="Avatar"
                                    class="rounded-circle" />
                                </div>
                                <div class="me-2 ms-1">
                                  <h6 class="mb-0">UI Designers</h6>
                                  <small class="text-muted">7 Members</small>
                                </div>
                              </div>
                              <div class="ms-auto">
                                <a href="javascript:;"><span class="badge bg-label-info">Designer</span></a>
                              </div>
                            </div>
                          </li>
                          <li class="mb-3">
                            <div class="d-flex align-items-center">
                              <div class="d-flex align-items-start">
                                <div class="avatar me-2">
                                  <img src="../../assets/img/icons/brands/vue-label.png" alt="Avatar"
                                    class="rounded-circle" />
                                </div>
                                <div class="me-2 ms-1">
                                  <h6 class="mb-0">Vue.js Developers</h6>
                                  <small class="text-muted">289 Members</small>
                                </div>
                              </div>
                              <div class="ms-auto">
                                <a href="javascript:;"><span class="badge bg-label-danger">Developer</span></a>
                              </div>
                            </div>
                          </li>
                          <li class="mb-3">
                            <div class="d-flex align-items-center">
                              <div class="d-flex align-items-start">
                                <div class="avatar me-2">
                                  <img src="../../assets/img/icons/brands/twitter-label.png" alt="Avatar"
                                    class="rounded-circle" />
                                </div>
                                <div class="me-2 ms-1">
                                  <h6 class="mb-0">Digital Marketing</h6>
                                  <small class="text-muted">24 Members</small>
                                </div>
                              </div>
                              <div class="ms-auto">
                                <a href="javascript:;"><span class="badge bg-label-secondary">Marketing</span></a>
                              </div>
                            </div>
                          </li>
                          <li class="text-center">
                            <a href="javascript:;">View all teams</a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div> -->
        <!--/ Teams -->
      </div>
      <!-- Projects table -->
      <!-- <div class="card mb-4">
                  <div class="card-datatable table-responsive">
                    <table class="datatables-projects table border-top">
                      <thead>
                        <tr>
                          <th></th>
                          <th></th>
                          <th>Name</th>
                          <th>Leader</th>
                          <th>Team</th>
                          <th class="w-px-200">Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div> -->
      <!--/ Projects table -->
    </div>
  </div>
  <!--/ User Profile Content -->

  <!-- User Services -->
  <div v-if="activeTab == 'services'" class="">
    <div v-for="service in props?.service_provider.services" class="col-lg-8 mb-4 col-md-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <div class="d-flex gap-2">
            <h5 class="card-title mb-0">{{ service.name }}</h5>
            <span class="badge bg-label-primary me-1">{{ service.category.name }}</span>
          </div>
          <small class="text-muted">Updated 1 month ago</small>
        </div>
        <div class="card-body pt-2">
          <div class="row gy-3">
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-primary me-3 p-2">
                  <i class="ti ti-chart-pie-2 ti-sm"></i>
                </div>
                <div class="card-info">
                  <h5 class="mb-0">230k</h5>
                  <small>Sales</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-info me-3 p-2">
                  <i class="ti ti-users ti-sm"></i>
                </div>
                <div class="card-info">
                  <h5 class="mb-0">8.549k</h5>
                  <small>Customers</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-danger me-3 p-2">
                  <i class="ti ti-shopping-cart ti-sm"></i>
                </div>
                <div class="card-info">
                  <h5 class="mb-0">1.423k</h5>
                  <small>Products</small>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="d-flex align-items-center">
                <div class="badge rounded-pill bg-label-success me-3 p-2">
                  <i class="ti ti-currency-dollar ti-sm"></i>
                </div>
                <div class="card-info">
                  <h5 class="mb-0">$9745</h5>
                  <small>Revenue</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="w-75" v-if="props?.service_provider?.services.length === 0">
        <h5 class="card-header">{{ props.service_provider?.first_name }}'s Services</h5>
        <div class="d-flex justify-content-center">
          <img class="w-50" src="/public/admin_assets/assets/img/illustrations/No data-found.svg" alt="" srcset="">
        </div>
      </div>
    </div>
  </div>
  <!--/ User Services -->

  <!-- User Availabilities-->
  <div v-if="activeTab == 'availabilities'" class="">
    <div class="card mb-4">
      <div v-if="props?.service_provider?.availabilities?.length > 0">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="card-header">{{ props.service_provider?.first_name }}'s availability</h5>
          <button @click="editingavAilability = true" class="btn btn-primary me-4"><i></i> Edit</button>
        </div>
        <div class="table-responsive">
          <table class="table table-striped border-top">
            <thead>
              <tr>
                <th class="text-nowrap">Days</th>
                <th class="text-nowrap">From</th>
                <th class="text-nowrap">To</th>
                <th class="text-nowrap">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr class="h-px-50" v-for="availability in props.service_provider.availabilities">
                <td class="text-nowrap">{{ availability.day }}</td>
                <td>
                  <div class="w-px-100">
                    <span v-if="!editingavAilability">{{ formateTime(availability?.start_time) }}</span>
                    <Transition>
                      <input v-if="editingavAilability" v-model="availability.start_time" type="time"
                        class="form-control w-auto h-px-30" autofocus id="defaultFormControlInput" placeholder=""
                        aria-describedby="defaultFormControlHelp" />
                    </Transition>
                  </div>
                </td>
                <td>
                  <div class="w-px-100">
                    <span v-if="!editingavAilability">{{ formateTime(availability?.end_time) }}</span>
                    <input v-if="editingavAilability" v-model="availability.end_time" type="time"
                      class="form-control w-auto h-px-30" id="defaultFormControlInput" placeholder=""
                      aria-describedby="defaultFormControlHelp" />
                  </div>
                </td>
                <td>
                  <div class="">
                    <div class="dropdown">
                      <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="ti ti-dots-vertical"></i>
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="javascript:void(0);"><span>Mark as Closed</span></a>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="card-body">
          <button type="submit" class="btn btn-primary me-2">Save changes</button>
          <button @click="editingavAilability = false" type="button" class="btn btn-label-dark">Discard</button>
        </div>
      </div>
      <div class="w-75" v-if="props?.service_provider?.availabilities.length === 0">
        <h5 class="card-header">{{ props.service_provider?.first_name }}'s availability</h5>
        <div class="d-flex justify-content-center">
          <img class="w-50" src="/public/admin_assets/assets/img/illustrations/No data-found.svg" alt="" srcset="">
        </div>
      </div>
    </div>
  </div>
  <!--/ User Availabilities -->

  <!-- User Bookings -->
   <div v-if="activeTab == 'bookings'" class="card p-4">
    <DataTable :value="store.list" lazy showGridlines @page="store.onPage" @sort="store.onSort"
                tableStyle="min-width: 50rem" :totalRecords="store.pagination.total" :rows="store.pagination.per_page"
                class="mb-4" :rowsPerPageOptions="[10, 20, 50]"
                :first="(store.pagination.current_page - 1) * store.pagination.per_page" paginator
                responsiveLayout="scroll">
                <template #header>
                    <div class="d-flex justify-content-between align-items-end">
                        <div class="d-flex gap-2 align-items-end">
                            <!--
                        <button
                            class="btn btn-xs btn-outline-primary waves-effect d-flex align-items-center justify-content-between"
                            @click="store.filterModelVisible = true">
                            <Icon icon="bx:filter-alt" width="18" height="18" />
                            <span class="mx-2">Filter
                                ({{ Object.values(store.filters).filter(value => (value !== null && value !=='')).length}})
                            </span>
                        </button>
                         -->
                            <div class="d-flex flex-column">
                                <label for="">Filter by Status</label>
                                <Select v-model="filters.status" :options="status" optionLabel="name"
                                    optionValue="value" placeholder="Select a Status" class="w-full" />
                            </div>
                            <div class="d-flex flex-column">
                                <label for="">Filter by Registration Date</label>
                                <DatePicker v-model="filters.dates" selectionMode="range" dateFormat="dd/mm/yy"
                                    placeholder="Enter a Date Range" />
                            </div>

                            <Button size="small" label="Apply" @click="applyFilters()" />
                            <Button size="small" label="Clear Filters" @click="clearFilter()" />
                        </div>
                        <div class="input-group input-group-sm w-auto h-px-40">
                            <span class="input-group-text" id="basic-addon-search31"><i class="ti ti-search"></i></span>
                            <input type="text" class="form-control" placeholder="Name or Email" v-model="searchValue"
                                @input="store.onSearchQuery(searchValue)">
                        </div>
                    </div>
                </template>

                <template #paginatorstart>
                    <div class="px-3">
                        Showing {{ store.pagination.from }} to {{ store.pagination.to }} of {{ store.pagination.total }}
                    </div>
                </template>


                <Column field="client.full_name" header="Client Name">
                    <template #body="slotData">
                        <div class="d-flex flex-wrap">
                            <div class="avatar me-3">
                                <img :alt="slotData.data.full_name" :src="slotData.data.client.profile_photo_url"
                                    class="rounded-circle">
                            </div>
                            <div>
                                <h6 class="mb-0">
                                    <Link :href="route('admin.users.show', slotData.data?.client?.id)">
                                    {{ slotData.data.client.full_name }}
                                    </Link>
                                </h6>
                                <small>Member since {{dayjs(slotData.data.client.created_at).format('MMM D, YYYY')}} </small>
                            </div>
                        </div>
                    </template>
                </Column>
                <Column field="service.name" header="Service" unstyle />
                <Column field="created_at" header="Booking Date" sortable>
                    <template #body="slotData">
                        <span>{{dayjs(slotData.data.created_at).format('MMM D, YYYY')}} </span>
                    </template>
                </Column>
                <Column field="status" header="Status">
                    <template #body="slotData"><span class="badge cursor-pointer" 
                    :class="{
                        'bg-label-danger': (slotData.data.status == 'cancelled'),
                        'bg-label-success': (slotData.data.status == 'completed'),
                        'bg-label-warning': (slotData.data.status == 'pending'),
                        'bg-label-info': (slotData.data.status == 'in-progress')
                    }"
                    >{{ slotData.data.status }}</span></template>
                </Column>
                <!-- <Column header="Action" appendTo>
                    <template #body="slotData">
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                <Link class="dropdown-item"
                                    :href="route('admin.service-providers.show', slotData.data.id)"><i
                                    class="ti ti-eye me-1"></i> View Details</Link>
                                <Link class="dropdown-item"
                                    :href="route('admin.service-providers.edit', slotData.data.id)"><i
                                    class="ti ti-edit me-1"></i> Edit</Link>
                                <button class="dropdown-item" @click="confirmDelete(slotData.data.id)"><i
                                        class="ti ti-trash me-1"></i>Delete</button>
                            </div>
                        </div>
                    </template>

                </Column> -->
                <template #empty>
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="text-black fw-bold" role="alert">
                            <span class="alert-icon text-secondary me-2"> <i class="ti ti-ban ti-xs"></i></span>
                            No data Found !!
                        </div>
                    </div>
                </template>
            </DataTable>
   </div>
  <!-- / User Bookings -->
  <!-- / Content -->
</template>

<script setup>
import { onMounted, reactive, ref, nextTick } from 'vue'
import { useBookingStore } from '@/Stores/BookingStore';
import dayjs from 'dayjs';
import { values } from 'lodash';


const store = useBookingStore();
const props = defineProps({
  service_provider: Object,
  categories: Array,
  login_activity: Array,
  bookings: Object
});

const activeTab = ref('profile');
const editingavAilability = ref(false);
const status = [
        {
            name: 'Pending',
            value: 'pending',
        },
        {
            name: 'In Proggress',
            value: 'in-progress',
        },
        {
            name: 'Completed',
            value: 'completed',
        },
        {
            name: 'Cancelled',
            value: 'cancelled',
        }

    ];
    const filters = reactive({
        name: '',
        status: null,
        dates: []
    })


onMounted(() => {
  nextTick(() => {
    const pageName = "Service Provider Management";
    const breadcrumbs = [
      { title: 'Service Providers', routeName: "admin.service-providers.list" },
      {
        title: "Profile",
      }
    ];
    console.log(props?.service_provider);
    console.log(props?.bookings);
    emit.emit('pageName', pageName, breadcrumbs);
  });
  clearFilter();
});


function formateDate(date) {
  const newDate = new Date(date);
  const formattedDate = newDate.toLocaleString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
  return formattedDate;
}



function formateTime(time24) {
  if (!time24) {
    return 'Closed'
  }
  const [hours, minutes, seconds] = time24.split(':').map(Number);

  const date = new Date();
  date.setHours(hours);
  date.setMinutes(minutes);
  date.setSeconds(seconds);

  const options = {
    hour: '2-digit',
    minute: '2-digit',
    hour12: true
  }

  return new Intl.DateTimeFormat('en-US', options).format(date)
}

function applyFilters() {
    if (filters.status !== null) {
        const index = store?.filters.findIndex((obj) => obj.column === 'status');
        console.log(index);
        if (index == -1 || !index) {
            store.filters.push({
                column: 'status',
                value: [filters.status]
            });
        } else {
            store.filters[index].value = [filters.status];
        }
    }

    if (filters.dates.length > 0) {
        const index = store?.filters.findIndex((obj) => obj.column === 'created_at');
        if (index == -1 || !index) {
            store.filters.push({
                column: 'created_at',
                value: filters.dates
            });
        } else {
            store.filters[index].value = filters.dates;
        }
    }
    store.getBookings();
}

function clearFilter() {
  store.filters = [{
    column: 'provider_id',
    value: [props.service_provider.id],
  }];
  filters.status = null;
  filters.dates = [];
  store.getBookings();
}



</script>