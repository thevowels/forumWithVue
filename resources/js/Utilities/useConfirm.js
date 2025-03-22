import {reactive, readonly} from "vue";

const globalState = reactive({
    show:false,
    title:'',
    message:'',
    resolver: null,
})

export const useConfirm = () => {

    const resetModel = () => {
        globalState.title = '';
        globalState.message = '';
        globalState.show = false;
        globalState.resolver = null;
    }

    return{
        state: readonly(globalState),
        confirmation: (message)=> {
            globalState.show=true;
            globalState.message=message;
            globalState.title="Please confirm";

            return new Promise((resolver)=>{
                globalState.resolver=resolver;
            })
        },
        confirm: ()=> {
            if(globalState.resolver){
                globalState.resolver(true);
            }
            resetModel();
        },
        cancel: () =>{
            if(globalState.resolver){
                globalState.resolver(false);
            }
            resetModel();
        }
    }
}
