<template>
    <div>
        <div v-if="selected_url">
            URL: <a v-bind:href="selected_url" target="_blank">{{selected_url}}</a>
            <h3>Drag and drop below to preview image</h3>
        </div>
        <picture-input
                ref="pictureInput"
                width="600"
                height="600"
                class="preview"
                margin="16"
                accept="image/*"
                size="10"
                :hideChangeButton=true
                :custom-strings="{
                    upload: '<h1>Bummer!</h1>',
                    drag: 'Drag your image over here',
                    change: 'Change Photo',
                    remove: 'Remove photo',
                    fileType: 'You can only preview images'
                  }"
                @change="onChange">
        </picture-input>
    </div>
</template>

<script>
    import PictureInput from 'vue-picture-input'

    export default {
        name: 'app',
        data() {
            return {
                selected_url: null
            }
        },
        components: {
            PictureInput
        },
        methods: {
            onChange (image) {
                if (image) {
                    const data = new FormData();
                    const file = this.$refs.pictureInput.file;
                    data.append('image', file, file.name);

                    const config = {
                        headers: { 'Content-Type': 'multipart/form-data' }
                    };

                    const _this = this;

                    axios.post('/preview', data, config)
                        .then(function(response) {
                            _this.selected_url = response.data.path;
                        })
                        .catch(function(error) {
                            console.log(error);
                        });
                } else {
                    console.log('FileReader API not supported: use the <form>');
                }
            }
        }
    }
</script>