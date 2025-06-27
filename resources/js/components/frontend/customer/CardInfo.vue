<script setup>
import { computed, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3'

defineProps({
    cards: Object,
})

const page = usePage()
const emit = defineEmits(['remove-card'])
</script>

<template>
    <div class="my-profile-right">
        <div class="card-info">
            <div class="card-info-head">
                <h3>Card details</h3>
                <a data-fancybox="" data-src="#manage-card" class="primary-btn" href="">Add card</a>
            </div>

            <p v-if="!cards.length">You have no saved cards</p>
            
            <template v-else>
                <div class="online-card" v-for="(card, index) in cards" :key="index">
                    <div class="card-cont">
                        <h4>Card Number</h4>
                        <p>XXXX XXXX XXXX {{ card.last4 }}</p>
                    </div>

                    <div class="online-card-details">
                        <div class="card-cont">
                            <h4>Expiry date</h4>
                            <p>{{ card.exp_month }} / {{ card.exp_year }}</p>
                        </div>
                    </div>
                    <a class="delete-btn" href="" @click.prevent="emit('remove-card', card.id)">
                        <img src="/public/frontend_assets/images/trash.svg" alt="trash">
                    </a>
    
                    <figure class="card-type">
                        <img src="/public/frontend_assets/images/card-type.svg" alt="card-type">
                    </figure>
                </div>
            </template>
        </div>
    </div>
</template>