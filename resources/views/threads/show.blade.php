@extends('layouts.app')

@section('content')
	<div class="container">
		{{-- 
		<thread-participants :thread-id="{{ $thread->id }}" inline-template>
			<div>
				<h3>Currently viewing the thread...</h3>
				<a v-for="user in participants" :key="user.id" :href="user.link">
					@{{ user.name }}, 
				</a>
			</div>
		</thread-participants>

		<hr class="divider"></hr>
		--}}

		<thread-card 
			:thread="{{ $thread->toJson() }}" 
			:photos="{{ $thread->getPhotoUrls() }}"
			:supports-photo-upload="true"
			:preview="false"
		></thread-card>

		<hr class="divider"></hr>

		<thread-replies :thread="{{ $thread->toJson() }}" :replies="{{ $thread->replies->toJson() }}" inline-template>
			<div>
				<h3 class="text-center">Replies</h3>

				<button 
					type="button" 
					class="btn btn-outline-primary mb-2" 
					v-if="! thread.locked"
					@click="creatingNewReply = ! creatingNewReply"
				>New reply</button>

				<div class="card" v-if="! thread.locked" v-show="creatingNewReply">
					<div class="card-header">
						<h3>Create a new reply</h3>
					</div>
					<div class="card-body">
						<form @submit.prevent class="mt-2">
							<div class="form-group row">
							    <label class="col-md-4 control-label">Title</label>

							    <div class="col-md-6">
							        <input type="text" class="form-control" name="title"
							               v-model="newReplyForm.title"
							               :class="{'is-invalid': newReplyForm.errors.has('title')}">

							        <span class="invalid-feedback" v-show="newReplyForm.errors.has('title')">
							            @{{ newReplyForm.errors.get('title') }}
							        </span>
							    </div>
							</div>

							<div class="form-group row">
							    <label class="col-md-4 control-label">Body</label>

							    <div class="col-md-6">
							    	<textarea 
							    		class="form-control" 
							            :class="{'is-invalid': newReplyForm.errors.has('body')}"
							    		name="body"
							    		v-model="newReplyForm.body"
							    	></textarea>

							        <span class="invalid-feedback" v-show="newReplyForm.errors.has('body')">
							           	@{{ newReplyForm.errors.get('body') }}
							       	</span>
								</div>
							</div>

							<button type="submit" class="btn btn-primary" @click="storeNewReply">Create!</button>
						</form>
					</div>
				</div>

				<reply-card v-if="replies.length > 0" v-for="reply in replies" :key="reply.id" :reply="reply"></reply-card>

				<div v-else class="alert alert-info" role="alert">
				  Nobody has replied, yet.
				</div>
			</div>
		</thread-replies>
	</div>
@endsection