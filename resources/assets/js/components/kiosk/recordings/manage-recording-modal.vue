<template>
		<div class="modal fade" id="manage-recording-modal">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-body">
						<div class="form-group">
					      	<input 
					      		ref="fileInput"
					      		type="file" 
					      		name="file_upload" 
					      		class="form-control-plaintext bg-none border-0" 
					      		accept="video/*" 
					      		multiple 
					      		@change="fileQueue.add($event.target.files)"
					      	>
						</div>

				        <form @submit.prevent>
							<div class="form-group row">
							    <label class="col-md-4 control-label">Category</label>
							    <div class="col-md-6">
							        <input type="text" class="form-control" name="category"
							               v-model="form.category"
							               :class="{'is-invalid': form.errors.has('category')}">
							        <span class="invalid-feedback" v-show="form.errors.has('category')">
							            {{ form.errors.get('category') }}
							        </span>
							    </div>
							</div>

							<div class="form-group row">
							    <label class="col-md-4 control-label">Title</label>
							    <div class="col-md-6">
							        <input type="text" class="form-control" name="title"
							               v-model="form.title"
							               :class="{'is-invalid': form.errors.has('title')}">
							        <span class="invalid-feedback" v-show="form.errors.has('title')">
							            {{ form.errors.get('title') }}
							        </span>
							    </div>
							</div>

							<div class="form-group row">
							    <label class="col-md-4 control-label">Summary</label>
							    <div class="col-md-6">
							        <input  type="text" class="form-control" name="summary"
							                v-model="form.summary"
							                :class="{'is-invalid': form.errors.has('summary')}">
							        <span class="invalid-feedback" v-show="form.errors.has('summary')">
							            {{ form.errors.get('summary') }}
							        </span>
							    </div>
							</div>	

							<div class="form-group row">
							    <label class="col-md-4 control-label">Body</label>
							    <div class="col-md-6">
									<quill-editor
										v-model="form.body"
										ref="editor"
										:options="editorOptions"
									></quill-editor>
							        <span class="invalid-feedback" v-show="form.errors.has('body')">
							            @{{ form.errors.get('body') }}
							        </span>
							    </div>
							</div>			
						</form>
					</div>
					<div class="modal-footer">
						<a :href="recording.link" class="btn btn-link">Go to recording</a>
					    <button class="btn btn-primary" @click.prevent="update" :disabled="form.busy">
					        <span v-if="form.busy">
					            <i class="fa fa-btn fa-spinner fa-spin"></i> Updating
					        </span>

					      <span v-else>
					            <i class="fa fa-btn fa-check-circle"></i> Update
					        </span>
					    </button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
</template>

<script>
	import FileUploadQueue from '../../file-upload-queue';

	export default {
		data () {
			return {
				recording: {},

				form: new SparkForm({
					category: '', 
					title: '', 
					summary: '', 
					body: ''
				}),

				editorOptions: { 
					modules: { 
						markdownShortcuts:{},
						toolbar: [
							[{
								header: [1,2,3,4,5]
							}],
							[
								'blockquote', 'code-block', 
								'bold', 'italic', 'underline', 'strike',
							],
							[{ 'list': 'ordered'}, { 'list': 'bullet' }],	
						]
					} 
				},

				fileQueue: new FileUploadQueue([
					{ name: 'videos', mime: 'videos' }
				])
			}
		},

		methods: {
			show (recording) {
				if (this.recording.id !== recording.id) {
					this.fileQueue.reset();
					this.$refs.fileInput.value = '';

					this.recording = recording;

					this.form.title = recording.title;
					this.form.category = recording.category;
					this.form.summary = recording.summary;
					this.form.body = recording.body;
				}

				$('#manage-recording-modal').modal('show');
			},

			hide () {
				this.recording = {};

				this.form.title ='';
				this.form.category = '';
				this.form.summary = '';
				this.form.body = '';

				this.fileQueue.reset();
				this.$refs.fileInput.value = '';

				$('#manage-recording-modal').modal('hide');
			},

			async update () {
				const recording = await Spark.put(`/api/recordings/${this.recording.slug}`, this.form);

				this.fileQueue.upload((files, name) => {
					if (files.length > 0) {
						axios.post(`/api/recordings/${this.recording.slug}/${name}`, files)
					}
				});
			}
		}	
	}
</script>