<template>
    <!-- Billing Address -->
    <div class="card card-action mb-4">
        <div class="card-header align-items-center">
            <h5 class="card-action-title mb-0">Inquiry Details</h5>
            <div class="card-action-element">
                <button v-if="!props?.inquiry?.reply && store.replyModal == false"
                    @click="store.replyModal = !store.replyModal" class="custom-button" type="button"
                    data-bs-toggle="modal" data-bs-target="#modalCenter">
                    <Icon class="me-1" icon="ri:reply-line" width="22" height="22" />
                    <span>Reply</span>
                </button>
            </div>
        </div>
        <div class="card-body">
            <ul class="timeline mt-3 mb-0">
                <li class="timeline-item timeline-item-primary pb-4 border-left-dashed">
                    <span class="timeline-indicator timeline-indicator-primary">
                        <i class="ti ti-send"></i>
                    </span>
                    <div class="timeline-event">
                        <div class="timeline-header border-bottom mb-3">
                            <div></div>
                            <span class="text-muted">On {{dayjs(props?.inquiry?.created_at).format('MMM D, YYYY')}}</span>
                        </div>
                        <div class="row">
                            <div class="col-xl-7 col-12">
                                <dl class="row mb-0">
                                    <dt class="col-sm-4 mb-3 fw-semibold text-nowrap">Name:</dt>
                                    <dd class="col-sm-8">{{ props?.inquiry?.name }}</dd>

                                    <dt class="col-sm-4 mb-3 fw-semibold text-nowrap">Email:</dt>
                                    <dd class="col-sm-8">{{ props?.inquiry?.email }}</dd>

                                    <dt class="col-sm-4 mb-3 fw-semibold text-nowrap">Message:</dt>
                                    <dd class="col-sm-8">{{ props?.inquiry?.message }}</dd>
                                </dl>
                            </div>
                            <div class="col-xl-5 col-12">
                                <dl class="row mb-0">
                                    <dt class="col-sm-4 mb-3 fw-semibold text-nowrap">Contact:</dt>
                                    <dd class="col-sm-8">{{ props?.inquiry?.phone }}</dd>

                                    <dt class="col-sm-4 mb-3 fw-semibold text-nowrap">Status:</dt>
                                    <dd class="col-sm-8">
                                        <span class="badge cursor-pointer"
                                            :class="{ 'bg-label-warning': (props?.inquiry?.status === 'Pending'), 'bg-label-success': (props?.inquiry?.status === 'Replied') }">
                                            {{ props?.inquiry?.status }}
                                        </span>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </li>
                <li v-if="props?.inquiry?.reply" class="timeline-item timeline-item-success pb-4 border-0">
                    <span class="timeline-indicator timeline-indicator-success">
                        <i class="ti ti-brush"></i>
                    </span>
                    <div class="timeline-event">
                        <div class="timeline-header border-bottom mb-3">
                            <h6 class="mb-0">Replied</h6>
                            <span class="text-muted">On {{dayjs(props?.inquiry?.reply?.created_at).format('MMM D, YYYY')}}</span>
                        </div>
                        <div>
                            <p class="card-text">{{ props?.inquiry?.reply?.reply }}</p>
                        </div>
                    </div>
                </li>
            </ul>
                <!-- Modal -->
                <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
                    data-bs-keyboard="false">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalCenterTitle">Reply</h5>
                                <button @click="store.cancelSubmit()" type="button" class="btn-close"
                                    data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div>
                                    <label for="exampleFormControlTextarea1" class="form-label fs-6">Type Your
                                        Reply Below</label>
                                    <textarea v-model="store.form.reply" class="form-control"
                                        id="exampleFormControlTextarea1" rows="5"></textarea>
                                    <span class="text-danger" v-if="store.form.errors?.reply">{{
                                        store.form.errors?.reply[0] }}</span>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button @click="store.cancelSubmit()" type="button" class="btn btn-label-secondary"
                                    data-bs-dismiss="modal">
                                    Close
                                </button>
                                <button @click="submit()" type="button" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <!--/ Billing Address -->
</template>

<script setup>
import { onMounted, reactive, ref, nextTick } from 'vue'
import { useInquiryStore } from '@/Stores/InquiryStore';
import dayjs from "dayjs";

const store = useInquiryStore();
const props = defineProps({
    inquiry: Object,
});


onMounted(() => {
    nextTick(() => {
        const pageName = "Inquiry Management";
        const breadcrumbs = [
            { title: 'Inquiries', routeName: "admin.inquiry.list" },
            {
                title: "Inquiry Details",
            }
        ];
        emit.emit('pageName', pageName, breadcrumbs);
    });
});

function submit() {
    store.form.Inquiry_id = props?.inquiry?.id;
    store.submitReply(props?.inquiry?.id);
    store.setFormData();
}

</script>