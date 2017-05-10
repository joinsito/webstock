<template>
    <form class="form-horizontal" method="post" role="form" @submit.prevent="addWeb" enctype="multipart/form-data">
        <legend>Site details</legend>
        <div :class="{'form-group': true, 'has-error': errors.has('sitename') }" >
            <label for="sitename" class="col-sm-2 control-label">Site Name</label>
            <div class="col-sm-10">
                <input title="Site Name" v-validate="'required'" v-model="sitename" type="text" :class="{'form-control': true, 'is-danger': errors.has('email') }" name="sitename" id="sitename" placeholder="You site name...">
                <span class="help-block with-errors" v-show="errors.has('sitename')">{{ errors.first('sitename') }}</span>
            </div>
        </div>
        <div :class="{'form-group': true, 'has-error': errors.has('siteurl') }">
            <label for="siteurl" class="col-sm-2 control-label">Site Url</label>
            <div class="col-sm-10">
                <input title="Site Url" v-validate="'required'" v-model="siteurl" type="text" class="form-control" name="siteurl" id="siteurl" placeholder="Your site url...">
                <span class="help-block with-errors" v-show="errors.has('siteurl')">{{ errors.first('siteurl') }}</span>
            </div>
        </div>
        <div :class="{'form-group': true, 'has-error': errors.has('sitedescription') }">
            <label for="sitedescription" class="col-sm-2 control-label">Site Description</label>
            <div class="col-sm-10">
                <textarea title="Site Description" v-validate="'required'" v-model="sitedescription" class="form-control" name="sitedescription" id="sitedescription" rows="3" placeholder="Write a short description describing your site"></textarea>
                <span class="help-block with-errors" v-show="errors.has('sitedescription')">{{ errors.first('sitedescription') }}</span>
            </div>
        </div>
        <div :class="{'form-group': true, 'has-error': errors.has('siteimage') }">
            <div v-if="!image">
                <label for="siteimage" class="col-sm-2 control-label">Select an image</label>
                <div class="col-sm-10">
                    <input title="Site image" v-validate="'required'" name="siteimage" id="siteimage" type="file" @change="onFileChange">
                    <span class="help-block with-errors" v-show="errors.has('siteimage')">{{ errors.first('siteimage') }}</span>
                </div>
            </div>
            <div v-else>
                <label class="col-sm-2 control-label">
                    Site image
                </label>
                <div class="col-sm-10">
                    <img :src="image" width="600px" />
                    <p>
                    <button class="btn btn-primary" @click="removeImage">Remove image</button>
                    </p>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Add site!</button>
    </form>
</template>
<script>
    export default {
        data () {
            return  {
                siteurl: '',
                sitename: '',
                sitedescription: '',
                result: '',
                image:''
            }
        },

        methods: {
            addWeb () {
                this.$validator.validateAll().then(() => {
                    // eslint-disable-next-line
                    axios.post('/api/signupwebform', {
                        site_url: this.siteurl,
                        site_name: this.sitename,
                        site_description: this.sitedescription,
                        site_image: this.image
                    }).then(response => {
                        // JSON responses are automatically parsed.
                        console.log(response.data);
                        this.result = response.data;
                        this.$root.currentView='signupwebsuccess';
                    }).catch(e => {
                        console.log("Error: "+e);
                    })
                }).catch(e => {
                    console.log("Validate error: "+e);
                });
            },
            onFileChange(e) {
                var files = e.target.files || e.dataTransfer.files;
                console.log(files[0].size);
                if (files[0].size>1000000) {
                    alert('File size is too large');
                    return;
                }
                if (!files.length)
                    return;
                this.createImage(files[0]);
            },
            createImage(file) {
                var image = new Image();
                var reader = new FileReader();
                var vm = this;

                reader.onload = (e) => {
                    vm.image = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            removeImage: function (e) {
                this.image = '';
            }
        }
    }
</script>