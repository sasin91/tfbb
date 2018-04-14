<spark-profile :user="user" inline-template>
    <div>
        <!-- Update Profile Photo -->
        @include('spark::settings.profile.update-profile-photo')

        <!-- Update Contact Information -->
        @include('spark::settings.profile.update-contact-information')

        <!-- Save fitness goals and story -->
        @include('settings.profile.profile-details')
    </div>
</spark-profile>
