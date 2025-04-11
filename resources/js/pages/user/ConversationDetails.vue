<template>
    <div class="ps-lg-7 pt-4">
        <h1 class="fs-24 fw-700 opacity-80 mb-5 mt-3">{{ $t("query_with") }} {{conversation && conversation.receiver_shop}}</h1>
        <div v-if="loading">
            <div class="text-center">
                <div>{{ $t("loading_please_wait") }}</div>
            </div>
        </div>
        <div v-else>
            <v-card>   
                <h4 class="px-3 py-3 mb-3 border-bottom"> #{{conversation.title}} ( Between you & {{ conversation.receiver_shop }} )</h4>
                <div v-for="(conversationMessage, i) in conversation.messages" :key="i" :class="[ 'row mx-4 align-center py-2', { 'border-bottom' : i+1 != conversation.messages.length}]">
                    <div class="col-sm-12 pb-0 pb-md-2">
                        <div class="d-flex align-center"> 
                            <v-avatar size="60">
                                <img
                                    :src="conversationMessage.user_image"
                                    class="border border-4"
                                    @error="imageFallback($event)"
                                />
                            </v-avatar> 
                            <div class="ml-3">
                                <h5>{{ conversationMessage.user_name }}</h5>
                                <div>{{ conversationMessage.created_at }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="d-flex align-center">
                            <div> 
                                <div>{{ conversationMessage.message }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <v-form lazy-validation autocomplete="chrome-off" class="px-3 py-5" @submit.prevent="addNewMessage()">
                    <div class="mb-3">
                        <div class="mb-1 fs-13 fw-500">{{ $t('message') }}</div>
                        <v-textarea
                            v-model="form.message"
                            :placeholder="$t('message')"
                            hide-details="auto"
                            rows="4"
                            required
                            variant="outlined"
                        ></v-textarea>
                        <p v-for="error of v$.form.message.$errors" :key="error.$uid" class="text-red">
                            {{error.$message }}
                        </p>
                    </div> 
                    <div class="text-right mt-4"> 
                        <v-btn 
                            elevation="0"
                            type="submit"
                            color="primary"
                            :loading="sending" 
                        >{{ $t('send') }}
                        </v-btn>
                    </div>
                </v-form>
            </v-card>
        </div>
    </div>
</template>

<script>  
import { required } from "@vuelidate/validators";
import { useVuelidate } from '@vuelidate/core';
export default {
    data: () => ({
        loading: true,
        sending: false,
        conversation: null,
        v$: useVuelidate(),
        form:{
            message: "", 
            conversation_id:null,
        }
    }), 
    created(){
        this.getDetails(this.$route.params.slug);
        window.intervalCall = setInterval(() => {
            this.getDetails(this.$route.params.slug);
        }, 8*1000);
    },
    validations: {
        form: { 
            message: { required },
        }
    },
    computed:{
        messageErrors() {
            const errors = [];
            if (!this.v$.form.message.$dirty) return errors;
            !this.v$.form.message.required &&
                errors.push(this.$i18n.t("this_field_is_required"));
            return errors;
        },
    },
    methods:{
        async getDetails(id){
            const res = await this.call_api("get", `user/querries/${id}`);
            if(res.data.success){
                this.conversation = res.data.data; 
                this.form.conversation_id = res.data.data.id;
                this.loading = false;
            }else{
                this.snack({
                    message: res.data.message,
                    color: "red"
                });
                return;
            }
        },
        async addNewMessage(){
            // Prevents form submitting if it has error
        const isFormCorrect = await this.v$.$validate();
        if (!isFormCorrect) return;
             
            // submit data & reset if success
            this.sending = true;

            const res = await this.call_api("post", "user/new-message-query",this.form);
            if(res.data.success){  
                this.conversation = res.data.data;
                this.snack({ message: res.data.message });
                this.resetData();             
            }else{
                this.snack({
                    message: this.$i18n.t("something_went_wrong"),
                    color: "red"
                });
            }
            this.sending = false; 
        },
        resetData(){  
            this.form.message  = ""; 
            this.form.conversation_id  = this.conversation.id; 
            this.v$.form.$reset();
        },
    },
}
</script>