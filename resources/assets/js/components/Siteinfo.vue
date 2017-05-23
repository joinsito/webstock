<template>
    <div class="table-responsive">
        <pulse-loader v-show="loading"></pulse-loader>
        <table class="table table-hover">
            <thead>
            <tr>
                <th colspan="2"><h2>Site information</h2></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <span><strong>Site name</strong></span>
                </td>
                <td>
                    <span>{{site.name}}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span><strong>Site image</strong></span>
                </td>
                <td>
                    <span><img height=200px :src="imageLocation(site.id)"/></span>
                </td>
            </tr>
            <tr>
                <td>
                    <span><strong>Site description</strong></span>
                </td>
                <td>
                    <span>{{site.awis.description}}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span><strong>Site url</strong></span>
                </td>
                <td>
                    <span><a :href="'https://'+site.url" target="_blank">{{site.url}}</a></span>
                </td>
            </tr>
            <tr>
                <td>
                    <span><strong>Alexa rank</strong></span>
                </td>
                <td>
                    <span>{{site.alexa_rank}}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span><strong>Linked in</strong></span>
                </td>
                <td>
                    <span>{{site.awis.links}}</span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
    import Vue from 'vue'
    import axios from 'axios'
    //similar to vue-resource

    export default {
        props: ['siteid','source'],
        data() {
            return {
                site: {},
                loading : true
            }
        },
        created() {
            this.loading = true
            this.fetchSiteInfo()
        },
        methods: {
            fetchSiteInfo() {
                var vm = this
                var sourcepage = this.source + '/' + this.siteid
                axios.get(`${sourcepage}`)
                    .then(function(response) {
                        Vue.set(vm.$data, 'site', response.data.siteinfo)
                        vm.$data.loading = false
                    })
                    .catch(function(response) {
                        console.log(response)
                    })
            },imageLocation(webId) {
                return '/images/sites/'+webId+'.jpg';
            },
        }
    }

</script>