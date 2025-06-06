<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { FormatMoney } from 'format-money-js'

const fm = new FormatMoney({
    decimals: 0,
    symbol: '$'
});

const page = usePage()

const provider = computed(() => page.props.provider)

const timings = computed(() => {
    const originalAvailability = JSON.parse(page.props.timings)
    const dayOrder = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

    const formattedAvailabilityArray = dayOrder.map(day => {
        const times = originalAvailability.hasOwnProperty(day) ? originalAvailability[day] : null;
        let timeRange = null;

        if (Array.isArray(times) && times.length > 0) {
            const startTime = times[0];
            const endTime = times[times.length - 1];
            timeRange = `${startTime} - ${endTime}`;
        } else if (times === null) {
            timeRange = "No Availability";
        } else if (Array.isArray(times) && times.length === 0) {
            timeRange = "No Availability";
        }

        return {
            day: day,
            timeRange: timeRange
        }
    })

    return formattedAvailabilityArray
})

const workingDaysCount = computed(() => timings.value.filter(t => t.timeRange != 'No Availability').length)
</script>

<template>
    <section class="banner-sec inner-banner">
        <div class="container-fluid">
            <div class="banner-inner">
                <div class="banner-inner-cont-wrap">
                    <h1>Find service provider</h1>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                        labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.
                    </p>
                    <div class="banner-image-1">
                        <img src="/public/frontend_assets/images/banner-image01.png" alt="banner-image-1">
                    </div>
                    <div class="banner-image-2">
                        <img src="/public/frontend_assets/images/banner-image02.png" alt="banner-image-2">
                    </div>
                </div>
                <img class="top-shape" src="/public/frontend_assets/images/banner-top-shape.png" alt="banner-top-shape">
                <img class="bottom-shape" src="/public/frontend_assets/images/inner-banner-right-shape.svg"
                    alt="banner-bottom-shape">
                <img class="left-blur-shape" src="/public/frontend_assets/images/banner-left-blur-shape.png"
                    alt="banner-left-blur-shape">
                <img class="right-blur-shape" src="/public/frontend_assets/images/banner-top-blur-shape.png"
                    alt="banner-top-blur-shape">
            </div>
        </div>
    </section>

    <section class="provider-details-sec">
        <div class="container">
            <div class="provider-inner">
                <div class="provider-row row">
                    <div class="col-lg-7 provider-details-col-left">
                        <div class="provider-details-left-cont">
                            <div class="provider-details-head">
                                <div class="card-profile">
                                    <div class="card-profile-img-wrap">
                                        <img src="/public/frontend_assets/images/profile-image-01.png"
                                            alt="profile-image-01">
                                    </div>
                                    <div class="card-profile-details">
                                        <h2 v-text="provider.provider.name"></h2>
                                        <div class="ratings d-none">
                                            <ul>
                                                <li>
                                                    <figure>
                                                        <img src="/public/frontend_assets/images/profile-star.svg"
                                                            alt="profile-star">
                                                    </figure>
                                                </li>
                                                <li>
                                                    <figure>
                                                        <img src="/public/frontend_assets/images/profile-star.svg"
                                                            alt="profile-star">
                                                    </figure>
                                                </li>
                                                <li>
                                                    <figure>
                                                        <img src="/public/frontend_assets/images/profile-star.svg"
                                                            alt="profile-star">
                                                    </figure>
                                                </li>
                                                <li>
                                                    <figure>
                                                        <img src="/public/frontend_assets/images/profile-star.svg"
                                                            alt="profile-star">
                                                    </figure>
                                                </li>
                                                <li>
                                                    <figure>
                                                        <img src="/public/frontend_assets/images/profile-star.svg"
                                                            alt="profile-star">
                                                    </figure>
                                                </li>
                                            </ul>
                                            <p>4.9</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="provider-details-tags">
                                    <div class="rated d-none">
                                        <figure><img src="/public/frontend_assets/images/profile-star.svg"
                                                alt="profile-star"></figure>
                                        <span>Top rated</span>
                                    </div>
                                    <div class="card-tags">
                                        <span class="tag">{{ provider.service.name }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="provider-details-content">
                                <h3 v-text="provider.service.name"></h3>
                                <p v-text="provider.description"></p>
                            </div>

                            <div class="about-service-provider d-none">
                                <h4>About the service provider</h4>
                                <div class="service-provider-card">
                                    <div class="blur green"></div>
                                    <img class="card-shape"
                                        src="/public/frontend_assets/images/service-provider-card-shape.svg"
                                        alt="service-provider-card-shape">
                                    <div class="card-profile">
                                        <div class="card-profile-img-wrap">
                                            <img src="/public/frontend_assets/images/about-service-provider-card.png"
                                                alt="service-provider">
                                        </div>
                                        <div class="card-profile-details">
                                            <h5>Davis D’suza</h5>
                                            <div class="provider-details-tags">
                                                <div class="rated">
                                                    <figure><img src="/public/frontend_assets/images/profile-star.svg"
                                                            alt="profile-star">
                                                    </figure>
                                                    <span>Top rated</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="address">
                                        <ul>
                                            <li>2972 Westheimer Rd</li>
                                            <li>1 contract in progress</li>
                                        </ul>
                                    </div>
                                    <p>
                                        Pellentesque interdum felis quis dui euismod, dignissim fermentum elit
                                        elementum. Mauris malesuada eu mauris vel feugiat. Mauris vel fermentum purus.
                                        Aliquam vitae nibh pulvinar dui faucibus tempus. Pellentesque habitant morbi
                                        tristique senectus et netus et malesuada fames ac turpis egestas.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5  provider-details-col-right">
                        <div class="provider-details-col-inner">
                            <div class="provider-pricing-card">
                                <div class="provider-pricing-card-head">
                                    <h4>{{ provider.service.name }} Price</h4>
                                    <p class="pricing">{{ fm.from(parseInt(provider.price / 100)) }}</p>
                                </div>

                                <div class="address">
                                    <h4>Address</h4>
                                    <div class="address-wrap">
                                        <figure>
                                            <img src="/public/frontend_assets/images/location-green.svg"
                                                alt="location-green">
                                        </figure>
                                        <p v-text="provider.location"></p>
                                    </div>
                                </div>

                                <div class="duration">
                                    <div class="duration-head">
                                        <h4>Job duration</h4>
                                        <p>{{ workingDaysCount }} working days</p>
                                    </div>
                                    <template v-if="timings.length">
                                        <ul>
                                            <li v-for="(timing, index) in timings" :key="index">
                                                <span class="date">{{ timing.day }}</span>
                                                <span :class="{'close': timing.timeRange == 'No Availability', 'timing': timing.timeRange != 'No Availability'}">{{ timing.timeRange }}</span>
                                            </li>
                                        </ul>
                                    </template>
                                    <a class="book-now" href="" @click.prevent="">Book now</a>
                                </div>
                            </div>

                            <div class="provider-pricing-card d-none">
                                <div class="provide-review-sec">
                                    <div class="review-head">
                                        <h4>Reviews</h4>
                                        <div class="star-ratings">
                                            <ul>
                                                <li>
                                                    <span>
                                                        <img src="/public/frontend_assets/images/profile-star.svg"
                                                            alt="profile-star">
                                                    </span>
                                                </li>
                                                <li>
                                                    <span>
                                                        <img src="/public/frontend_assets/images/profile-star.svg"
                                                            alt="profile-star">
                                                    </span>
                                                </li>
                                                <li>
                                                    <span>
                                                        <img src="/public/frontend_assets/images/profile-star.svg"
                                                            alt="profile-star">
                                                    </span>
                                                </li>
                                                <li>
                                                    <span>
                                                        <img src="/public/frontend_assets/images/profile-star.svg"
                                                            alt="profile-star">
                                                    </span>
                                                </li>
                                                <li>
                                                    <span>
                                                        <img src="/public/frontend_assets/images/profile-star.svg"
                                                            alt="profile-star">
                                                    </span>
                                                </li>
                                            </ul>

                                            <p><strong>4.9</strong></p>

                                            <p>(764 reviews)</p>

                                        </div>
                                    </div>

                                    <div class="reatngs-wrap">
                                        <div class="star-ratings-sec">
                                            <ul>
                                                <li>
                                                    <p><strong>5 stars</strong></p>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: 90%"
                                                            aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <p>
                                                        (688)
                                                    </p>
                                                </li>
                                                <li>
                                                    <p><strong>4 stars</strong></p>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: 80%"
                                                            aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <p>
                                                        (68)
                                                    </p>
                                                </li>
                                                <li>
                                                    <p><strong>3 stars</strong></p>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: 20%"
                                                            aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <p>
                                                        (6)
                                                    </p>
                                                </li>
                                                <li>
                                                    <p><strong>2 stars</strong></p>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: 5%"
                                                            aria-valuenow="5" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <p>
                                                        (1)
                                                    </p>
                                                </li>
                                                <li>
                                                    <p><strong>1 stars</strong></p>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: 5%"
                                                            aria-valuenow="5" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <p>
                                                        (1)
                                                    </p>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="ratings-cat">
                                            <ul>
                                                <li>
                                                    <p><strong>Availability</strong></p>
                                                    <p>5 rating</p>
                                                </li>
                                                <li>
                                                    <p><strong>Skills</strong></p>
                                                    <p>5 rating</p>
                                                </li>
                                                <li>
                                                    <p><strong>Quality</strong></p>
                                                    <p>5 rating</p>
                                                </li>
                                                <li>
                                                    <p><strong>Corporation</strong></p>
                                                    <p>5 rating</p>
                                                </li>
                                                <li>
                                                    <p><strong>Communication</strong></p>
                                                    <p>5 rating</p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="review-items">
                                    <div class="review-cont">
                                        <div class="review-head">
                                            <figure><img src="/public/frontend_assets/images/quote.svg" alt="quote">
                                            </figure>
                                            <p class="date">23.07.2023</p>
                                        </div>

                                        <div class="review-body">
                                            <p><strong>Donec ex risus, iaculis id turpis a, auctor lobortis arcu.
                                                </strong></p>
                                            <p>
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
                                                veniam, quis nostrud.
                                            </p>
                                        </div>

                                        <div class="card-profile">
                                            <div class="card-profile-img-wrap">
                                                <img src="/public/frontend_assets/images/rosy.png" alt="rosy">
                                            </div>
                                            <div class="card-profile-details">
                                                <h3>Rossy D’suza</h3>
                                                <div class="ratings">
                                                    <ul>
                                                        <li>
                                                            <figure><img
                                                                    src="/public/frontend_assets/images/profile-star.svg"
                                                                    alt="profile-star">
                                                            </figure>
                                                        </li>
                                                        <li>
                                                            <figure><img
                                                                    src="/public/frontend_assets/images/profile-star.svg"
                                                                    alt="profile-star">
                                                            </figure>
                                                        </li>
                                                        <li>
                                                            <figure><img
                                                                    src="/public/frontend_assets/images/profile-star.svg"
                                                                    alt="profile-star">
                                                            </figure>
                                                        </li>
                                                        <li>
                                                            <figure><img
                                                                    src="/public/frontend_assets/images/profile-star.svg"
                                                                    alt="profile-star">
                                                            </figure>
                                                        </li>
                                                        <li>
                                                            <figure>
                                                                <img
                                                                    src="/public/frontend_assets/images/profile-star.svg"
                                                                    alt="profile-star">
                                                            </figure>
                                                        </li>
                                                    </ul>

                                                    <p>5.0</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="review-cont">
                                        <div class="review-head">
                                            <figure><img src="/public/frontend_assets/images/quote.svg" alt="quote">
                                            </figure>
                                            <p class="date">23.07.2023</p>
                                        </div>

                                        <div class="review-body">
                                            <p><strong>Donec ex risus, iaculis id turpis a, auctor lobortis arcu.
                                                </strong></p>
                                            <p>
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
                                                veniam, quis nostrud.
                                            </p>
                                        </div>

                                        <div class="card-profile">
                                            <div class="card-profile-img-wrap">
                                                <img src="/public/frontend_assets/images/rosy.png" alt="rosy">
                                            </div>
                                            <div class="card-profile-details">
                                                <h3>Rossy D’suza</h3>
                                                <div class="ratings">
                                                    <ul>
                                                        <li>
                                                            <figure><img
                                                                    src="/public/frontend_assets/images/profile-star.svg"
                                                                    alt="profile-star"></figure>
                                                        </li>
                                                        <li>
                                                            <figure><img
                                                                    src="/public/frontend_assets/images/profile-star.svg"
                                                                    alt="profile-star"></figure>
                                                        </li>
                                                        <li>
                                                            <figure><img
                                                                    src="/public/frontend_assets/images/profile-star.svg"
                                                                    alt="profile-star"></figure>
                                                        </li>
                                                        <li>
                                                            <figure><img
                                                                    src="/public/frontend_assets/images/profile-star.svg"
                                                                    alt="profile-star"></figure>
                                                        </li>
                                                        <li>
                                                            <figure><img
                                                                    src="/public/frontend_assets/images/profile-star.svg"
                                                                    alt="profile-star"></figure>
                                                        </li>
                                                    </ul>

                                                    <p>5.0</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="review-cont">
                                        <div class="review-head">
                                            <figure><img src="/public/frontend_assets/images/quote.svg" alt="quote">
                                            </figure>
                                            <p class="date">23.07.2023</p>
                                        </div>

                                        <div class="review-body">
                                            <p><strong>Donec ex risus, iaculis id turpis a, auctor lobortis arcu.
                                                </strong></p>
                                            <p>
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
                                                veniam, quis nostrud.
                                            </p>
                                        </div>

                                        <div class="card-profile">
                                            <div class="card-profile-img-wrap">
                                                <img src="/public/frontend_assets/images/rosy.png" alt="rosy">
                                            </div>
                                            <div class="card-profile-details">
                                                <h3>Rossy D’suza</h3>
                                                <div class="ratings">
                                                    <ul>
                                                        <li>
                                                            <figure><img
                                                                    src="/public/frontend_assets/images/profile-star.svg"
                                                                    alt="profile-star"></figure>
                                                        </li>
                                                        <li>
                                                            <figure><img
                                                                    src="/public/frontend_assets/images/profile-star.svg"
                                                                    alt="profile-star"></figure>
                                                        </li>
                                                        <li>
                                                            <figure><img
                                                                    src="/public/frontend_assets/images/profile-star.svg"
                                                                    alt="profile-star"></figure>
                                                        </li>
                                                        <li>
                                                            <figure><img
                                                                    src="/public/frontend_assets/images/profile-star.svg"
                                                                    alt="profile-star"></figure>
                                                        </li>
                                                        <li>
                                                            <figure><img
                                                                    src="/public/frontend_assets/images/profile-star.svg"
                                                                    alt="profile-star"></figure>
                                                        </li>
                                                    </ul>

                                                    <p>5.0</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a class="booknow" href="review-page.html">View all</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="blur green"></div>
    </section>
</template>