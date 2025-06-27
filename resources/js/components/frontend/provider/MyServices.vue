<script setup>
import { Link } from "@inertiajs/vue3";
import { FormatMoney } from 'format-money-js';
import { Dropdown } from 'bootstrap';

const emit = defineEmits(['delete-item', 'edit-item-selected']);

const fm = new FormatMoney({
    decimals: 0,
    symbol: '$'
});

const props = defineProps({
    services: Object
})

function removeItem(param) {
    emit('delete-item', param)
}

function editItem(param) {
    let item = props.services.find(s => s.id === param)
    emit('edit-item-selected', item)
}
</script>

<template>
    <div class="col-md-8 profile-col">
        <div class="my-profile-card">
            <div class="my-profile-head">
                <h2>My services ({{ services.length }})</h2>
                <a data-fancybox data-src="#add-service" class="primary-btn" href="#url">Add new</a>
            </div>

            <div class="provider-service-slider">
                <div class="provider-service-slide" v-for="item in services" :key="item.id">
                    <div class="provider-service-card">
                        <figure>
                            <template v-if="item.file_path != null">
                                <img :src="item.file_path" alt="provider-serive-image">
                            </template>
                            <template v-else>
                                <img src="/public/frontend_assets/images/provider-serive-image-01.png" alt="provider-serive-image">
                            </template>
                            <div class="dot-option">
                                <a class="notification-option-btn dropdown-toggle" href="#url" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <span><img src="/public/frontend_assets/images/dots.svg" alt="dots"></span>
                                </a>
                                <div class="dots-drop-dowm dropdown-menu">
                                    <ul>
                                        <li><a href="" @click.prevent="editItem(item.id)">Edit</a></li>
                                        <li><a href="" @click.prevent="removeItem(item.id)">Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                        </figure>
                        <div class="provider-service-cont">
                            <div class="my-profile-head">
                                <h4><Link :href="`/service-details/${item.id}`">{{ item.service.name }}</Link></h4>
                                <p class="price">{{ fm.from(parseInt(item.price/100)) }}</p>
                            </div>
                            <p>{{ item.description }}</p>
                            <Link class="booknow" :href="`/service-details/${item.id}`">Read more</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>