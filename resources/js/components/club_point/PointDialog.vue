<template>
    <v-dialog v-model="isVisible" max-width="700px" @click:outside="closeDialog">
        <div class="white pa-5 rounded text-center" v-if="activeClubPoint">
        <h2 class="mb-3 text-capitalize">{{ $t('convert_club_points_to_wallet') }}</h2>  
        <v-btn class="mr-2" color="success" elevation="0" @click.stop="convertClubPoint" :loading="loading" :disabled="loading">{{ $t('convert_now') }}</v-btn>
        <v-btn class="ml-2" color="warning"  elevation="0" @click.stop="closeDialog" v-if="!loading">{{ $t('cancel') }}</v-btn>
        </div>
    </v-dialog>
</template>

<script> 
export default { 
    props: {
        show: { type: Boolean, required: true, default: false },
        activeClubPoint: { required: true, default: null },
    },
    data: () => ({
        loading: false,    
    }), 
    computed: { 
        isVisible: {
            get: function(){
                return this.show
            },
            set: function(newValue){}
        }, 
    }, 
    methods:{ 
        closeDialog(){
            this.isVisible = false;
            this.$emit('close')
        }, 

        async convertClubPoint(){
            if(this.activeClubPoint !==null){
                this.loading = true;
                let formData = {
                    id: this.activeClubPoint.id
                } 
                let res = await this.call_api("post", `user/convert-point-into-wallet`, formData); 
                if(res.data == 3){
                    this.snack({ message: this.$i18n.t("the_order_is_not_paid"), color: "red" }); 
                    setTimeout(() => {
                        this.loading = false; 
                    }, 2 * 1000);
                }else{ 
                this.snack({ message: this.$i18n.t("club_point_converted_to_wallet_successfully"), color: "green" }); 
                setTimeout(() => {
                    this.loading = false;
                    window.location.reload();
                }, 2 * 1000);
                }
            }
        }
    }
}
</script>