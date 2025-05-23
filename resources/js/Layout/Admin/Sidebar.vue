<template>
  <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" @mouseenter="toggleMenuHover"
    @mouseleave="removeMenuHover">
    <div class="app-brand demo mb-2">
      <InertiaLink :href="route('admin.dashboard')" class="app-brand-link">
        <span class="app-brand-logo demo" style="height: 70%;">
          <img alt="Logo" src="/public/admin_assets/logo/logo.png" style="height: 60px; width: auto;" />
        </span>
        <span class="app-brand-text demo menu-text fw-bold fs-6"> {{ page?.props?.appName }} </span>
      </InertiaLink>
      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto" @click="toggleMenuCollapse">
        <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
        <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
      </a>
    </div>

    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
      <li class="menu-item" :class="{ 'active': $page.url.startsWith('/admin/dashboard') }">
        <Link :href="route('admin.dashboard')" class="menu-link">
        <Icon icon="iconoir:home-simple" width="21" height="21" class="menu-icon" />
        <div>Dashboard</div>
        </Link>
      </li>
      <li class="menu-item"
        :class="{ 'open': $page.url.startsWith('/admin/users/') || $page.url.startsWith('/admin/service-providers/') }">
        <a href="javascript:void(0);" class="menu-link menu-toggle"
          :class="{ 'active': $page.url.startsWith('/admin/users/list') }">
          <!-- <i class="menu-icon tf-icons ti ti-smart-home"></i> -->
          <Icon icon="ph:users-light" width="21" height="21" class="menu-icon" />
          <div data-i18n="Dashboards">Users</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item"
            :class="{ 'active': $page.url.startsWith('/admin/users/list') || $page.url.startsWith('/admin/users/create') }">
            <Link :href="route('admin.users.list')" class="menu-link">
            <div data-i18n="List">Clients</div>
            </Link>
          </li>
          <li class="menu-item"
            :class="{ 'active': $page.url.startsWith('/admin/service-providers/list') || $page.url.startsWith('/admin/service-providers/create') }">
            <Link :href="route('admin.service-providers.list')" class="menu-link">
            <div data-i18n="List">Service Providers</div>
            </Link>
          </li>
        </ul>
      </li>
      <li class="menu-item" :class="{ 'open': $page.url.startsWith('/admin/cms/') }">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <Icon icon="gridicons:pages" width="21" height="21" class="menu-icon" />
          <div data-i18n="Dashboards">CMS</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item" :class="{ 'active': $page.url.startsWith('/admin/cms/list') }">
            <Link :href="route('admin.cms.list')" class="menu-link">
            <div data-i18n="List">List</div>
            </Link>
          </li>
          <li class="menu-item" :class="{ 'active': $page.url.startsWith('/admin/cms/create') }">
            <Link :href="route('admin.cms.create')" class="menu-link">
            <div>Create</div>
            </Link>
          </li>
        </ul>
      </li>
      <li class="menu-item" :class="{ 'open': $page.url.startsWith('/admin/blog/') }">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <Icon class="menu-icon" icon="fa6-solid:blog" width="21" height="21" />
          <div>Blog</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item" :class="{ 'active': $page.url.startsWith('/admin/blog/list') }">
            <Link :href="route('admin.blog.list')" class="menu-link">
            <div data-i18n="List">List</div>
            </Link>
          </li>
          <li class="menu-item" :class="{ 'active': $page.url.startsWith('/admin/blog/create') }">
            <Link :href="route('admin.blog.create')" class="menu-link">
            <div>Create</div>
            </Link>
          </li>
        </ul>
      </li>
      <li class="menu-item" :class="{ 'open': $page.url.startsWith('/admin/blog-category/') }">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <Icon class="menu-icon" icon="iconamoon:category-bold" width="21" height="21" />
          <div>Blog Category</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item" :class="{ 'active': $page.url.startsWith('/admin/blog-category/list') }">
            <Link :href="route('admin.blog-category.list')" class="menu-link">
            <div data-i18n="List">List</div>
            </Link>
          </li>
          <li class="menu-item" :class="{ 'active': $page.url.startsWith('/admin/blog-category/create') }">
            <Link :href="route('admin.blog-category.create')" class="menu-link">
            <div>Create</div>
            </Link>
          </li>
        </ul>
      </li>
      <li class="menu-item" :class="{ 'open': $page.url.startsWith('/admin/category/') }">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <Icon icon="iconamoon:category-bold" width="21" height="21" class="menu-icon" />
          <div data-i18n="Dashboards">Service Category</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item" :class="{ 'active': $page.url.startsWith('/admin/category/list') }">
            <Link :href="route('admin.category.list')" class="menu-link">
            <div data-i18n="List">List</div>
            </Link>
          </li>
          <li class="menu-item" :class="{ 'active': $page.url.startsWith('/admin/category/create') }">
            <Link :href="route('admin.category.create')" class="menu-link">
            <div>Create</div>
            </Link>
          </li>
        </ul>
      </li>
      <li class="menu-item" :class="{ 'open': $page.url.startsWith('/admin/service/') }">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <Icon icon="material-symbols-light:service-toolbox" width="24" height="24" class="menu-icon" />
          <div data-i18n="Dashboards">Services</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item" :class="{ 'active': $page.url.startsWith('/admin/service/list') }">
            <Link :href="route('admin.service.list')" class="menu-link">
            <div data-i18n="List">List</div>
            </Link>
          </li>
          <li class="menu-item" :class="{ 'active': $page.url.startsWith('/admin/service/create') }">
            <Link :href="route('admin.service.create')" class="menu-link">
            <div>Create</div>
            </Link>
          </li>
        </ul>
      </li>
      <li class="menu-item" :class="{ 'open': $page.url.startsWith('/admin/inquiry/') }">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <Icon icon="ix:inquiry-mail" width="21" height="21" class="menu-icon" />
          <div data-i18n="Dashboards">Inquiry</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item" :class="{ 'active': $page.url.startsWith('/admin/inquiry/list') }">
            <Link :href="route('admin.inquiry.list')" class="menu-link">
            <div data-i18n="List">List</div>
            </Link>
          </li>
        </ul>
      </li>
      <li class="menu-item" :class="{ 'active': $page.url.startsWith('/admin/site-setting') }">
        <Link :href="route('admin.site-setting.index')" class="menu-link">
        <Icon icon="iconoir:settings" width="21" height="21" class="menu-icon" />
        <div>Site Settings</div>
        </Link>
      </li>
    </ul>
  </aside>
</template>
<script setup>
import { onMounted } from 'vue';
import { Link as InertiaLink, usePage } from '@inertiajs/vue3';

const page = usePage();

onMounted(() => {
  const menu = document.querySelector('#layout-menu');
  if (menu) {
    menu.addEventListener('mouseenter', () => {
      if (document.documentElement.classList.contains('layout-menu-collapsed')) {
        menu.classList.add('layout-menu-hover');
      }
    });
    menu.addEventListener('mouseleave', () => {
      menu.classList.remove('layout-menu-hover');
    });

    new Menu(menu, {
      accordion: false
    });
  }
});


const toggleMenuHover = () => {
  if (document.documentElement.classList.contains('layout-menu-collapsed')) {
    document.documentElement.classList.add('layout-menu-hover');
  } else {
    document.documentElement.classList.remove('layout-menu-hover');
  }
};

const removeMenuHover = () => {
  if (document.documentElement.classList.contains('layout-menu-collapsed')) {
    document.documentElement.classList.remove('layout-menu-hover');
  }
};

const toggleMenuCollapse = () => {
  document.documentElement.classList.toggle('layout-menu-collapsed');
  document.documentElement.classList.toggle('layout-menu-expanded');
  const layoutMenu = document.getElementById("layout-menu");
  if (layoutMenu) {
    layoutMenu.style.removeProperty('z-index');
  }
};
</script>
