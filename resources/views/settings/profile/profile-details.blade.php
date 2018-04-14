<profile-details :user="user" inline-template>
    <div class="card card-default">
        <div class="card-header">
            <h3>
                {{__('Profile Details')}}
                <a v-if="profile && profile.published_at" class="pull-right" :href="profile.urls.web">Go to profile</a>
            </h3>
        </div>

        <div class="card-body">
            <!-- Success Message -->
            <div class="alert alert-success" v-if="form.successful">
                {{__('Your profile details has been updated!')}}
            </div>

            <div class="row" v-if="uploadedPhotos.length > 0">
              <photo-preview v-for="(photo, index) in uploadedPhotos" :key="index" :photo="photo"></photo-preview>
            </div>

            <div class="row" v-if="uploadedVideos.length > 0">
                <video-player v-for="(video, index) in uploadedVideos" :key="index" :video="video.url"></video-player>
            </div>  

            <div class="form-group row" v-if="profile && profile.id">
                <input type="file" name="files" class="form-control btn btn-link" accept="image/*,video/*" multiple @change="queueFilesForUpload">
            </div>

            <hr class="divider"></hr>     

            <form role="form">
                <!-- Training style -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">{{__('Training style')}}</label>

                    <div class="col-md-6">
                        <select class="custom-select" name="training_style" v-model="form.training_style">
                            @foreach(config('training.styles') as $style)
                                @if(optional(Auth::user()->profile)->training_style === $style)
                                    <option value="{{ $style }}" selected>{{ __($style) }}</option>
                                @else
                                    <option value="{{ $style }}">{{ __($style) }}</option>
                                @endif
                            @endforeach
                        </select>

                        <span class="invalid-feedback" v-show="form.errors.has('training_style')">
                            @{{ form.errors.get('training_style') }}
                        </span>
                    </div>
                </div>

                <!-- Training level -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">{{__('Training level')}}</label>

                    <div class="col-md-6">
                        <select class="custom-select" name="training_level" v-model="form.training_level">
                            @foreach(config('training.levels') as $level)
                                @if(optional(Auth::user()->profile)->training_level === $level)
                                    <option value="{{ $level }}" selected>{{ __($level) }}</option>
                                @else
                                    <option value="{{ $level }}">{{ __($level) }}</option>
                                @endif
                            @endforeach
                        </select>

                        <span class="invalid-feedback" v-show="form.errors.has('training_level')">
                            @{{ form.errors.get('training_level') }}
                        </span>
                    </div>
                </div>

                <!-- Goals -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">{{__('Goals')}}</label>

                    <div class="col-md-6">
                        <quill-editor
                            v-model="form.goals"
                            ref="goalsEditor"
                            :options="editorOptions"
                        ></quill-editor>

                        <span class="invalid-feedback" v-show="form.errors.has('goals')">
                            @{{ form.errors.get('goals') }}
                        </span>
                    </div>
                </div>

                <!-- Story -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label text-md-right">{{__('Story')}}</label>

                    <div class="col-md-6">
                        <quill-editor
                            v-model="form.story"
                            ref="goalsEditor"
                            :options="editorOptions"
                        ></quill-editor>

                        <span class="invalid-feedback" v-show="form.errors.has('story')">
                            @{{ form.errors.get('story') }}
                        </span>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="btn-group col-md-6 offset-md-4" role="group" aria-label="Actions">
                        <button type="submit" class="btn btn-primary"
                                @click.prevent="saveProfile"
                                :disabled="form.busy">

                            {{__('Save')}}
                        </button>

                        <button type="submit" class="btn btn-warning"
                                v-if="profile && profile.published_at"
                                @click.prevent="unpublishProfile"
                                :disabled="form.busy"
                        >
                            {{__('Unpublish')}}
                        </button>

                        <button type="submit" class="btn btn-success"
                                v-else
                                @click.prevent="publishProfile"
                                :disabled="form.busy"
                        >
                            {{__('Publish')}}
                        </button>
                    </div>                    
                </div>
            </form>
        </div>
    </div>
</profile-details>
