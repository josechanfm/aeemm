<template>
    <AdminLayout title="Dashboard" >
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                課程管理
            </h2>
        </template>
            <inertia-link :href="route('admin.courses.create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3" >新增課程</inertia-link>
            <p></p>
            <a-table :dataSource="courses" :columns="columns">
                <template #bodyCell="{column, text, record, index}">
                    <template v-if="column.dataIndex=='operation'">
                        <inertia-link :href="route('admin.courses.edit',record.id)" class="ant-btn">修改</inertia-link>
                        <inertia-link :href="route('admin.courses.destroy',record.id)" class="ant-btn">刪除</inertia-link>
                    </template>
                    <template v-else>
                        {{record[column.dataIndex]}}
                    </template>
                </template>
            </a-table>

        <!-- Modal Start-->
        <a-modal v-model:visible="modal.isOpen" :title="modal.mode=='CREATE'?'新增':'修改'" width="60%" >
        <a-form
            ref="modalRef"
            :model="modal.data"
            name="Teacher"
            :label-col="{ span: 6 }"
            :wrapper-col="{ span: 18 }"
            autocomplete="off"
            :rules="rules"
            :validate-messages="validateMessages"
        >
            <a-form-item label="Title Zh" name="title_zh">
                <a-input v-model:value="modal.data.title_zh" />
            </a-form-item>
            <a-form-item label="Title En" name="title_en">
                <a-input v-model:value="modal.data.title_en" />
            </a-form-item>
            <a-form-item label="Title" name="title">
                <a-input v-model:value="modal.data.title" />
            </a-form-item>
            <a-form-item label="Description" name="description">
                <a-input v-model:value="modal.data.description" />
            </a-form-item>
            <a-form-item label="Require Login" name="require_login">
                <a-switch v-model:checked="modal.data.require_login" :unCheckedValue="0" :checkedValue="1"/>
            </a-form-item>
            <a-form-item label="Require Member" name="require_member">
                <a-switch v-model:checked="modal.data.require_member" :unCheckedValue="0" :checkedValue="1"/>
            </a-form-item>
            <a-form-item label="Banner image" name="cert_logo">
                <div v-if="modal.data.media.length" >
                    <inertia-link :href="route('admin.form-delete-media',modal.data.media[0].id)" class="float-right text-red-500">
                        <svg focusable="false" class="" data-icon="delete" width="1em" height="1em" fill="currentColor" aria-hidden="true" viewBox="64 64 896 896">
                            <path d="M360 184h-8c4.4 0 8-3.6 8-8v8h304v-8c0 4.4 3.6 8 8 8h-8v72h72v-80c0-35.3-28.7-64-64-64H352c-35.3 0-64 28.7-64 64v80h72v-72zm504 72H160c-17.7 0-32 14.3-32 32v32c0 4.4 3.6 8 8 8h60.4l24.7 523c1.6 34.1 29.8 61 63.9 61h454c34.2 0 62.3-26.8 63.9-61l24.7-523H888c4.4 0 8-3.6 8-8v-32c0-17.7-14.3-32-32-32zM731.3 840H292.7l-24.2-512h487l-24.2 512z"></path>
                        </svg>
                    </inertia-link>
                    <img :src="'/media/'+modal.data.media[0].id+'/'+modal.data.media[0].file_name"/>
                </div>
                <a-upload
                    v-model:file-list="modal.data.image"
                    :multiple="false"
                    :beforeUpload="()=>false"
                    :max-count="1"
                    list-type="picture"
                >
                    <a-button>
                        <upload-outlined></upload-outlined>
                        upload
                    </a-button>
                </a-upload>
            </a-form-item>            
        </a-form>
        <template #footer>
            <a-button v-if="modal.mode=='EDIT'" key="Update" type="primary"  @click="updateRecord()">更新</a-button>
            <a-button v-if="modal.mode=='CREATE'"  key="Store" type="primary" @click="storeRecord()">新增</a-button>
        </template>
    </a-modal>    
    <!-- Modal End-->
    </AdminLayout>

</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { UploadOutlined } from '@ant-design/icons-vue';
import Icon, { RestFilled } from '@ant-design/icons-vue';

export default {
    components: {
        AdminLayout,
        UploadOutlined,
        RestFilled        
    },
    props: ['courses'],
    data() {
        return {
            modal:{
                isOpen:false,
                data:{},
                title:"Modal",
                mode:""
            },
            columns:[
                {
                    title: '標題(中文)',
                    dataIndex: 'title_zh',
                },{
                    title: '標題(英文)',
                    dataIndex: 'title_en',
                },{
                    title: '開始日期',
                    dataIndex: 'start_date',
                },{
                    title: '結束日期',
                    dataIndex: 'end_date',
                },{
                    title: '上課時間',
                    dataIndex: 'class_time',
                },{
                    title: '時數',
                    dataIndex: 'hours',
                },{
                    title: 'Action',
                    dataIndex: 'operation',
                    key: 'operation',
                },
            ],
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
            labelCol: {
                style: {
                width: '150px',
                },
            },
        }
    },
    created(){
    },
    methods: {
        createRecord(record){
            this.modal.data={};
            this.modal.data.media=[];
            this.modal.mode="CREATE";
            this.modal.isOpen=true;
        },
        editRecord(record){
            this.modal.data={...record};
            this.modal.mode="EDIT";
            this.modal.isOpen=true;
        },
        storeRecord(){
            this.$refs.modalRef.validateFields().then(()=>{
                this.$inertia.post(route('admin.forms.store') , this.modal.data, {
                    onSuccess:(page)=>{
                        this.modal.isOpen=false;
                    },
                    onError:(err)=>{
                        console.log(err);
                    }
                });
            }).catch(err => {
                console.log(err);
            });
        },
        updateRecord(){
            console.log(this.modal.data);
            this.$refs.modalRef.validateFields().then(()=>{
                this.modal.data._method = 'PATCH';
                this.$inertia.post(route('admin.forms.update',this.modal.data.id), this.modal.data,{
                    onSuccess:(page)=>{
                        this.modal.isOpen=false;
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

        deleteRecord(record){
            if (!confirm('Are you sure want to remove?')) return;
            this.$inertia.delete(route('admin.forms.destroy', {form:record.id}),{
                onSuccess: (page)=>{
                    console.log(page);
                },
                onError: (error)=>{
                    alert(error.message);
                }

            });
        },
    },
}
</script>