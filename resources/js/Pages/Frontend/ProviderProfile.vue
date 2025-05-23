<script setup>
import { computed, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import ProfileDropdown from "../../components/frontend/provider/ProfileDropdown.vue";
import ProviderSidebar from "../../components/frontend/provider/SideNavigation.vue";
import FancyBoxModal from "../../components/frontend/FancyBoxModal.vue";
import ChangePasswordForm from "../../components/frontend/ChangePasswordForm.vue";
import ProfileUpdateForm from "../../components/frontend/ProfileUpdateForm.vue";

const page = usePage()
const user = computed(() => page.props.user)

onMounted(() => {
    $("[data-fancybox]").fancybox({
        touch: false,
        hideOnOverlayClick: false,
        afterClose: function () {
            if (document.querySelector('.errorMsg').innerText != '') {
                document.querySelector('.errorMsg').innerText = ''
            }
        }
    })
})

const hideModal = () => {
    $.fancybox.close()
}
</script>

<template>
    <div class="dashboard-sec">
        <div class="dashboard-container">
            <div class="dashboard-head">
                <button class="dashboard-toggler">
                    <span class="stick"></span>
                </button>
                <h1 class="text-capitalize">My profile</h1>
                <div class="search-sec">
                    <div class="serach-inner-wrap">
                        <div class="nofication">
                            <a href="">
                                <figure>
                                    <img src="/public/frontend_assets/images/notification.svg" alt="nofication">
                                    <span class="notification-indecator"></span>
                                </figure>
                            </a>
                        </div>

                        <!-- Profile Dropdown -->
                        <ProfileDropdown :user="user" />
                    </div>
                </div>
            </div>
            <div class="dashboard-inner-wrap">

                <!-- Left sidebar panel -->
                <ProviderSidebar />
                <!-- Left sidebar panel -->

                <div class="dashboard-right-panel">
                    <div class="dashboard-right-inner">
                        <div class="dashboard-inner-left-wrap">
                            <div class="profile-wrap row">
                                <div class="col-md-8 profile-col">
                                    <div class="my-profile-card">
                                        <div class="my-profile-head">
                                            <h2>My information</h2>
                                            <a data-fancybox="" data-src="#edit-profile" class="primary-btn"
                                                href="#url">Edit</a>
                                        </div>

                                        <div class="my-profile-inner-wrap">
                                            <div class="profile-details">
                                                <figure>
                                                    <img v-if="user.profile_photo_url" :src="user.profile_photo_url"
                                                        alt="profile-image">
                                                    <img v-else
                                                        src="/public/frontend_assets/images/my-profile-image.png"
                                                        alt="profile-image">
                                                </figure>
                                                <h3>{{ user.name }}</h3>
                                                <a data-fancybox="" data-src="#change-passpord" class="outline-btn"
                                                    href="#url">Change password</a>
                                            </div>

                                            <div class="profile-info">
                                                <ul>
                                                    <li>
                                                        <div class="profile-info-wrap">
                                                            <figure>
                                                                <img src="/public/frontend_assets/images/sms-notification.svg"
                                                                    alt="sms-notification">
                                                            </figure>
                                                            <div class="profile-info-cont">
                                                                <h4>Email address</h4>
                                                                <a href="">{{ user.email }}</a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="profile-info-wrap">
                                                            <figure>
                                                                <img src="/public/frontend_assets/images/call-calling-green.svg"
                                                                    alt="call-calling">
                                                            </figure>
                                                            <div class="profile-info-cont">
                                                                <h4>Phone no.</h4>
                                                                <a href="">{{ user.phone }}</a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="profile-info-wrap">
                                                            <figure>
                                                                <img src="/public/frontend_assets/images/location-green.svg"
                                                                    alt="location">
                                                            </figure>
                                                            <div class="profile-info-cont">
                                                                <h4>Location</h4>
                                                                <p>{{ user.location }}</p>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="profile-info-wrap">
                                                            <figure>
                                                                <img src="/public/frontend_assets/images/calendar-green.svg"
                                                                    alt="calendar">
                                                            </figure>
                                                            <div class="profile-info-cont">
                                                                <h4>Date of birth</h4>
                                                                <p v-text="(user.profile) ? user.profile.date_of_birth : ''"></p>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 profile-col">
                                    <div class="files-listing">
                                        <div class="my-profile-head">
                                            <h3>Files</h3>
                                            <a data-fancybox="" data-src="#upload-document" class="primary-btn"
                                                href="#url">Add new</a>
                                        </div>

                                        <ul>
                                            <li>
                                                <a href="#url">
                                                    <figure>
                                                        <img src="/public/frontend_assets/images/pdf.svg" alt="pdf">
                                                    </figure>
                                                    <div class="pdf-detsils">
                                                        <div class="file-head">
                                                            <p>Lorem Ipsum ....</p>
                                                            <span>Aug 14, 25</span>
                                                        </div>
                                                        <p>
                                                            230.84 MB
                                                        </p>
                                                    </div>
                                                </a>
                                                <div class="dot-option">
                                                    <a class="notification-option-btn dropdown-toggle" href="#url"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span><img src="/public/frontend_assets/images/dots.svg"
                                                                alt="dots"></span>
                                                    </a>
                                                    <div class="dots-drop-dowm dropdown-menu">
                                                        <ul>
                                                            <li><a href="#url">My Profile 1</a></li>
                                                            <li><a href="#url">My Profile 2</a></li>
                                                            <li><a href="#url">My Profile 3</a></li>
                                                            <li><a href="#url">My Profile 4</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <a href="#url">
                                                    <figure><img src="/public/frontend_assets/images/pdf.svg" alt="pdf">
                                                    </figure>
                                                    <div class="pdf-detsils">
                                                        <div class="file-head">
                                                            <p>Lorem Ipsum ....</p>
                                                            <span>Aug 14, 25</span>
                                                        </div>
                                                        <p>
                                                            230.84 MB
                                                        </p>
                                                    </div>
                                                </a>
                                                <div class="dot-option">
                                                    <a class="notification-option-btn dropdown-toggle" href="#url"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span><img src="/public/frontend_assets/images/dots.svg"
                                                                alt="dots"></span>
                                                    </a>
                                                    <div class="dots-drop-dowm dropdown-menu">
                                                        <ul>
                                                            <li><a href="#url">My Profile 1</a></li>
                                                            <li><a href="#url">My Profile 2</a></li>
                                                            <li><a href="#url">My Profile 3</a></li>
                                                            <li><a href="#url">My Profile 4</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <a href="#url">
                                                    <figure><img src="/public/frontend_assets/images/pdf.svg" alt="pdf">
                                                    </figure>
                                                    <div class="pdf-detsils">
                                                        <div class="file-head">
                                                            <p>Lorem Ipsum ....</p>
                                                            <span>Aug 14, 25</span>
                                                        </div>
                                                        <p>
                                                            230.84 MB
                                                        </p>
                                                    </div>
                                                </a>
                                                <div class="dot-option">
                                                    <a class="notification-option-btn dropdown-toggle" href="#url"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span><img src="/public/frontend_assets/images/dots.svg"
                                                                alt="dots"></span>
                                                    </a>
                                                    <div class="dots-drop-dowm dropdown-menu">
                                                        <ul>
                                                            <li><a href="#url">My Profile 1</a></li>
                                                            <li><a href="#url">My Profile 2</a></li>
                                                            <li><a href="#url">My Profile 3</a></li>
                                                            <li><a href="#url">My Profile 4</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <a href="#url">
                                                    <figure><img src="/public/frontend_assets/images/pdf.svg" alt="pdf">
                                                    </figure>
                                                    <div class="pdf-detsils">
                                                        <div class="file-head">
                                                            <p>Lorem Ipsum ....</p>
                                                            <span>Aug 14, 25</span>
                                                        </div>
                                                        <p>
                                                            230.84 MB
                                                        </p>
                                                    </div>
                                                </a>
                                                <div class="dot-option">
                                                    <a class="notification-option-btn dropdown-toggle" href="#url"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span><img src="/public/frontend_assets/images/dots.svg"
                                                                alt="dots"></span>
                                                    </a>
                                                    <div class="dots-drop-dowm dropdown-menu">
                                                        <ul>
                                                            <li><a href="#url">My Profile 1</a></li>
                                                            <li><a href="#url">My Profile 2</a></li>
                                                            <li><a href="#url">My Profile 3</a></li>
                                                            <li><a href="#url">My Profile 4</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <a href="#url">
                                                    <figure><img src="/public/frontend_assets/images/pdf.svg" alt="pdf">
                                                    </figure>
                                                    <div class="pdf-detsils">
                                                        <div class="file-head">
                                                            <p>Lorem Ipsum ....</p>
                                                            <span>Aug 14, 25</span>
                                                        </div>
                                                        <p>
                                                            230.84 MB
                                                        </p>
                                                    </div>
                                                </a>
                                                <div class="dot-option">
                                                    <a class="notification-option-btn dropdown-toggle" href="#url"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span><img src="/public/frontend_assets/images/dots.svg"
                                                                alt="dots"></span>
                                                    </a>
                                                    <div class="dots-drop-dowm dropdown-menu">
                                                        <ul>
                                                            <li><a href="#url">My Profile 1</a></li>
                                                            <li><a href="#url">My Profile 2</a></li>
                                                            <li><a href="#url">My Profile 3</a></li>
                                                            <li><a href="#url">My Profile 4</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-4 profile-col">
                                    <div class="my-profile-card">
                                        <div class="my-profile-head">
                                            <h2>Account details</h2>
                                            <a data-fancybox="" data-src="#add-account" class="primary-btn"
                                                href="#url">Add new</a>
                                        </div>

                                        <div class="card-list">
                                            <div class="online-card">
                                                <div class="card-cont">
                                                    <h4>Account No</h4>
                                                    <p>XXXX XXXX XXXX 4569</p>
                                                </div>
                                                <div class="card-cont">
                                                    <h4>Account Holder</h4>
                                                    <p>Esther Howard</p>
                                                </div>
                                                <div class="card-cont">
                                                    <h4>IFSC Code</h4>
                                                    <p>5683GH&amp;K</p>
                                                </div>
                                                <div class="btn-list">
                                                    <a class="edit-btn" href="#url"><img
                                                            src="/public/frontend_assets/images/edit.svg"
                                                            alt="edit"></a>
                                                    <a class="delete-btn" href="#url"><img
                                                            src="/public/frontend_assets/images/trash.svg"
                                                            alt="trash"></a>
                                                </div>

                                            </div>
                                            <div class="online-card">
                                                <div class="card-cont">
                                                    <h4>Account No</h4>
                                                    <p>XXXX XXXX XXXX 4569</p>
                                                </div>
                                                <div class="card-cont">
                                                    <h4>Account Holder</h4>
                                                    <p>Esther Howard</p>
                                                </div>
                                                <div class="card-cont">
                                                    <h4>IFSC Code</h4>
                                                    <p>5683GH&amp;K</p>
                                                </div>
                                                <div class="btn-list">
                                                    <a class="edit-btn" href="#url"><img
                                                            src="/public/frontend_assets/images/edit.svg"
                                                            alt="edit"></a>
                                                    <a class="delete-btn" href="#url"><img
                                                            src="/public/frontend_assets/images/trash.svg"
                                                            alt="trash"></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8 profile-col">
                                    <div class="my-profile-card">
                                        <div class="my-profile-head">
                                            <h2>My services</h2>
                                            <a data-fancybox="" data-src="#add-service" class="primary-btn"
                                                href="#url">Add new</a>
                                        </div>

                                        <div
                                            class="provider-service-slider slick-initialized slick-slider slick-dotted">
                                            <div class="slick-list draggable">
                                                <div class="slick-track"
                                                    style="opacity: 1; width: 4970px; transform: translate3d(-994px, 0px, 0px);">
                                                    <div class="provider-service-slide slick-slide slick-cloned"
                                                        data-slick-index="-2" id="" aria-hidden="true" tabindex="-1"
                                                        style="width: 497px;">
                                                        <div class="provider-service-card">
                                                            <figure>
                                                                <img src="/public/frontend_assets/images/provider-serive-image-01.png"
                                                                    alt="provider-serive-image">
                                                                <div class="dot-option">
                                                                    <a class="notification-option-btn dropdown-toggle"
                                                                        href="#url" data-bs-toggle="dropdown"
                                                                        aria-expanded="false" tabindex="-1">
                                                                        <span><img
                                                                                src="/public/frontend_assets/images/dots.svg"
                                                                                alt="dots"></span>
                                                                    </a>
                                                                    <div class="dots-drop-dowm dropdown-menu">
                                                                        <ul>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    1</a></li>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    2</a></li>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    3</a></li>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    4</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </figure>
                                                            <div class="provider-service-cont">
                                                                <div class="my-profile-head">
                                                                    <h4> <a href=""
                                                                            tabindex="-1">Service name goes here...</a>
                                                                    </h4>
                                                                    <p class="price">$40</p>
                                                                </div>
                                                                <p>
                                                                    Pellentesque interdum felis quis dui euismod,
                                                                    dignissim fermentum elit elementum. Mauris malesuada
                                                                    eu mauris vel feugiat. Mauris vel fermentum purus.
                                                                </p>
                                                                <a class="booknow" href=""
                                                                    tabindex="-1">Read more</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="provider-service-slide slick-slide slick-cloned"
                                                        data-slick-index="-1" id="" aria-hidden="true" tabindex="-1"
                                                        style="width: 497px;">
                                                        <div class="provider-service-card">
                                                            <figure>
                                                                <img src="/public/frontend_assets/images/provider-serive-image-02.png"
                                                                    alt="provider-serive-image">
                                                                <div class="dot-option">
                                                                    <a class="notification-option-btn dropdown-toggle"
                                                                        href="#url" data-bs-toggle="dropdown"
                                                                        aria-expanded="false" tabindex="-1">
                                                                        <span><img
                                                                                src="/public/frontend_assets/images/dots.svg"
                                                                                alt="dots"></span>
                                                                    </a>
                                                                    <div class="dots-drop-dowm dropdown-menu">
                                                                        <ul>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    1</a></li>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    2</a></li>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    3</a></li>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    4</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </figure>
                                                            <div class="provider-service-cont">
                                                                <div class="my-profile-head">
                                                                    <h4> <a href=""
                                                                            tabindex="-1">Service name goes here...</a>
                                                                    </h4>
                                                                    <p class="price">$40</p>
                                                                </div>
                                                                <p>
                                                                    Pellentesque interdum felis quis dui euismod,
                                                                    dignissim fermentum elit elementum. Mauris malesuada
                                                                    eu mauris vel feugiat. Mauris vel fermentum purus.
                                                                </p>
                                                                <a class="booknow" href=""
                                                                    tabindex="-1">Read more</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="provider-service-slide slick-slide slick-current slick-active"
                                                        data-slick-index="0" aria-hidden="false" tabindex="0"
                                                        role="tabpanel" id="slick-slide00"
                                                        aria-describedby="slick-slide-control00" style="width: 497px;">
                                                        <div class="provider-service-card">
                                                            <figure>
                                                                <img src="/public/frontend_assets/images/provider-serive-image-01.png"
                                                                    alt="provider-serive-image">
                                                                <div class="dot-option">
                                                                    <a class="notification-option-btn dropdown-toggle"
                                                                        href="#url" data-bs-toggle="dropdown"
                                                                        aria-expanded="false" tabindex="0">
                                                                        <span><img
                                                                                src="/public/frontend_assets/images/dots.svg"
                                                                                alt="dots"></span>
                                                                    </a>
                                                                    <div class="dots-drop-dowm dropdown-menu">
                                                                        <ul>
                                                                            <li><a href="#url" tabindex="0">My Profile
                                                                                    1</a></li>
                                                                            <li><a href="#url" tabindex="0">My Profile
                                                                                    2</a></li>
                                                                            <li><a href="#url" tabindex="0">My Profile
                                                                                    3</a></li>
                                                                            <li><a href="#url" tabindex="0">My Profile
                                                                                    4</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </figure>
                                                            <div class="provider-service-cont">
                                                                <div class="my-profile-head">
                                                                    <h4> <a href=""
                                                                            tabindex="0">Service name goes here...</a>
                                                                    </h4>
                                                                    <p class="price">$40</p>
                                                                </div>
                                                                <p>
                                                                    Pellentesque interdum felis quis dui euismod,
                                                                    dignissim fermentum elit elementum. Mauris malesuada
                                                                    eu mauris vel feugiat. Mauris vel fermentum purus.
                                                                </p>
                                                                <a class="booknow" href=""
                                                                    tabindex="0">Read more</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="provider-service-slide slick-slide slick-active"
                                                        data-slick-index="1" aria-hidden="false" tabindex="0"
                                                        role="tabpanel" id="slick-slide01"
                                                        aria-describedby="slick-slide-control01" style="width: 497px;">
                                                        <div class="provider-service-card">
                                                            <figure>
                                                                <img src="/public/frontend_assets/images/provider-serive-image-02.png"
                                                                    alt="provider-serive-image">
                                                                <div class="dot-option">
                                                                    <a class="notification-option-btn dropdown-toggle"
                                                                        href="#url" data-bs-toggle="dropdown"
                                                                        aria-expanded="false" tabindex="0">
                                                                        <span><img
                                                                                src="/public/frontend_assets/images/dots.svg"
                                                                                alt="dots"></span>
                                                                    </a>
                                                                    <div class="dots-drop-dowm dropdown-menu">
                                                                        <ul>
                                                                            <li><a href="#url" tabindex="0">My Profile
                                                                                    1</a></li>
                                                                            <li><a href="#url" tabindex="0">My Profile
                                                                                    2</a></li>
                                                                            <li><a href="#url" tabindex="0">My Profile
                                                                                    3</a></li>
                                                                            <li><a href="#url" tabindex="0">My Profile
                                                                                    4</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </figure>
                                                            <div class="provider-service-cont">
                                                                <div class="my-profile-head">
                                                                    <h4> <a href=""
                                                                            tabindex="0">Service name goes here...</a>
                                                                    </h4>
                                                                    <p class="price">$40</p>
                                                                </div>
                                                                <p>
                                                                    Pellentesque interdum felis quis dui euismod,
                                                                    dignissim fermentum elit elementum. Mauris malesuada
                                                                    eu mauris vel feugiat. Mauris vel fermentum purus.
                                                                </p>
                                                                <a class="booknow" href=""
                                                                    tabindex="0">Read more</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="provider-service-slide slick-slide" data-slick-index="2"
                                                        aria-hidden="true" tabindex="-1" role="tabpanel"
                                                        id="slick-slide02" aria-describedby="slick-slide-control02"
                                                        style="width: 497px;">
                                                        <div class="provider-service-card">
                                                            <figure>
                                                                <img src="/public/frontend_assets/images/provider-serive-image-01.png"
                                                                    alt="provider-serive-image">
                                                                <div class="dot-option">
                                                                    <a class="notification-option-btn dropdown-toggle"
                                                                        href="#url" data-bs-toggle="dropdown"
                                                                        aria-expanded="false" tabindex="-1">
                                                                        <span><img
                                                                                src="/public/frontend_assets/images/dots.svg"
                                                                                alt="dots"></span>
                                                                    </a>
                                                                    <div class="dots-drop-dowm dropdown-menu">
                                                                        <ul>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    1</a></li>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    2</a></li>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    3</a></li>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    4</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </figure>
                                                            <div class="provider-service-cont">
                                                                <div class="my-profile-head">
                                                                    <h4> <a href=""
                                                                            tabindex="-1">Service name goes here...</a>
                                                                    </h4>
                                                                    <p class="price">$40</p>
                                                                </div>
                                                                <p>
                                                                    Pellentesque interdum felis quis dui euismod,
                                                                    dignissim fermentum elit elementum. Mauris malesuada
                                                                    eu mauris vel feugiat. Mauris vel fermentum purus.
                                                                </p>
                                                                <a class="booknow" href=""
                                                                    tabindex="-1">Read more</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="provider-service-slide slick-slide" data-slick-index="3"
                                                        aria-hidden="true" tabindex="-1" role="tabpanel"
                                                        id="slick-slide03" aria-describedby="slick-slide-control03"
                                                        style="width: 497px;">
                                                        <div class="provider-service-card">
                                                            <figure>
                                                                <img src="/public/frontend_assets/images/provider-serive-image-02.png"
                                                                    alt="provider-serive-image">
                                                                <div class="dot-option">
                                                                    <a class="notification-option-btn dropdown-toggle"
                                                                        href="#url" data-bs-toggle="dropdown"
                                                                        aria-expanded="false" tabindex="-1">
                                                                        <span><img
                                                                                src="/public/frontend_assets/images/dots.svg"
                                                                                alt="dots"></span>
                                                                    </a>
                                                                    <div class="dots-drop-dowm dropdown-menu">
                                                                        <ul>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    1</a></li>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    2</a></li>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    3</a></li>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    4</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </figure>
                                                            <div class="provider-service-cont">
                                                                <div class="my-profile-head">
                                                                    <h4> <a href=""
                                                                            tabindex="-1">Service name goes here...</a>
                                                                    </h4>
                                                                    <p class="price">$40</p>
                                                                </div>
                                                                <p>
                                                                    Pellentesque interdum felis quis dui euismod,
                                                                    dignissim fermentum elit elementum. Mauris malesuada
                                                                    eu mauris vel feugiat. Mauris vel fermentum purus.
                                                                </p>
                                                                <a class="booknow" href=""
                                                                    tabindex="-1">Read more</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="provider-service-slide slick-slide slick-cloned"
                                                        data-slick-index="4" id="" aria-hidden="true" tabindex="-1"
                                                        style="width: 497px;">
                                                        <div class="provider-service-card">
                                                            <figure>
                                                                <img src="/public/frontend_assets/images/provider-serive-image-01.png"
                                                                    alt="provider-serive-image">
                                                                <div class="dot-option">
                                                                    <a class="notification-option-btn dropdown-toggle"
                                                                        href="#url" data-bs-toggle="dropdown"
                                                                        aria-expanded="false" tabindex="-1">
                                                                        <span><img
                                                                                src="/public/frontend_assets/images/dots.svg"
                                                                                alt="dots"></span>
                                                                    </a>
                                                                    <div class="dots-drop-dowm dropdown-menu">
                                                                        <ul>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    1</a></li>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    2</a></li>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    3</a></li>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    4</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </figure>
                                                            <div class="provider-service-cont">
                                                                <div class="my-profile-head">
                                                                    <h4> <a href=""
                                                                            tabindex="-1">Service name goes here...</a>
                                                                    </h4>
                                                                    <p class="price">$40</p>
                                                                </div>
                                                                <p>
                                                                    Pellentesque interdum felis quis dui euismod,
                                                                    dignissim fermentum elit elementum. Mauris malesuada
                                                                    eu mauris vel feugiat. Mauris vel fermentum purus.
                                                                </p>
                                                                <a class="booknow" href=""
                                                                    tabindex="-1">Read more</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="provider-service-slide slick-slide slick-cloned"
                                                        data-slick-index="5" id="" aria-hidden="true" tabindex="-1"
                                                        style="width: 497px;">
                                                        <div class="provider-service-card">
                                                            <figure>
                                                                <img src="/public/frontend_assets/images/provider-serive-image-02.png"
                                                                    alt="provider-serive-image">
                                                                <div class="dot-option">
                                                                    <a class="notification-option-btn dropdown-toggle"
                                                                        href="#url" data-bs-toggle="dropdown"
                                                                        aria-expanded="false" tabindex="-1">
                                                                        <span><img
                                                                                src="/public/frontend_assets/images/dots.svg"
                                                                                alt="dots"></span>
                                                                    </a>
                                                                    <div class="dots-drop-dowm dropdown-menu">
                                                                        <ul>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    1</a></li>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    2</a></li>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    3</a></li>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    4</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </figure>
                                                            <div class="provider-service-cont">
                                                                <div class="my-profile-head">
                                                                    <h4> <a href=""
                                                                            tabindex="-1">Service name goes here...</a>
                                                                    </h4>
                                                                    <p class="price">$40</p>
                                                                </div>
                                                                <p>
                                                                    Pellentesque interdum felis quis dui euismod,
                                                                    dignissim fermentum elit elementum. Mauris malesuada
                                                                    eu mauris vel feugiat. Mauris vel fermentum purus.
                                                                </p>
                                                                <a class="booknow" href=""
                                                                    tabindex="-1">Read more</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="provider-service-slide slick-slide slick-cloned"
                                                        data-slick-index="6" id="" aria-hidden="true" tabindex="-1"
                                                        style="width: 497px;">
                                                        <div class="provider-service-card">
                                                            <figure>
                                                                <img src="/public/frontend_assets/images/provider-serive-image-01.png"
                                                                    alt="provider-serive-image">
                                                                <div class="dot-option">
                                                                    <a class="notification-option-btn dropdown-toggle"
                                                                        href="#url" data-bs-toggle="dropdown"
                                                                        aria-expanded="false" tabindex="-1">
                                                                        <span><img
                                                                                src="/public/frontend_assets/images/dots.svg"
                                                                                alt="dots"></span>
                                                                    </a>
                                                                    <div class="dots-drop-dowm dropdown-menu">
                                                                        <ul>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    1</a></li>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    2</a></li>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    3</a></li>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    4</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </figure>
                                                            <div class="provider-service-cont">
                                                                <div class="my-profile-head">
                                                                    <h4> <a href=""
                                                                            tabindex="-1">Service name goes here...</a>
                                                                    </h4>
                                                                    <p class="price">$40</p>
                                                                </div>
                                                                <p>
                                                                    Pellentesque interdum felis quis dui euismod,
                                                                    dignissim fermentum elit elementum. Mauris malesuada
                                                                    eu mauris vel feugiat. Mauris vel fermentum purus.
                                                                </p>
                                                                <a class="booknow" href=""
                                                                    tabindex="-1">Read more</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="provider-service-slide slick-slide slick-cloned"
                                                        data-slick-index="7" id="" aria-hidden="true" tabindex="-1"
                                                        style="width: 497px;">
                                                        <div class="provider-service-card">
                                                            <figure>
                                                                <img src="/public/frontend_assets/images/provider-serive-image-02.png"
                                                                    alt="provider-serive-image">
                                                                <div class="dot-option">
                                                                    <a class="notification-option-btn dropdown-toggle"
                                                                        href="#url" data-bs-toggle="dropdown"
                                                                        aria-expanded="false" tabindex="-1">
                                                                        <span><img
                                                                                src="/public/frontend_assets/images/dots.svg"
                                                                                alt="dots"></span>
                                                                    </a>
                                                                    <div class="dots-drop-dowm dropdown-menu">
                                                                        <ul>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    1</a></li>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    2</a></li>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    3</a></li>
                                                                            <li><a href="#url" tabindex="-1">My Profile
                                                                                    4</a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </figure>
                                                            <div class="provider-service-cont">
                                                                <div class="my-profile-head">
                                                                    <h4>
                                                                        <a href="" tabindex="-1">Service name goes here...</a>
                                                                    </h4>
                                                                    <p class="price">$40</p>
                                                                </div>
                                                                <p>
                                                                    Pellentesque interdum felis quis dui euismod,
                                                                    dignissim fermentum elit elementum. Mauris malesuada
                                                                    eu mauris vel feugiat. Mauris vel fermentum purus.
                                                                </p>
                                                                <a class="booknow" href="" tabindex="-1">Read more</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <ul class="slick-dots" style="" role="tablist">
                                                <li class="slick-active" role="presentation">
                                                    <button type="button"
                                                        role="tab" id="slick-slide-control00"
                                                        aria-controls="slick-slide00" aria-label="1 of 2" tabindex="0"
                                                        aria-selected="true">1
                                                    </button>
                                                </li>
                                                <li role="presentation"><button type="button" role="tab"
                                                        id="slick-slide-control01" aria-controls="slick-slide01"
                                                        aria-label="2 of 2" tabindex="-1">2</button></li>
                                                <li role="presentation"><button type="button" role="tab"
                                                        id="slick-slide-control02" aria-controls="slick-slide02"
                                                        aria-label="3 of 2" tabindex="-1">3</button></li>
                                                <li role="presentation"><button type="button" role="tab"
                                                        id="slick-slide-control03" aria-controls="slick-slide03"
                                                        aria-label="4 of 2" tabindex="-1">4</button></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Change Password Modal -->
                <FancyBoxModal heading="Change Password" id="change-passpord"
                    caption="Some caption text for development.">
                    <ChangePasswordForm v-on:password-update-success="hideModal" />
                </FancyBoxModal>

                <div class="change-passpord" id="upload-document" style="display: none;">
                    <h2>Upload new document</h2>
                    <p>Lorem ipsum dolor sit amet</p>
                    <form class="login-form">
                        <div class="form-input">
                            <label>Upload document</label>
                            <div class="file-upload-container">
                                <input type="file" accept="image/*">
                                <div class="placeholder">
                                    <img src="/public/frontend_assets/images/document-upload.svg" alt="Upload icon">
                                    <span>Upload your document</span>
                                </div>
                                <img class="preview-img">
                            </div>
                        </div>
                        <div class="form-input">
                            <button type="submit">Save</button>
                        </div>
                    </form>
                </div>

                <div class="change-passpord" id="add-account" style="display: none;">
                    <h2>Add new account </h2>
                    <p>Lorem ipsum dolor sit amet</p>
                    <form class="login-form">
                        <div class="form-input">
                            <label>Account holder name</label>
                            <input type="text" placeholder="Enter name">
                        </div>

                        <div class="form-input">
                            <label>Account number</label>
                            <input type="number" placeholder="Enter">
                        </div>

                        <div class="form-input">
                            <label>IFSC Code</label>
                            <input type="text" placeholder="Enter">
                        </div>

                        <div class="form-input">
                            <button type="submit">Save</button>
                        </div>
                    </form>
                </div>

                <!-- Profile Edit Modal -->
                <FancyBoxModal heading="Edit Profile" id="edit-profile" caption="Some caption text for development.">
                    <ProfileUpdateForm :user="user" :url="$page.url" v-on:profile-update-success="hideModal" />
                </FancyBoxModal>

                <div class="change-passpord for-select2" id="add-service" style="display: none;">
                    <h2>Add new service</h2>
                    <p>Lorem ipsum dolor sit amet</p>

                    <form class="login-form">
                        <div class="form-input">
                            <label>Upload service image</label>
                            <div class="file-upload-container">
                                <input type="file" accept="image/*">
                                <div class="placeholder">
                                    <img src="/public/frontend_assets/images/document-upload.svg" alt="Upload icon">
                                    <span>Upload your document</span>
                                </div>
                                <img class="preview-img">
                            </div>
                        </div>

                        <div class="form-input">
                            <label>Service title</label>
                            <input type="text" placeholder="Enter">
                        </div>

                        <div class="form-input">
                            <label>Service price</label>
                            <input type="text" placeholder="Enter">
                        </div>

                        <div class="form-input">
                            <label>Service description</label>
                            <textarea placeholder="Write here..."></textarea>
                        </div>
                        <div class="form-input">
                            <button type="submit">Save</button>
                        </div>
                    </form>
                </div>
                <div class="sidebar-overlay"></div>
            </div>
        </div>


        <div class="top-left-shape">
            <img src="/public/frontend_assets/images/top-left-image.png" alt="">
        </div>
        <div class="top-right-shape">
            <img src="/public/frontend_assets/images/top-right-image.png" alt="">
        </div>
        <div class="bottom-left-shape">
            <img src="/public/frontend_assets/images/bottom-left-shap.png" alt="">
        </div>
        <div class="bottom-right-shape">
            <img src="/public/frontend_assets/images/bottom-image.png" alt="">
        </div>
        <div class="top-center">
            <img src="/public/frontend_assets/images/center-image.png" alt="">
        </div>
    </div>
</template>