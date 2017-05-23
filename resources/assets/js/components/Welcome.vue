<template>
    <div class="jumbotron">
        <mycarousel></mycarousel>
        <div class="center">
            <p class="text-center">
                Latest additions
            </p>
        </div>
        <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Rank</th>
                <th>Site Url</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="web in webs" @click="gotoSite(web.id,web.url)" class="siterow">
                <!--<td>
                    <span><img width=200px :src="imageLocation(web.id)"/></span>
                </td>-->
                <td>
                    <span>{{web.alexa_rank}}</span>
                </td>
                <td>
                    <span>{{web.url}}</span>
                </td>
            </tr>
            </tbody>
        </table>
        <pagination :pagination="pagination" navClass="anyclass" size="pagination-sm" :callback="fetchIndexData" ></pagination>
    </div>
    </div>
</template>
<script>
    import Vue from 'vue'
    import axios from 'axios'
    import Carousel from "../../../../node_modules/vue-carousel/src/Carousel";
    //similar to vue-resource

    export default {
        components: {Carousel}, props: ['source', 'title'],
        data() {
            return {
                webs: {},
                pagination: {},
                options: {}
            }
        },
        created() {
            this.fetchIndexData()
            this.$on('paginationChangepage', function(page){
                this.fetchIndexData(page)
            });
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
            },
            gotoSite(siteId,siteUrl) {
                window.location.href = '/site/'+siteId+'/'+siteUrl;
            }
        }
    }

</script>