

<template>
    <div>
        <label for="">Ảnh đại diện</label><br>
        <div class="row m-0" v-if="selectedFile.length > 0" >
            <img :src="file.src" class="img-thumbnail" v-for="file in selectedFile" width='100' alt="" 
                style="cursor: pointer; object-fit: cover; margin: 0px 2px;" 
                type="button"
                data-toggle="modal"
                data-target="#mediaModal"
            >
        </div>
        <div class="row m-0" v-else>
            <img src="https://e7.pngegg.com/pngimages/480/73/png-clipart-white-paper-illustration-post-it-note-paper-post-it-note-angle-white-thumbnail.png" class="img-thumbnail" width='100' alt="" style="cursor: pointer;" 
                type="button"
                data-toggle="modal"
                data-target="#mediaModal"
            >
        </div>
        <input type="hidden" :value="value" :name="name" />
        
    </div>

    <div class="modal fade" id="mediaModal" tabindex="-1" aria-labelledby="mediaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediaModalLabel">Quản lý media</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <select name="type" class="form-control-sm mr-1" id="">
                                <option value="">Tất cả</option>
                                <option value="image">Hình ảnh</option>
                            </select>
                            <select name="month" class="form-control-sm mr-1" id="" v-model="searchParam.month">
                                <option value="">Tất cả các ngày</option>
                                <option :value="month.month" v-for="month in monthsWithFile">{{ month.month }}</option>
                            </select>

                            <button @click="handleOnClickSearch" class="btn btn-primary btn-sm mr-1" type="button">Tìm kiếm</button>

                            <button @click="uploadFiles" type="button" class="btn btn-success btn-sm">Upload</button>
                        </div>
                        <input type="file" @change="handleFileChange" style="display: none;" accept="image/*" ref="fileInput" multiple />
                    </div>
                    
                    <div class="row" style="overflow: auto; height: 400px;">    
                        <div class="col-2" v-if="!(isLoading)" v-for="file in files">
                            <div v-if="selectedFile.some(item => item.id === file.id)" class="p-1 fileSelected" style="cursor: pointer;" @click="handleSelectedFile(file.id, file.url)">
                                <img :src="file.url" class="img-fluid img-thumbnail active" :alt="file.alt">
                            </div>
                            <div v-else  class="p-1 fileSelected" style="cursor: pointer;" @click="handleSelectedFile(file.id, file.url)">
                                <img :src="file.url" class="img-fluid img-thumbnail" :alt="file.alt">
                            </div>
                        </div>
                        <LoadingComponent display="block" v-else class="col-12 pt-1" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" form="formUpload" class="btn btn-primary" data-dismiss="modal">Submit</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from './LoadingComponent.vue';

export default {
    name: 'media',
    props:{
        link: {
            type: Object,
            require: false
        },
        manyFile: {
            type: String,
            require: false
        },
        name: {
            type: String,
            default: "file_id"
        },
    },
    components: {
        LoadingComponent
    },
    data() {
        return {
            files: [],
            monthsWithFile: [],
            selectedFile: [],
            fileUploads: null,
            searchParam: {
                month: ''
            },
            isLoading: false,
            value: ""
        }
    },

    mounted() {
       this.handleInitData();
       
    },

    methods: {
        async handleOnClickSearch() {
            let params = { month: this.searchParam.month };
            await this.getInitData({params});

        },

        handleFileChange: async function(event) {
            this.fileUploads = event.target.files;

            try {
                const formData = new FormData();
                for (let i = 0; i < this.fileUploads.length; i++) {
                    formData.append(`files[${i}]`, this.fileUploads[i]);
                }

                const res = await window.apis.post(this.link.linkUploadImages, formData,{
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                await this.handleInitData();

            } catch (error) {
                let res = error.response;
                if(res){
                    if(res.status == 422) {
                        alert("Vui lòng tải file đúng định dạng");
                        return;
                    }
                }
                alert("có lỗi phía server");
                console.error(res);
            }
        },

        async uploadFiles() {
            const fileInputElement = this.$refs.fileInput;
            fileInputElement.click();
        },
        
        handleEvent: function (id) {
            
        },

        handleSelectedFile: function (id, src) {
            if(this.selectedFile.some(item => item.id === id)) {
                let newSelecteFile = this.selectedFile.filter(item => item.id != id);
                this.selectedFile = newSelecteFile;
                return;
            }

            if(this.selectedFile.length >= this.manyFile) {
                alert(`Chỉ chọn ${this.manyFile} ảnh`);
                return;
            }

            this.selectedFile = [...this.selectedFile, {id: id, src: src}];
            this.handleValue();
        },

        handleInitData: async function ()  {    
            this.isLoading = true; 
            await this.getInitData({params: {}});
            this.isLoading = false; 
            
        },

        getInitData: async function({params={}}) {
            console.log(params);
            try {
                let res = await window.apis.get(this.link.linkInitData, {
                    params: params
                });
                let data = res.data;
                this.files = data.files;
                this.monthsWithFile = data.monthsWithFile;
            } catch (error) {
                console.error(error);
            }
        },

        handleValue() {
            
            let newValue =  this.selectedFile.map((data) => {
                return data.id;
            })

            newValue = JSON.stringify(newValue);
            this.value = newValue;
        }
    },
}
</script>

<style>
.fileSelected .active{
    box-shadow: rgba(85, 85, 85, 0.593) 0px 3px 8px;
}
</style>