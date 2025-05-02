<template>
    <section class="provider-sec">
        <div class="container">
            <div class="provider-row">
                <div class="provider-left">
                    <div class="provider-left-inner">
                        <div class="provider-card-item">
                            <div class="provider-card active">
                                <h2>Filter by category</h2>

                                <ul style="">
                                    <li>
                                        <label>
                                            <input type="checkbox" checked="" />
                                            <span>All categories</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="checkbox" />
                                            <span>Plumbing</span>
                                        </label>
                                    </li>
                                    <div v-for="category of serviceStore.categories" :key="category.key"
                                        class="d-flex gap-2 pb-2">
                                        <Checkbox v-model="categoryFilter.value" :inputId="category.key" name="category"
                                            :value="category.name" />
                                        <label :for="category.key">{{ category.name }}</label>
                                    </div>
                                </ul>

                                <a class="showmore" href="#url">Show less</a>
                            </div>
                        </div>
                        <div class="provider-card-item">
                            <div class="provider-card">
                                <h2>Filter by location</h2>

                                <ul style="display: none">
                                    <li>
                                        <label>
                                            <input type="checkbox" checked="" />
                                            <span>All location</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="checkbox" />
                                            <span>Buffalo</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="checkbox" />
                                            <span>Rochester</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="checkbox" />
                                            <span>Syracuse</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="checkbox" />
                                            <span>Albany</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="checkbox" />
                                            <span>New Rochelle</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="checkbox" />
                                            <span>Mount Vernon</span>
                                        </label>
                                    </li>
                                    <li>
                                        <label>
                                            <input type="checkbox" />
                                            <span>Albany</span>
                                        </label>
                                    </li>
                                </ul>

                                <a class="showmore" href="#url">Show more</a>
                            </div>
                        </div>
                        <div class="provider-card-item">
                            <div class="provider-card">
                                <h2>Filter by price</h2>

                                <div class="pricing">
                                    <div class="pricing-col">
                                        <label>Min</label>
                                        <input type="number" placeholder="00" />
                                    </div>

                                    <div class="pricing-col">
                                        <p>To</p>
                                    </div>

                                    <div class="pricing-col">
                                        <label>Max</label>
                                        <input type="number" placeholder="00" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="provider-card-item">
                            <button class="btn btn-primary" @click="submit()">
                                Apply
                            </button>

                            <div class="d-flex gap-2 mt-2 mb-4">
                                <div v-for="providers in store.list" class="card">
                                    <div class="card-head p-2">
                                        <div class="card-profile">
                                            <div class="card-profile-details">
                                                <h6>
                                                    {{ providers.full_name }}
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { onMounted, nextTick, ref, reactive } from "vue";
import { useServiceStore } from "@/Stores/ServiceStore";
import { useServiceProviderStore } from "@/Stores/ServiceProviderStore";
import { Column } from "primevue";
import { values } from "lodash";

import Checkbox from "primevue/checkbox";

const store = useServiceProviderStore();
const serviceStore = useServiceStore();

const seletedCategories = ref([]);

const categoryFilter = reactive({
    column: "category",
    value: [],
});

onMounted(() => {
    nextTick(() => {
        emit.emit("pageName", "Service Provider Management", [
            { title: "Service Providers", routeName: "" },
        ]);
    });
    store.getServiceProviders();
    serviceStore.getCategories();
});

function submit() {
    const index = checkIfIndexExists(store.filters);
    if (index == -1 || !index) {
        store.filters.push(categoryFilter);
    } else {
        store.filters[index].value = categoryFilter.value;
    }
    
    store.getServiceProviders();
}

function checkIfIndexExists(array){
    return array.findIndex((obj) => obj.column === 'category');
}
</script>
