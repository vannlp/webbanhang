<template>
    <div>
        <div class="row">
            <div class="col-sm-3">
                <p class="mb-0">Full Name</p>
            </div>
            <div class="col-sm-9">
                <p class="text-muted mb-0" v-if="!isEdit">{{user.name}}</p>
                <input type="text" name="name" v-else class="form-control" :value="user.name">

            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col-sm-3">
                <p class="mb-0">Username</p>
            </div>
            <div class="col-sm-9">
                <p class="text-muted mb-0" v-if="!isEdit">{{user.username}}</p>
                <input type="text" name="username" v-else class="form-control" :value="user.username">
            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col-sm-3">
                <p class="mb-0">Email</p>
            </div>
            <div class="col-sm-9">
                <p class="text-muted mb-0" v-if="!isEdit">{{user.email}}</p>
                <input type="text" name="email" v-else class="form-control" :value="user.email" readonly>
            </div>
        </div>

        <hr>
        <div class="row">
            <div class="col-sm-3">
                <p class="mb-0">Quyền</p>
            </div>
            <div class="col-sm-9">
                <p class="text-muted mb-0" v-if="!isEdit">{{user.role.name}}</p>
                <input type="text" readonly v-else class="form-control" :value="user.role.name">
            </div>
        </div>

        <hr>
        <div class="row">
            <div class="col-sm-3">
                <p class="mb-0">Số điện thoại</p>
            </div>
            <div class="col-sm-9">
                <p class="text-muted mb-0" v-if="!isEdit">{{user.phone}}</p>
                <input type="text" name="phone" v-else class="form-control" :value="user.phone">
            </div>
        </div>

        <hr>
        <div class="row mt-3">
            <div class="col-3">
                <button class="btn btn-info mr-2" @click="handleIsEdit" type="button">Edit</button>
                <button type="submit" class="btn btn-primary" v-if="isEdit">Submit</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    // Các phương thức, dữ liệu và vòng đời của component Vue
    name: 'ProfileContent',
    data() {
        return {
            isEdit: false
        }
    },
    props: {
        user: {
            type: Object,
            required: false
        }
    },
    methods: {
        handleIsEdit: function () {
            if(this.isEdit) this.isEdit = false;
            else this.isEdit = true;
        }
    }

}
</script>
