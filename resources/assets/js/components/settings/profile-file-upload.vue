<template>
    <vue-transmit class="col-lg-12" tag="section" v-bind="options"
                  upload-area-classes="bg-faded"
                  ref="uploader"
                  >
      <div class="d-flex align-items-center justify-content-center w-100"
            style="height:50vh; border-radius: 1rem;">
        <button class="btn btn-primary"
                @click="triggerBrowse">Upload Files</button>
      </div>
      <!-- Scoped slot -->
      <template slot="files" slot-scope="props">
        <div v-for="(file, i) in props.files" :key="file.id" :class="{'mt-5': i === 0}">
          <div class="media">
            <img :src="file.dataUrl" class="img-fluid d-flex mr-3">
            <div class="media-body">
              <h3>{{ file.name }}</h3>
              <div class="progress" style="width: 50vw;">
                <div class="progress-bar bg-success"
                    :style="{width: file.upload.progress + '%'}"></div>
              </div>
              <pre>{{ file | json }} </pre>
            </div>
          </div>
        </div>
      </template>
    </vue-transmit>
</template>

<script>
	import VueTransmit from "vue-transmit";

	export default {
		components: {VueTransmit},

		props: { profile:Object },

		data () {
			return {
		      options: {
		      	autoQueue: true,
		      	autoProcessQueue: true,
		        acceptedFileTypes: ['image/*', 'video/*'],
		        clickable: true,
		        adapterOptions: {
		          url: function (files) {
		          	if (/(image)$/.test(files[0].type)) {
		          		return `/api/profiles/${this.profile.id}/photos`
		          	} else if (/(video)$/.test(files[0].type)) {
		          		return `/api/profiles/${this.profile.id}/videos`
		          	}

		          	return `/api/profiles/${this.profile.id}/documents`
		          }
		        },
		      }
			}
		},

	    methods: {
	      triggerBrowse() {
	        this.$refs.uploader.triggerBrowseFiles()
	      },
	    },

	    filters: {
	      json(value) {
	        return JSON.stringify(value, null, 2)
	      }
	    }
	}
</script>