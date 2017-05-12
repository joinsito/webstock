<template>
    <div class="table-responsive">
        <highcharts :options="options"></highcharts>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Name</th>
                <th>Site Url</th>
                <th>Description</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="web in webs">
                <td>
                    <span><img width=200px :src="imageLocation(web.id)"/></span>
                </td>
                <td>
                    <span>{{web.name}}</span>
                </td>
                <td>
                    <span>{{web.url}}</span>
                </td>
                <td>
                    <span>{{web.description}}</span>
                </td>

            </tr>
            </tbody>
        </table>
        <pagination :pagination="pagination" navClass="anyclass" size="pagination-sm" :callback="fetchIndexData" ></pagination>
        <button @click="signupWeb" class="btn btn-primary">Signup project</button>
    </div>
</template>
<script>
    import Vue from 'vue'
    import axios from 'axios'
    //similar to vue-resource

    export default {
        props: ['source', 'title'],
        data() {
            return {
                webs: {},
                pagination: {},
                options: {}
            }
        },
        created() {
            this.fetchIndexData()
        },
        methods: {
            fetchIndexData(page) {
                var vm = this
                if (page) {
                    var sourcepage = this.source + '?page='+page;
                }else {
                    var sourcepage = this.source;
                }
                axios.get(`${sourcepage}`)
                    .then(function(response) {
                        Vue.set(vm.$data, 'webs', response.data.webs.data)
                        Vue.set(vm.$data, 'pagination', response.data.webs)
                        Vue.set(vm.$data, 'options', response.data.options)
                    })
                    .catch(function(response) {
                        console.log(response)
                    })
            },
            signupWeb() {
                this.$root.currentView='signupweb';
            },
            imageLocation(webId) {
                return '/images/sites/'+webId;
            }
        }
    }

</script>