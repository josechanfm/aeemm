<template>
    <AdminLayout title="Dashboard" >
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                表格管理
            </h2>
        </template>
        <a-form
            ref="modalRef"
            :model="course"
            name="Teacher"
            layout="vertical"
            autocomplete="off"
            :rules="rules"
            :validate-messages="validateMessages"
        >
            <a-form-item label="標題(中文)" name="title_zh">
                <a-input v-model:value="course.title_zh" />
            </a-form-item>
            <a-form-item label="標題(英文)" name="title_en">
                <a-input v-model:value="course.title_en" />
            </a-form-item>
            <a-form-item label="簡介(中文)" name="description_zh">
                <a-textarea v-model:value="course.description_zh" />
            </a-form-item>
            <a-form-item label="標題(英文)" name="description_en">
                <a-textarea v-model:value="course.description_en" />
            </a-form-item>
            <a-form-item label="開始日期" name="start_date">
                <a-date-picker v-model:value="course.start_date" :format="dateFormat" :valueFormat="dateFormat" />
            </a-form-item>
            <a-form-item label="結束日期" name="end_date">
                <a-date-picker v-model:value="course.end_date" :format="dateFormat" :valueFormat="dateFormat" />
            </a-form-item>
            <a-form-item label="上課時間" name="class_time">
                <a-time-range-picker v-model:value="course.class_time" format="HH:mm" valueFormat="HH:mm" />
            </a-form-item>
            <a-form-item label="時數" name="hours">
                <a-input-number v-model:value="course.hours" :min="0"/>
            </a-form-item>
            <a-form-item label="學費(非會員)" name="fee">
                <a-input-number v-model:value="course.fee" :min="0"/>
            </a-form-item>
            <a-form-item label="學費(會員)" name="fee_member">
                <a-input-number v-model:value="course.fee_member" :min="0"/>
            </a-form-item>
            <a-form-item label="內容" name="content">
                <div class="bg-white">
                    <quill-editor v-model:value="course.content"  style="min-height: 300px;"/>
                </div>
            </a-form-item>
            <a-form-item label="語言" name="language">
                <a-input v-model:value="course.language" />
            </a-form-item>
            <a-form-item label="場所" name="location">
                <a-input v-model:value="course.location" />
            </a-form-item>
            <a-form-item label="對象" name="target">
                <a-input v-model:value="course.target" />
            </a-form-item>
            <a-form-item label="發佈" name="published">
                <a-switch v-model:checked="course.published" :unCheckedValue="0" :checkedValue="1"/>
            </a-form-item>
            <a-form-item label="導師" name="tutor">
                <div class="bg-white">
                    <quill-editor v-model:value="course.tutor" style="min-height: 300px;"/>
                </div>
            </a-form-item>
            <a-form-item label="單張" name="poster">
                <div v-if="course.media.length" >
                    <inertia-link :href="route('admin.form-delete-media',course.media[0].id)" class="float-right text-red-500">
                        <svg focusable="false" class="" data-icon="delete" width="1em" height="1em" fill="currentColor" aria-hidden="true" viewBox="64 64 896 896">
                            <path d="M360 184h-8c4.4 0 8-3.6 8-8v8h304v-8c0 4.4 3.6 8 8 8h-8v72h72v-80c0-35.3-28.7-64-64-64H352c-35.3 0-64 28.7-64 64v80h72v-72zm504 72H160c-17.7 0-32 14.3-32 32v32c0 4.4 3.6 8 8 8h60.4l24.7 523c1.6 34.1 29.8 61 63.9 61h454c34.2 0 62.3-26.8 63.9-61l24.7-523H888c4.4 0 8-3.6 8-8v-32c0-17.7-14.3-32-32-32zM731.3 840H292.7l-24.2-512h487l-24.2 512z"></path>
                        </svg>
                    </inertia-link>
                    
                    <img :src="course.media[0].preview_url"/>
                </div>
                <a-upload
                    v-model:file-list="course.poster"
                    :multiple="false"
                    :beforeUpload="()=>false"
                    :max-count="1"
                    list-type="picture"
                >
                    <a-button>
                        <upload-outlined></upload-outlined>
                        上載
                    </a-button>
                </a-upload>
            </a-form-item>            
            <a-button key="Store" type="primary" @click="updateRecord">更新</a-button>
        </a-form>
        <template #footer>
            
        </template>
    </AdminLayout>

</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { UploadOutlined } from '@ant-design/icons-vue';
import { quillEditor } from 'vue3-quill';
import Icon, { RestFilled } from '@ant-design/icons-vue';
import dayjs from 'dayjs';

export default {
    setup() {
        return {
        }        
    },
    components: {
        AdminLayout,
        UploadOutlined,
        RestFilled,
        quillEditor,
        dayjs,
    },
    props: ['course'],
    data() {
        return {
            dateFormat:'YYYY-MM-DD',
            quillEditorOption: {
                placeholder: 'core',
                modules: {
                // toolbar: [
                    // custom toolbars options
                    // will override the default configuration
                // ],
                // other moudle options here
                // otherMoudle: {}
                    // htmlEditButton: {
                    //     debug: false,
                    //     msg: "Edytuj zawartość przy pomocy HTML",
                    //     cancelText: "Anuluj",
                    //     buttonTitle: "Pokaż kod źródłowy HTML",
                    // },
                },
                // more options
            },
            rules:{
                field:{required:true},
                label:{required:true},
            },
            validateMessages:{
                required: '${label} is required!',
                types: {
                    email: '${label} is not a valid email!',
                    number: '${label} is not a valid number!',
                },
                number: {
                    range: '${label} must be between ${min} and ${max}',
                },
            },

        }
    },
    created(){
        if(!Array.isArray(this.course.class_time)){
            this.course.class_time=JSON.parse(this.course.class_time);
        }
    },
    mounted(){
    },
    computed:{
    }, 
    methods: {
        updateRecord(){
            console.log(this.course);
            this.$refs.modalRef.validateFields().then(()=>{
                this.course._method = 'PATCH';
                this.$inertia.post(route('admin.courses.update',this.course.id), this.course,{
                    onSuccess:(page)=>{
                        if(!Array.isArray(this.course.class_time)){
                            this.course.class_time=JSON.parse(this.course.class_time);
                        }

                        console.log(page);
                    },
                    onError:(error)=>{
                        console.log(error);
                    }
                });
            }).catch(err => {
                console.log("error", err);
            });
        },
    },
}
</script>
<style>
.ant-form-vertical .ant-form-item-label{
    padding-bottom:0px;
}
</style>