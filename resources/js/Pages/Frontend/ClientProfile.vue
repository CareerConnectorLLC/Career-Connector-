<script setup>
import { computed, onMounted } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import ProfileDropdown from "../../components/frontend/customer/ProfileDropdown.vue";
import FancyBoxModal from "../../components/frontend/FancyBoxModal.vue";
import ChangePasswordForm from "../../components/frontend/ChangePasswordForm.vue";
import ProfileUpdateForm from "../../components/frontend/ProfileUpdateForm.vue";
import CustomerSidebar from "../../components/frontend/customer/LeftSidebar.vue";

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

const page = usePage()

const user = computed(() => page.props.user)

const hideModal = () => {
    $.fancybox.close()
}
</script>

<template>
    <div class="dashboard-sec my-profile">
        <div class="dashboard-container">
            <div class="dashboard-head">
                <button class="dashboard-toggler">
                    <span class="stick"></span>
                </button>
                <h1 class="text-capitalize">My profile</h1>
                <div class="search-sec">
                    <div class="serach-inner-wrap">
                        <div class="search-form">
                            <form>
                                <div class="search-inner">
                                    <div class="search-inner-wrap">
                                        <input type="text" placeholder="Search">
                                        <a class="search-filter-button" href="">
                                            <img src="/public/frontend_assets/images/search-icon.svg" alt="search-icon">
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <a href="#url" class="sarch-toggler">
                                <img src="/public/frontend_assets/images/d-search-normal.svg" alt="search-normal">
                            </a>
                        </div>

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
                <CustomerSidebar />
                <!-- Left sidebar panel -->
                <div class="dashboard-right-panel">
                    <div class="dashboard-right-inner">
                        <div class="my-profile-wrap">
                            <div class="my-profile-left">
                                <div class="my-profile-card">
                                    <div class="my-profile-head">
                                        <h2>My information</h2>
                                        <a data-fancybox="" data-src="#edit-profile" class="primary-btn"
                                            href="#url">Edit</a>
                                    </div>

                                    <div class="my-profile-inner-wrap">
                                        <div class="profile-details">
                                            <figure v-if="user.profile_photo_url">
                                                <img :src="user.profile_photo_url"
                                                    alt="profile-image">
                                            </figure>
                                            <figure v-else>
                                                <img src="/public/frontend_assets/images/my-profile-image.png"
                                                    alt="profile-image">
                                            </figure>
                                            <h3>{{ user.full_name }}</h3>
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
                                                            <p>
                                                                {{ user.location }}
                                                            </p>
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
                                                            <p>
                                                                {{ (user.profile) ? user.profile.date_of_birth : '' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="my-profile-right">
                                <div class="card-info">
                                    <div class="card-info-head">
                                        <h3>Card details</h3>
                                        <a class="primary-btn" href="#url">Add card</a>
                                    </div>

                                    <div class="online-card">
                                        <div class="card-cont">
                                            <h4>John Doe</h4>
                                            <p>XXXX XXXX XXXX 4569</p>
                                        </div>

                                        <div class="online-card-details">
                                            <div class="card-cont">
                                                <h4>Expiry date</h4>
                                                <p>XX / XX</p>
                                            </div>
                                            <div class="card-cont">
                                                <h4>CVV</h4>
                                                <p>000</p>
                                            </div>
                                        </div>
                                        <a class="delete-btn" href="#url"><img
                                                src="/public/frontend_assets/images/trash.svg" alt="trash"></a>

                                        <figure class="card-type"><img
                                                src="/public/frontend_assets/images/card-type.svg" alt="card-type">
                                        </figure>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sidebar-overlay"></div>
            </div>

            <!-- Change Password Modal -->
            <FancyBoxModal heading="Change Password" id="change-passpord" caption="Some caption text for development.">
                <ChangePasswordForm v-on:password-update-success="hideModal" />
            </FancyBoxModal>

            <!-- Profile Edit Modal -->
            <FancyBoxModal heading="Edit Profile" id="edit-profile" caption="Some caption text for development.">
                <ProfileUpdateForm :user="user" :url="$page.url" v-on:profile-update-success="hideModal" />
            </FancyBoxModal>
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