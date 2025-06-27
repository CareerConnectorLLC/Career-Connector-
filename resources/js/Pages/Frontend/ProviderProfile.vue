<script setup>
import { computed, onMounted, onUnmounted, ref, watch } from "vue";
import { usePage, useForm, router } from "@inertiajs/vue3";

import ProfileDropdown from "../../components/frontend/provider/ProfileDropdown.vue";
import ProviderSidebar from "../../components/frontend/provider/SideNavigation.vue";
import FancyBoxModal from "../../components/frontend/FancyBoxModal.vue";
import ChangePasswordForm from "../../components/frontend/ChangePasswordForm.vue";
import ProfileUpdateForm from "../../components/frontend/ProfileUpdateForm.vue";
import BankDetails from "../../components/frontend/provider/BankDetails.vue";
import BankAccountForm from "../../components/frontend/provider/BankAccountForm.vue";
import MyServices from "../../components/frontend/provider/MyServices.vue";
import MyFiles from "../../components/frontend/provider/MyFiles.vue";
import NewServiceItem from "../../components/frontend/provider/NewServiceItem.vue";
import DocUploadForm from "../../components/frontend/provider/DocUploadForm.vue";

const page = usePage()
const form = useForm()
const accountData = ref(null)
const myServices = ref([])
const user = computed(() => page.props.user)
const services = computed(() => page.props.services)
const bankAccounts = computed(() => page.props.bank_accounts)
const allServices = computed(() => page.props.all_services)
const documents = computed(() => page.props.documents)
const remainingServices = ref([])
const serviceSingleItem = ref(null)
const docSingleItem = ref(null)
const docFileSelected = ref(false)
const providerServices = ref([])
const modalIsShown = ref(false)

const scrollY = ref(0);
const isFixed = ref(false);

watch(
    () => services.value, (newValue) => {
        myServices.value = newValue.map(s => s.service.name)
    }
)

onMounted(() => {
    window.addEventListener('scroll', handleScroll);

    const myServiceIds = services.value.map(s => s.service.id)

    $("[data-fancybox]").fancybox({
        touch: false,
        hideOnOverlayClick: false,
        afterClose: function (instance, current, e) {
            if (['#upload-document', '#add-service', '#add-account', '#edit-profile'].includes(current.src)) {
                docFileSelected.value = false
                modalIsShown.value = true

                setTimeout(() => {
                    modalIsShown.value = false
                }, 1000);
            }
        }
    })

    $('.provider-service-slider').slick({
        dots: true,
        infinite: true,
        speed: 300,
        slidesToShow: 2,
        slidesToScroll: 1,
        arrows: false,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                }
            }
        ]
    });

    myServices.value = services.value.map(s => s.service.name)

    providerServices.value = services.value.map(s => {
        return {
            id: s.service.id,
            name: s.service.name
        }
    })
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});

const hideModal = (param = null) => {
    $.fancybox.close()
    
    if (param && param.isBank) {
        router.reload({ only: ['bank_accounts'] })
    }
}

const showModal = (data) => {
    accountData.value = data
    $.fancybox.open({
        src: '#add-account',
        type: 'inline',
        opts: {
            touch: false,
            hideOnOverlayClick: false,
            afterClose: function (instance, current) {
                accountData.value = null
            }
        }
    })
}

function openServiceEditModal(param) {
    serviceSingleItem.value = param
    $.fancybox.open({
        src: '#add-service',
        type: 'inline',
        opts: {
            touch: false,
            hideOnOverlayClick: false,
            afterClose: function (instance, current) {
                modalIsShown.value = true
                serviceSingleItem.value = null

                setTimeout(() => {
                    modalIsShown.value = false
                }, 1000);
            }
        }
    })
}

function handleScroll() {
    scrollY.value = window.scrollY;
    isFixed.value = scrollY.value > 0;
}

function removeItem(param) {
    form.delete(`/service-details/${param}`)
}

function setDocFile() {
    docFileSelected.value = true    
}

function getDocSingle(param) {
    docSingleItem.value = param
    $.fancybox.open({
        src: '#upload-document',
        type: 'inline',
        opts: {
            touch: false,
            hideOnOverlayClick: false,
            afterClose: function (instance, current) {
                modalIsShown.value = true
                docSingleItem.value = null

                setTimeout(() => {
                    modalIsShown.value = false
                }, 1000);
            }
        }
    })
}
</script>

<template>
    <div class="dashboard-sec">
        <div class="dashboard-container">
            <div class="dashboard-head" :class="{'fixed': isFixed}">
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
                                                                <p
                                                                    v-text="(user.profile) ? user.profile.date_of_birth : ''">
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="profile-info-wrap">
                                                            <figure>
                                                                <img src="/public/frontend_assets/images/briefcase.svg" alt="briefcase">
                                                            </figure>
                                                            <div class="profile-info-cont">
                                                                <h4>Services ({{ myServices.length }})</h4>
                                                                <div class="tag-list">
                                                                    <template v-for="(service, index) in myServices" :key="index">
                                                                        <span v-text="service"></span>
                                                                    </template>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 profile-col">
                                    <MyFiles 
                                        :docs="documents"
                                        v-on:doc-selected="getDocSingle"
                                    />
                                </div>
                                <BankDetails
                                    :bankAccounts="bankAccounts"
                                    :hasStripeId="user.stripe_id ? true : false"
                                    v-on:account-data="showModal"
                                />
                                <!-- Provider Services -->
                                <MyServices
                                    :services="services"
                                    v-on:delete-item="removeItem"
                                    v-on:edit-item-selected="openServiceEditModal"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Change Password Modal -->
                <FancyBoxModal
                    heading="Change Password"
                    id="change-passpord"
                    caption="Some caption text for development."
                >
                    <ChangePasswordForm v-on:password-update-success="hideModal" />
                </FancyBoxModal>

                <!-- Document Upload Modal -->
                <FancyBoxModal
                    heading="Upload new document"
                    id="upload-document"
                    caption="Lorem ipsum dolor sit amet"
                >
                    <DocUploadForm
                        :isShown="modalIsShown"
                        :fileSelected="docFileSelected"
                        :services="providerServices"
                        :docItem="docSingleItem"
                        v-on:doc-selected="setDocFile"
                        v-on:doc-saved-success="hideModal"
                    />
                </FancyBoxModal>

                <!-- Add Bank Account -->
                <FancyBoxModal heading="Manage Bank Account" id="add-account" caption="Enter your bank details here.">
                    <BankAccountForm
                        :user="user"
                        :isShown="modalIsShown"
                        v-on:bank-details-saved="hideModal"
                        :accountData="accountData"
                    />
                </FancyBoxModal>

                <!-- Profile Edit Modal -->
                <FancyBoxModal heading="Edit Profile" id="edit-profile" caption="Some caption text for development.">
                    <ProfileUpdateForm
                        :user="user"
                        :url="$page.url"
                        :fileSelected="docFileSelected"
                        v-on:profile-update-success="hideModal"
                        v-on:profile-pic-selected="docFileSelected = true"
                    />
                </FancyBoxModal>

                <FancyBoxModal
                    :heading="!serviceSingleItem ? `Add new service` : `Update Service`"
                    id="add-service"
                    caption="Create a new service item."
                >
                    <NewServiceItem
                        :isShown="modalIsShown"
                        :fileSelected="docFileSelected"
                        :services="allServices"
                        :serviceItem="serviceSingleItem"
                        v-on:data-saved="hideModal"
                        v-on:image-selected="setDocFile"
                    />
                </FancyBoxModal>
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