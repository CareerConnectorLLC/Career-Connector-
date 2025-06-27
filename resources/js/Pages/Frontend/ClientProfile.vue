<script setup>
import { computed, onMounted, ref } from "vue";
import { router, usePage, useForm } from "@inertiajs/vue3";
import ProfileDropdown from "../../components/frontend/customer/ProfileDropdown.vue";
import FancyBoxModal from "../../components/frontend/FancyBoxModal.vue";
import ChangePasswordForm from "../../components/frontend/ChangePasswordForm.vue";
import ProfileUpdateForm from "../../components/frontend/ProfileUpdateForm.vue";
import ManageCardForm from "../../components/frontend/ManageCardForm.vue";
import CustomerSidebar from "../../components/frontend/customer/LeftSidebar.vue";
import CardInfo from "../../components/frontend/customer/CardInfo.vue";

const stripe = ref(null)
const cardError = ref(null)

const page = usePage()

const form = useForm({
    paymentMethodId: null,
})

const user = computed(() => page.props.user)
const cards = computed(() => page.props.savedCards)
const clientSecret = computed(() => page.props.client_secret)

onMounted(() => {
    stripe.value = Stripe(page.props.stripe_key)
    const elements = stripe.value.elements();
    const cardElement = elements.create('card');
    const cardForm = document.querySelector('#card-form')
    cardElement.mount('#card-element');

    const urlParams = new URLSearchParams(window.location.search);
    const fromParam = urlParams.get('from');
    
    cardForm.addEventListener('submit', async (e) => {
        e.preventDefault()
        const { paymentMethod, error } = await stripe.value.createPaymentMethod({
            type: 'card',
            card: cardElement,
            billing_details: {
                name: user.value.name,
                email: user.value.email,
                phone: user.value.phone,
            }
        })

        if (error) {
            cardError.value = error
        } else {
            form.paymentMethodId = paymentMethod.id           
            form.post(`/save-card`, {
                onSuccess: page => {
                    $.fancybox.close()
                    if (fromParam) {
                        router.visit(fromParam)
                    }
                }
            })
        }
    })    

    $("[data-fancybox]").fancybox({
        touch: false,
        hideOnOverlayClick: false,
        afterClose: function () {
            if (document.querySelector('.errorMsg').innerText != '') {
                document.querySelector('.errorMsg').innerText = ''
            }

            if (cardError.value) {
                cardError.value = null
            }

            router.reload()
        }
    })
})

const hideModal = () => {
    $.fancybox.close()
}

const deleteCard = (param) => {
    router.delete(`/remove-card/${param}`, {
        onSuccess: page => {
            console.log(`Deleted successfully`)
        }
    })
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
                            <CardInfo :cards="cards" v-on:remove-card="deleteCard" />
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

            <!-- Card Info Modal -->
            <FancyBoxModal heading="Manage Card" id="manage-card" caption="Add a new credit card.">
                <ManageCardForm :user="user" id="card-element" :cardError="cardError"/>
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