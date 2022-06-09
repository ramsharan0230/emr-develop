<template>
     <div>
        <div v-if="!exportexceldetail"> No Item Download </div>
        <span v-html="exportexceldetail.route"></span>
     </div>
</template>
<script>
    export default {
        props: ['userinfo'],
          data () {
            return {
                loading: true,
                errored: false,
                exportexceldetail : {
                    user : this.userinfo,
                    message : null,
                    time : null,
                    route : null,
                }
            }
        },
        methods :
        {
            download: function () {
                window.open(this.exportexceldetail.route, "_blank");
            }
        },
        mounted() {
            console.log('Component mounted for billing report detail.')
            Echo.private('excelreadychanndel.'+this.userinfo)
                .listen('ExcelDownloadedEvent',(e) => {
                 console.log('billing export listing')
                this.exportexceldetail.route  = e.route
                this.exportexceldetail.message  = e.message
            })



        }

    }
</script>
