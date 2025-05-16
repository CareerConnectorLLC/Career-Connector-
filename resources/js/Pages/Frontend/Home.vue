<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
    <section class="banner-sec">
        <div class="container-fluid">
            <div class="banner-inner">
                <div class="banner-inner-cont-wrap">
                    <span class="tag">Welcome to Career connector</span>
                    <h1>One stop solution to make your life super easy</h1>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                        labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.
                    </p>
                    <div class="banner-serach-wrap">
                        <form>
                            <div class="form-wrapper">
                                <div class="search-col">
                                    <input type="text" class="search" placeholder="Search for Service category">
                                </div>
                                <div class="search-col">
                                    <input type="text" placeholder="Location">
                                </div>
                                <div class="search-col">
                                    <select>
                                        <option>Price range</option>
                                        <option>$100</option>
                                        <option>$500</option>
                                        <option>$1000</option>
                                    </select>
                                </div>
                                <div class="search-col">
                                    <input type="submit" value="search">
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="banner-image-1">
                        <img src="/public/frontend_assets/images/banner-image01.png" alt="banner-image-1">
                    </div>

                    <div class="banner-image-2">
                        <img src="/public/frontend_assets/images/banner-image02.png" alt="banner-image-2">
                    </div>
                </div>

                <img class="top-shape" src="/public/frontend_assets/images/banner-top-shape.png" alt="banner-top-shape">
                <img class="bottom-shape" src="/public/frontend_assets/images/banner-bottom-shape.png"
                    alt="banner-bottom-shape">
                <div class="banner-image-3">
                    <img src="/public/frontend_assets/images/banner-image03.png" alt="banner-image-3">
                </div>

                <img class="left-blur-shape" src="/public/frontend_assets/images/banner-left-blur-shape.png"
                    alt="banner-left-blur-shape">
                <img class="right-blur-shape" src="/public/frontend_assets/images/banner-top-blur-shape.png"
                    alt="banner-top-blur-shape">

            </div>
        </div>
    </section>

    <section class="solution-sec">
        <div class="container">
            <div class="row solution-row">
                <div class="col-md-6 solution-col-left">
                    <div class="solution-left-cont">
                        <figure class="solution-img-1">
                            <img src="/public/frontend_assets/images/solution-image01.png" alt="solution-image">
                        </figure>

                        <figure class="solution-img-2">
                            <img src="/public/frontend_assets/images/solution-image02.png" alt="solution-image">
                        </figure>
                    </div>
                </div>
                <div class="col-md-6 solution-col-right">
                    <div class="solution-cont">
                        <h2>One stop solution to make your life super easy</h2>
                        <p>
                            Pellentesque interdum felis quis dui euismod, dignissim fermentum elit elementum. Mauris
                            malesuada eu mauris vel feugiat. Mauris vel fermentum purus. Aliquam vitae nibh pulvinar dui
                            faucibus tempus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames
                            ac turpis egestas.
                        </p>
                        <p>
                            Cras rhoncus risus id nisi posuere fermentum. Ut scelerisque lectus quam, sit amet vehicula
                            leo lacinia non. Ut molestie semper velit, in posuere massa. Aliquam augue sapien, ultrices
                            eget tempor in, rutrum non mi. Morbi ornare a sapien in semper. Donec pellentesque ut eros
                            at laoreet. Donec venenatis nisi eget felis auctor ornare. Maecenas blandit orci metus,
                            vitae viverra arcu scelerisque quis. In tristique elementum consectetur. Proin rutrum
                            pharetra ante, et sodales mi venenatis non. Donec at ullamcorper felis. Aenean ex tellus,
                            consequat vitae blandit quis, laoreet et tellus.
                        </p>
                        <a class="primary-btn" href="">Read more</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="sign-up-sec">
        <div class="container">
            <div class="sign-up-inner">
                <div class="row sign-up-row">
                    <div class="col-md-6 sign-up-col">
                        <div class="sign-up-card">
                            <div class="sign-up-card-image">
                                <img src="/public/frontend_assets/images/sign-up-image01.png" alt="sign-up-image">
                                <img class="signup-shape" src="/public/frontend_assets/images/service-image-shape.svg"
                                    alt="service-image-shape">

                                <Link class="signup-arrow" href="/register?user_role=USER">
                                    <img src="/public/frontend_assets/images/right-arrow.svg" alt="right-arrow">
                                </Link>

                            </div>
                            <div class="sign-up-cont">
                                <h2>Sign up as a customer</h2>
                                <p>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                                    incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 sign-up-col">
                        <div class="sign-up-card">
                            <div class="sign-up-card-image">
                                <img src="/public/frontend_assets/images/sign-up-image02.png" alt="sign-up-image">
                                <img class="signup-shape" src="/public/frontend_assets/images/service-image-shape.svg"
                                    alt="service-image-shape">
                                <Link class="signup-arrow" href="/register?user_role=SERVICE-PROVIDER">
                                    <img src="/public/frontend_assets/images/right-arrow.svg" alt="right-arrow">
                                </Link>
                            </div>
                            <div class="sign-up-cont">
                                <h2>Sign up as a service provider</h2>
                                <p>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                                    incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>