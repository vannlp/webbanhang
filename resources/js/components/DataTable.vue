<template>
    <div class="table-responsive">
        <table class="table" id="{{ id }}">
            <thead class="thead-dark">
                <tr>
                    <th v-for="header in headers">
                        {{ header }}
                    </th>
                </tr>
            </thead>

            <tbody>
                
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: "data-table",
    props: {
        headers: {
            type: Array,
            required: true
        },
        route: {
            type: String,
            required:true
        },
        id: {
            type: String,
            required: true
        }
    },
    mounted: function() {
        $(`#${this.id}`).DataTable
    }
}
</script>