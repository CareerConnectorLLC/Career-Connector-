<script setup>
import { ref, onMounted, watch } from 'vue'

const props = defineProps({
    services: Object,
    serviceIds: Object,
    price: String
})

const emit = defineEmits(['option-selected'])
const selectedCategories = ref([])
const availableServices = ref([])
const showMore = ref(false)
const pricing = ref(0)

onMounted(() => {
    selectedCategories.value = props.serviceIds
    availableServices.value = props.services.slice(0, 7)
    pricing.value = parseInt(props.price/100)
})

watch(
    () => showMore.value, (newValue) => {
        if (newValue) {
            availableServices.value = props.services
        } else {
            availableServices.value = props.services.slice(0, 7)
        }
    }
)

const applyFilter = () => {
    let params = {}
    params['services'] = selectedCategories.value
    params['pricing'] = pricing.value * 100
    emit('option-selected', params)
}
</script>

<template>
    <div class="provider-left">
        <div class="provider-left-inner">
            <div class="provider-card-item">
                <div class="provider-card active">
                    <h2>Filter by service</h2>
                    <ul style="">
                        <li v-for="service in availableServices" :key="service.id">
                            <label>
                                <input type="checkbox" v-model="selectedCategories" v-bind:value="service.id">
                                <span>{{ service.name }}</span>
                            </label>
                        </li>
                    </ul>
                    <a class="showmore" href="" @click.prevent="showMore = !showMore">
                        Show {{ showMore ? `Less` : `More` }}
                    </a>
                </div>
            </div>
            <div class="provider-card-item">
                <div class="provider-card">
                    <h2>Filter by price</h2>
                    <p>Price: ${{ pricing }}</p>                  
                    <Slider v-model="pricing" class="w-100" />
                </div>
            </div>
            <div class="provider-card-item">
                <button type="button" @click="applyFilter">Apply</button>
            </div>
        </div>
        <button class="side-bar-toggler">
            <span class="stick"></span>
        </button>
    </div>
</template>

<style scoped>
button[type="button"] {
    background: var(--btnPrimary);
    color: var(--whiteColor);
    height: 52px;
    border: none;
    -webkit-appearance: none;
    appearance: none;
    -webkit-border-radius: 12px;
    -moz-border-radius: 12px;
    -ms-border-radius: 12px;
    border-radius: 12px;
    padding-left: 44px;
    padding-right: 44px;
    cursor: pointer;
    font-size: 24px;
    font-weight: 500;
    outline: none !important;
    -webkit-transition: all 0.3s ease-in-out;
    transition: all 0.3s ease-in-out;
    font-size: 16px;
    line-height: 1.1;
}
</style>