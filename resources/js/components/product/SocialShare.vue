<template>
    <div>
        <ShareNetwork
            network="facebook"
            :url="currentFullPath"
            :title="title"
        >
            <i class="lab la-facebook-f"></i>
        </ShareNetwork>
        <ShareNetwork
            network="Twitter"
            :url="currentFullPath"
            :title="title"
        >
            <i class="lab la-twitter"></i>
        </ShareNetwork>
        <ShareNetwork
            network="Pinterest"
            :url="currentFullPath"
            :title="title"
        >
            <i class="lab la-pinterest-p"></i>
        </ShareNetwork>
        <ShareNetwork
            network="LinkedIn"
            :url="currentFullPath"
            :title="title"
        >
            <i class="lab la-linkedin-in"></i>
        </ShareNetwork>
        <ShareNetwork
            network="Email"
            :url="currentFullPath"
            :title="title"
        >
            <i class="las la-envelope"></i>
        </ShareNetwork>
        <ShareNetwork
            network="WhatsApp"
            :url="currentFullPath"
            :title="title"
        >
            <i class="lab la-whatsapp"></i>
        </ShareNetwork>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
    props:{
        title: {
            type: String,
            default:  ''
        }
    },
    computed:{
        ...mapGetters("app",[
            "appUrl"
        ]),
        currentFullPath: function(){
            return this.appUrl + this.$route.fullPath
        }
    }
}
</script>