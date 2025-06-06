<script setup>
import { computed, watch, ref, onMounted } from 'vue'
import { usePage, useForm } from '@inertiajs/vue3'
import pluralize from 'pluralize'
import Select from 'primevue/select'
import MultiSelect from 'primevue/multiselect'
import Onboarding from '../../../../components/frontend/provider/Onboard.vue'

const page = usePage()

const form = useForm({
    category: null,
    services: null,
    experience: null
})

const categories = computed(() => page.props.categories)

const experience = computed(() => {
    return Array(10).fill(1).map((x, y) => {
        return {
            key: x + y,
            val: `${x + y} ${pluralize('year', x + y)}`
        }
    })
})

const personalData = computed(() => page.props.personal_data)

const categoryServices = ref([])

onMounted(() => {
    if (personalData.value) {
        form.category = formatCategoryIds(personalData.value.category)
        form.experience = personalData.value.experience
        form.services = personalData.value.services
    }
})

watch(
    () => form.category, async (value, oldValue) => {
        if (personalData.value) {
            if (oldValue && oldValue.length > value.length) {
                let intersection = oldValue.filter(element => !value.includes(element))[0]
                let category = categoryServices.value.find(i => i.category_id == intersection)
                let serviceIds = category.items.map(i => i.id)
                form.services = form.services.filter(s => !serviceIds.includes(s))
            }
        }
        
        let apiUrl = fullApiUrl(value),
        response = await fetch(apiUrl),
        result = await response.json()
        categoryServices.value = result.data
    }
)

const fullApiUrl = (param) => {
    const baseUrl = `${window.location.origin}/onboard/provider/service-category`;
    const url = new URL(baseUrl)

    if (!Array.isArray(param) && typeof param == 'object') {
        param = Object.keys(param).map(k => param[k])
    }

    param.forEach((item, index) => {
        url.searchParams.append(`category_id[${index}]`, item);
    })

    return url.toString();
}

const formatCategoryIds = (params) => {
    return Object.keys(params).map(item => params[item])
}
</script>

<template>
    <Onboarding :currentUrl="$page.props.current_url">
        <div class="login-form">
            <div class="login-head">
                <h1 v-text="`Personal Information`"></h1>
                <p v-text="`Lorem ipsum dolor sit amet consectetur adipisicing elit.`"></p>
            </div>
            <form @submit.prevent="form.post(`/onboard/provider/personal-info`, { replace: true })">
                <div class="form-input">
                    <label>Expertise</label>
                    <MultiSelect v-model="form.category" display="chip" :options="categories" optionValue="id"
                        optionLabel="name" filter placeholder="Select Expertise" class="w-100" />
                    <small class="text-danger" v-if="form.errors.category" v-text="form.errors.category"></small>
                </div>

                <div class="form-input">
                    <label>Experience</label>
                    <Select :options="experience" v-model="form.experience" optionValue="key" optionLabel="val"
                        placeholder="Select Experience" class="w-100" />
                    <small class="text-danger" v-if="form.errors.experience" v-text="form.errors.experience"></small>
                </div>

                <div class="form-input">
                    <label>Available services</label>
                    <MultiSelect
                        display="chip"
                        v-model="form.services"
                        :options="categoryServices"
                        optionValue="id"
                        optionLabel="name"
                        optionGroupLabel="category_name"
                        optionGroupChildren="items"
                        filter
                        placeholder="Available services"
                        class="w-100"
                    >
                    <template #optiongroup="slotProps">
                        <p>{{ slotProps.option.category_name }}</p>
                    </template>
                    </MultiSelect>
                    <small class="text-danger" v-if="form.errors.services" v-html="form.errors.services"></small>
                </div>

                <div class="form-input">
                    <button type="submit" :disabled="form.processing">Next</button>
                </div>
            </form>
        </div>
    </Onboarding>
</template>