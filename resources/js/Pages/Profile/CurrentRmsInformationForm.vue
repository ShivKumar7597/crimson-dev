<template>
    <jet-form-section @submitted="updateCurrentRmsInformation">
        <template #title>
            Current RMS Information
        </template>

        <template #description>
            Update your Curent RMS API details
        </template>

        <template #form>
            <!-- Sub Domain -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="sub_domain" value="Sub Domain" />
                <jet-input id="sub_domain" type="text" class="mt-1 block w-full" v-model="form.sub_domain" ref="sub_domain" autocomplete="sub-domain" />
                <jet-input-error :message="form.error('sub_domain')" class="mt-2" />
            </div>

            <!-- Api Token -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="api_token" value="API Token" />
                <jet-input id="api_token" type="text" class="mt-1 block w-full" v-model="form.api_token" autocomplete="api-token" />
                <jet-input-error :message="form.error('api_token')" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </jet-button>
        </template>
    </jet-form-section>
</template>

<script>
    import JetButton from './../../Jetstream/Button'
    import JetFormSection from './../../Jetstream/FormSection'
    import JetInput from './../../Jetstream/Input'
    import JetInputError from './../../Jetstream/InputError'
    import JetLabel from './../../Jetstream/Label'
    import JetActionMessage from './../../Jetstream/ActionMessage'
    import JetSecondaryButton from './../../Jetstream/SecondaryButton'

    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            JetSecondaryButton,
        },

        props: ['sub_domain', 'api_token'],

        data() {
            return {
                form: this.$inertia.form({
                    '_method': 'PUT',
                    sub_domain: this.sub_domain,
                    api_token: this.api_token,
                }, {
                    bag: 'updateCurrentRmsInformation',
                    resetOnSuccess: false,
                }),

                photoPreview: null,
            }
        },

        methods: {
            updateCurrentRmsInformation() {
                this.form.post('/user/current-rms-information', {
                    preserveScroll: true
                });
            },
        },
    }
</script>
