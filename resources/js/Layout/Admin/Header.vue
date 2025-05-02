<template>
  <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none" @click="toggleMobileMenu()">
      <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
        <i class="ti ti-menu-2 ti-sm"></i>
      </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
      <!-- Search -->
      <div class="navbar-nav align-items-center">
        <div class="nav-item navbar-search-wrapper mb-0">
          <span class="fs-4 text-primary">{{ title }}</span>
        </div>
      </div>
      <!-- /Search -->
      <ul class="navbar-nav flex-row align-items-center ms-auto">

        <!-- Notification -->
        <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
          <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"
            data-bs-auto-close="outside" aria-expanded="false">
            <i class="ti ti-bell ti-md"></i>
            <span class="badge bg-danger rounded-pill badge-notifications">0</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end py-0">
            <li class="dropdown-menu-header border-bottom">
              <div class="dropdown-header d-flex align-items-center py-3">
                <h5 class="text-body mb-0 me-auto">Notification</h5>
                <a href="javascript:void(0)" class="dropdown-notifications-all text-body" data-bs-toggle="tooltip"
                  data-bs-placement="top" title="Mark all as read"><i class="ti ti-mail-opened fs-4"></i></a>
              </div>
            </li>
            <li class="dropdown-notifications-list">
              <ul class="list-group list-group-flush notification-panel">

              </ul>
            </li>
            <li class="dropdown-menu-footer border-top">
              <a href="javascript:void(0);"
                class="dropdown-item d-flex justify-content-center text-primary p-2 h-px-40 mb-1 align-items-center">
                View all notifications
              </a>
            </li>
          </ul>
        </li>



        <li class="nav-item navbar-dropdown dropdown-user dropdown">
          <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
            <div class="avatar avatar-online">
              <img :src="user.profile_photo_url" alt class="rounded-circle" />
            </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <Link class="dropdown-item" :href="route('admin.admin-profile')">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar avatar-online">
                    <img :src="user.profile_photo_url" alt class="h-auto rounded-circle" />
                  </div>
                </div>
                <div class="flex-grow-1">
                  <span class="fw-semibold d-block">{{user.full_name}}</span>
                  <small class="text-muted">{{user.email}}</small>
                </div>
              </div>
              </Link>
            </li>
            <li>
              <div class="dropdown-divider"></div>
            </li>
            <li>
              <Link class="dropdown-item" :href="route('admin.admin-profile')">
              <i class="ti ti-user-check me-2 ti-sm"></i>
              <span class="align-middle">My Profile</span>
              </Link>
              <li>
                <Link class="dropdown-item" :href="route('admin.site-setting.index')">
                  <Icon class="me-2" icon="lets-icons:setting-line-light" width="21" height="21" />
                  <span class="align-middle">Site Settings</span>
                </Link>
              </li>
            </li>
            <li>
              <div class="dropdown-divider"></div>
            </li>
            <li>
              <Link class="dropdown-item" :href="route('admin.logout')" method="post">
              <i class="ti ti-logout me-2 ti-sm"></i>
              <span class="align-middle">Log Out</span>
              </Link>
            </li>
          </ul>
        </li>



      </ul>
    </div>
    <!-- Search Small Screens -->
    <!-- <div class="navbar-search-wrapper search-input-wrapper d-none">
      <input
        type="text"
        class="form-control search-input container-xxl border-0"
        placeholder="Search..."
        aria-label="Search..." />
      <i class="ti ti-x ti-sm search-toggler cursor-pointer"></i>
    </div> -->
  </nav>
  <Toast position="bottom-right" group="br" />
</template>



<script setup>
  import { router, usePage } from '@inertiajs/vue3'
  import { ref, onMounted, computed, onUnmounted } from 'vue';
  import Toast from 'primevue/toast';

    const signOut = () => {
        // router.visit(route('admin.logout'),{
        //     method: 'post',
        // });
    }
   const user = computed(() => usePage().props.auth.user);


const title = ref("");
onMounted(() => {
  emit.on("pageName", function (arg1) {
    title.value = arg1;
  });
});


const toggleMobileMenu = () => {
    document.documentElement.classList.toggle('layout-menu-expanded');
    // document.documentElement.classList.toggle('layout-menu-expanded');
    const layoutMenu = document.getElementById("layout-menu");
    if (layoutMenu) {
        layoutMenu.style.zIndex = '9999'; // Set desired z-index value
    }
};


</script>
<style scoped>
  .notification-panel {
    max-height: 15rem;
    overflow-y: auto;
  }

  @media (max-width: 576px) {
    .notification-panel {
      max-height: 31vh;
    }
  }

  .notification-panel::-webkit-scrollbar {
    width: 8px;
  }

  .notification-panel::-webkit-scrollbar-thumb {
    background-color: #888;
    border-radius: 4px;
  }

  .notification-panel::-webkit-scrollbar-thumb:hover {
    background-color: #555;
  }

  .notification-panel::-webkit-scrollbar-track {
    background-color: #f1f1f1;
  }
</style>
